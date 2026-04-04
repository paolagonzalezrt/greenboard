<?php

use App\Models\TranslatableText;
use App\Services\Translation\TranslationService;
use Illuminate\Support\Facades\Cache;

if (!function_exists('trans_dynamic')) {
    /**
     * Traducir un texto dinámico de base de datos.
     *
     * @param string $key La clave del texto (ej: 'site.welcome_message')
     * @param string|null $locale El idioma destino (usa el actual si es null)
     * @param string|null $default Texto por defecto si no se encuentra
     * @return string
     */
    function trans_dynamic(string $key, ?string $locale = null, ?string $default = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $cacheKey = "dynamic_trans_{$key}_{$locale}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $locale, $default) {
            $text = TranslatableText::where('key', $key)->first();

            if (!$text) {
                return $default ?? $key;
            }

            // Si es el idioma origen, devolver el texto original
            if ($text->source_locale === $locale) {
                return $text->source_text;
            }

            // Buscar traducción existente
            $translation = $text->getTranslation($locale);
            if ($translation) {
                return $translation;
            }

            // Intentar traducir automáticamente
            try {
                $translationService = app(TranslationService::class);
                return $translationService->translateDynamic($text, $locale);
            } catch (\Exception $e) {
                return $text->source_text;
            }
        });
    }
}

if (!function_exists('__d')) {
    /**
     * Alias corto para trans_dynamic.
     */
    function __d(string $key, ?string $locale = null, ?string $default = null): string
    {
        return trans_dynamic($key, $locale, $default);
    }
}
