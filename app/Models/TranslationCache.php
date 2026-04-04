<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationCache extends Model
{
    protected $table = 'translation_cache';

    protected $fillable = [
        'cache_key',
        'source_locale',
        'target_locale',
        'source_text',
        'translated_text',
        'service',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Generar clave de cache única.
     */
    public static function generateCacheKey(string $text, string $sourceLocale, string $targetLocale): string
    {
        return md5("{$sourceLocale}:{$targetLocale}:" . trim($text));
    }

    /**
     * Buscar traducción en cache.
     */
    public static function findCached(string $text, string $sourceLocale, string $targetLocale): ?self
    {
        $cacheKey = self::generateCacheKey($text, $sourceLocale, $targetLocale);

        return self::where('cache_key', $cacheKey)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    /**
     * Guardar traducción en cache.
     */
    public static function store(
        string $sourceText,
        string $translatedText,
        string $sourceLocale,
        string $targetLocale,
        string $service,
        ?int $ttlSeconds = null
    ): self {
        $cacheKey = self::generateCacheKey($sourceText, $sourceLocale, $targetLocale);
        $ttlSeconds = $ttlSeconds ?? config('localization.cache.ttl', 86400 * 30);

        return self::updateOrCreate(
            ['cache_key' => $cacheKey],
            [
                'source_locale' => $sourceLocale,
                'target_locale' => $targetLocale,
                'source_text' => $sourceText,
                'translated_text' => $translatedText,
                'service' => $service,
                'expires_at' => now()->addSeconds($ttlSeconds),
            ]
        );
    }

    /**
     * Limpiar cache expirado.
     */
    public static function clearExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
