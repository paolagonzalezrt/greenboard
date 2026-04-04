<?php

namespace App\Services\Translation\Contracts;

interface TranslationProviderInterface
{
    /**
     * Traducir un texto.
     *
     * @param string $text Texto a traducir
     * @param string $targetLocale Idioma destino (es, en, de)
     * @param string|null $sourceLocale Idioma origen (auto-detectar si es null)
     * @return string Texto traducido
     * @throws \App\Services\Translation\Exceptions\TranslationException
     */
    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string;

    /**
     * Traducir múltiples textos.
     *
     * @param array $texts Array de textos a traducir
     * @param string $targetLocale Idioma destino
     * @param string|null $sourceLocale Idioma origen
     * @return array Array de textos traducidos
     */
    public function translateBatch(array $texts, string $targetLocale, ?string $sourceLocale = null): array;

    /**
     * Verificar si el proveedor está configurado correctamente.
     */
    public function isConfigured(): bool;

    /**
     * Obtener el nombre del proveedor.
     */
    public function getProviderName(): string;
}
