<?php

namespace App\Services\Translation\Providers;

use App\Services\Translation\Contracts\TranslationProviderInterface;

/**
 * Proveedor de traducción dummy que NO traduce nada.
 * Útil para desarrollo/testing sin gastar caracteres de API.
 */
class PassthroughProvider implements TranslationProviderInterface
{
    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string
    {
        // Devuelve el texto original sin traducir
        return $text;
    }

    public function translateBatch(array $texts, string $targetLocale, ?string $sourceLocale = null): array
    {
        // Devuelve todos los textos sin traducir
        return $texts;
    }

    public function isConfigured(): bool
    {
        return true; // Siempre "configurado" (no necesita API key)
    }

    public function getProviderName(): string
    {
        return 'passthrough';
    }
}
