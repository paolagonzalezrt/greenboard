<?php

namespace App\Services\Translation;

use App\Models\DynamicTranslation;
use App\Models\TranslatableText;
use App\Models\TranslationCache;
use App\Services\Translation\Contracts\TranslationProviderInterface;
use App\Services\Translation\Exceptions\TranslationException;
use App\Services\Translation\Providers\DeepLProvider;
use App\Services\Translation\Providers\GoogleTranslateProvider;
use App\Services\Translation\Providers\PassthroughProvider;
use App\Jobs\TranslateTextJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    private TranslationProviderInterface $provider;
    private bool $cacheEnabled;
    private int $cacheTtl;
    private string $cachePrefix;

    public function __construct(?TranslationProviderInterface $provider = null)
    {
        $this->provider = $provider ?? $this->resolveProvider();
        $this->cacheEnabled = config('localization.cache.enabled', true);
        $this->cacheTtl = config('localization.cache.ttl', 86400 * 30);
        $this->cachePrefix = config('localization.cache.prefix', 'translation_');
    }

    /**
     * Resolver el proveedor de traducción según configuración.
     */
    private function resolveProvider(): TranslationProviderInterface
    {
        // Si las traducciones están deshabilitadas, usar proveedor dummy
        if (!config('localization.translations_enabled', true)) {
            return new PassthroughProvider();
        }

        $service = config('localization.translation_service', 'deepl');

        return match ($service) {
            'google' => new GoogleTranslateProvider(),
            'passthrough' => new PassthroughProvider(),
            default => new DeepLProvider(),
        };
    }

    /**
     * Traducir un texto con cache.
     */
    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string
    {
        $sourceLocale = $sourceLocale ?? config('localization.default_locale', 'es');

        // Si el idioma es el mismo, devolver el texto original
        if ($targetLocale === $sourceLocale) {
            return $text;
        }

        // Verificar cache
        if ($this->cacheEnabled) {
            $cached = $this->getFromCache($text, $sourceLocale, $targetLocale);
            if ($cached !== null) {
                return $cached;
            }
        }

        // Traducir
        try {
            $translated = $this->provider->translate($text, $targetLocale, $sourceLocale);

            // Guardar en cache
            if ($this->cacheEnabled) {
                $this->saveToCache($text, $translated, $sourceLocale, $targetLocale);
            }

            return $translated;
        } catch (TranslationException $e) {
            Log::warning('Translation failed, returning original text', [
                'error' => $e->getMessage(),
                'provider' => $e->getProvider(),
            ]);

            return $text;
        }
    }

    /**
     * Traducir usando cola (async).
     */
    public function translateAsync(string $text, string $targetLocale, ?string $sourceLocale = null): void
    {
        if (config('localization.use_queue', false)) {
            TranslateTextJob::dispatch($text, $targetLocale, $sourceLocale)
                ->onQueue(config('localization.queue_name', 'translations'));
        } else {
            $this->translate($text, $targetLocale, $sourceLocale);
        }
    }

    /**
     * Traducir múltiples textos.
     */
    public function translateBatch(array $texts, string $targetLocale, ?string $sourceLocale = null): array
    {
        $sourceLocale = $sourceLocale ?? config('localization.default_locale', 'es');

        if ($targetLocale === $sourceLocale) {
            return $texts;
        }

        $results = [];
        $toTranslate = [];
        $toTranslateKeys = [];

        // Verificar cache para cada texto
        foreach ($texts as $key => $text) {
            if ($this->cacheEnabled) {
                $cached = $this->getFromCache($text, $sourceLocale, $targetLocale);
                if ($cached !== null) {
                    $results[$key] = $cached;
                    continue;
                }
            }
            $toTranslate[] = $text;
            $toTranslateKeys[] = $key;
        }

        // Traducir los textos que no estaban en cache
        if (!empty($toTranslate)) {
            try {
                $translated = $this->provider->translateBatch($toTranslate, $targetLocale, $sourceLocale);

                foreach ($translated as $index => $translatedText) {
                    $originalKey = $toTranslateKeys[$index];
                    $originalText = $toTranslate[$index];
                    $results[$originalKey] = $translatedText;

                    if ($this->cacheEnabled) {
                        $this->saveToCache($originalText, $translatedText, $sourceLocale, $targetLocale);
                    }
                }
            } catch (TranslationException $e) {
                Log::warning('Batch translation failed', ['error' => $e->getMessage()]);

                // Devolver textos originales para los que fallaron
                foreach ($toTranslateKeys as $index => $key) {
                    $results[$key] = $toTranslate[$index];
                }
            }
        }

        // Ordenar resultados según keys originales
        ksort($results);

        return $results;
    }

    /**
     * Traducir un texto dinámico (de base de datos).
     */
    public function translateDynamic(TranslatableText $translatableText, string $targetLocale): string
    {
        // Verificar si ya existe traducción
        $existingTranslation = $translatableText->getTranslation($targetLocale);
        if ($existingTranslation) {
            return $existingTranslation;
        }

        // Traducir y guardar
        $translated = $this->translate(
            $translatableText->source_text,
            $targetLocale,
            $translatableText->source_locale
        );

        DynamicTranslation::create([
            'translatable_text_id' => $translatableText->id,
            'locale' => $targetLocale,
            'translated_text' => $translated,
            'is_auto_translated' => true,
            'translated_at' => now(),
        ]);

        return $translated;
    }

    /**
     * Traducir todos los textos dinámicos pendientes para un idioma.
     */
    public function translateAllDynamicTexts(string $targetLocale): int
    {
        $count = 0;
        $texts = TranslatableText::active()
            ->whereDoesntHave('translations', function ($query) use ($targetLocale) {
                $query->where('locale', $targetLocale);
            })
            ->where('source_locale', '!=', $targetLocale)
            ->get();

        foreach ($texts as $text) {
            try {
                $this->translateDynamic($text, $targetLocale);
                $count++;
            } catch (\Exception $e) {
                Log::error('Failed to translate dynamic text', [
                    'text_id' => $text->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Obtener traducción de cache.
     */
    private function getFromCache(string $text, string $sourceLocale, string $targetLocale): ?string
    {
        // Primero intentar cache en memoria (Redis/File)
        $cacheKey = $this->getCacheKey($text, $sourceLocale, $targetLocale);
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        // Luego intentar cache en base de datos
        $dbCache = TranslationCache::findCached($text, $sourceLocale, $targetLocale);
        if ($dbCache) {
            // Guardar también en cache de memoria para acceso más rápido
            Cache::put($cacheKey, $dbCache->translated_text, $this->cacheTtl);
            return $dbCache->translated_text;
        }

        return null;
    }

    /**
     * Guardar traducción en cache.
     */
    private function saveToCache(string $text, string $translated, string $sourceLocale, string $targetLocale): void
    {
        // Cache en memoria
        $cacheKey = $this->getCacheKey($text, $sourceLocale, $targetLocale);
        Cache::put($cacheKey, $translated, $this->cacheTtl);

        // Cache en base de datos (persistente)
        TranslationCache::store(
            $text,
            $translated,
            $sourceLocale,
            $targetLocale,
            $this->provider->getProviderName(),
            $this->cacheTtl
        );
    }

    /**
     * Generar clave de cache.
     */
    private function getCacheKey(string $text, string $sourceLocale, string $targetLocale): string
    {
        return $this->cachePrefix . md5("{$sourceLocale}:{$targetLocale}:{$text}");
    }

    /**
     * Limpiar cache expirado.
     */
    public function clearExpiredCache(): int
    {
        return TranslationCache::clearExpired();
    }

    /**
     * Verificar si el proveedor está configurado.
     */
    public function isProviderConfigured(): bool
    {
        return $this->provider->isConfigured();
    }

    /**
     * Obtener nombre del proveedor activo.
     */
    public function getProviderName(): string
    {
        return $this->provider->getProviderName();
    }

    /**
     * Obtener información de uso de la API (si el proveedor lo soporta).
     */
    public function getUsage(): ?array
    {
        try {
            if (method_exists($this->provider, 'getUsage')) {
                return $this->provider->getUsage();
            }
        } catch (\Exception $e) {
            Log::error('Failed to retrieve usage information', [
                'provider' => $this->getProviderName(),
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
