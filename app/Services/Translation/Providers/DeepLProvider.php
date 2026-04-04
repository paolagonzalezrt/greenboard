<?php

namespace App\Services\Translation\Providers;

use App\Services\Translation\Contracts\TranslationProviderInterface;
use App\Services\Translation\Exceptions\TranslationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepLProvider implements TranslationProviderInterface
{
    private string $apiKey;
    private string $apiUrl;

    // Mapeo de códigos de idioma a códigos DeepL
    private array $localeMap = [
        'es' => 'ES',
        'en' => 'EN',
        'de' => 'DE',
    ];

    public function __construct()
    {
        $this->apiKey = config('localization.deepl.api_key', '');
        $this->apiUrl = config('localization.deepl.api_url', 'https://api-free.deepl.com/v2/translate');
    }

    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string
    {
        if (!$this->isConfigured()) {
            throw new TranslationException(
                'DeepL API key not configured',
                $this->getProviderName()
            );
        }

        $targetLang = $this->mapLocale($targetLocale);
        $sourceLang = $sourceLocale ? $this->mapLocale($sourceLocale) : null;

        try {
            $params = [
                'text' => [$text],
                'target_lang' => $targetLang,
            ];

            if ($sourceLang) {
                $params['source_lang'] = $sourceLang;
            }

            $response = Http::withHeaders([
                'Authorization' => "DeepL-Auth-Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $params);

            if ($response->failed()) {
                throw new TranslationException(
                    "DeepL API error: {$response->status()}",
                    $this->getProviderName(),
                    ['response' => $response->body()]
                );
            }

            $data = $response->json();

            return $data['translations'][0]['text'] ?? $text;
        } catch (TranslationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('DeepL translation error', [
                'message' => $e->getMessage(),
                'text' => substr($text, 0, 100),
                'target' => $targetLocale,
            ]);

            throw new TranslationException(
                "Translation failed: {$e->getMessage()}",
                $this->getProviderName(),
                null,
                0,
                $e
            );
        }
    }

    public function translateBatch(array $texts, string $targetLocale, ?string $sourceLocale = null): array
    {
        if (!$this->isConfigured()) {
            throw new TranslationException(
                'DeepL API key not configured',
                $this->getProviderName()
            );
        }

        $targetLang = $this->mapLocale($targetLocale);
        $sourceLang = $sourceLocale ? $this->mapLocale($sourceLocale) : null;

        try {
            $params = [
                'text' => $texts,
                'target_lang' => $targetLang,
            ];

            if ($sourceLang) {
                $params['source_lang'] = $sourceLang;
            }

            $response = Http::withHeaders([
                'Authorization' => "DeepL-Auth-Key {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $params);

            if ($response->failed()) {
                throw new TranslationException(
                    "DeepL API error: {$response->status()}",
                    $this->getProviderName()
                );
            }

            $data = $response->json();

            return array_map(
                fn($item) => $item['text'],
                $data['translations'] ?? []
            );
        } catch (TranslationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new TranslationException(
                "Batch translation failed: {$e->getMessage()}",
                $this->getProviderName(),
                null,
                0,
                $e
            );
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function getProviderName(): string
    {
        return 'deepl';
    }

    private function mapLocale(string $locale): string
    {
        return $this->localeMap[$locale] ?? strtoupper($locale);
    }

    /**
     * Get DeepL API usage and quota information.
     */
    public function getUsage(): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('DeepL API key not configured');
        }

        try {
            $usageUrl = str_contains($this->apiUrl, 'api-free')
                ? 'https://api-free.deepl.com/v2/usage'
                : 'https://api.deepl.com/v2/usage';

            $response = Http::withHeaders([
                'Authorization' => "DeepL-Auth-Key {$this->apiKey}",
            ])->get($usageUrl);

            if ($response->failed()) {
                throw new \Exception("DeepL API error: {$response->status()}");
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to fetch DeepL usage', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
