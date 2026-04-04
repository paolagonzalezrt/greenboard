<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslatableText extends Model
{
    protected $fillable = [
        'key',
        'group',
        'source_text',
        'source_locale',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Obtener todas las traducciones de este texto.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DynamicTranslation::class);
    }

    /**
     * Obtener la traducción para un idioma específico.
     */
    public function getTranslation(string $locale): ?string
    {
        $translation = $this->translations()
            ->where('locale', $locale)
            ->first();

        return $translation?->translated_text;
    }

    /**
     * Obtener texto traducido o el original si no existe.
     */
    public function getTextForLocale(string $locale): string
    {
        if ($locale === $this->source_locale) {
            return $this->source_text;
        }

        return $this->getTranslation($locale) ?? $this->source_text;
    }

    /**
     * Scope para filtrar por grupo.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope para textos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
