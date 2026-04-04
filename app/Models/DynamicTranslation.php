<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicTranslation extends Model
{
    protected $fillable = [
        'translatable_text_id',
        'locale',
        'translated_text',
        'is_auto_translated',
        'is_reviewed',
        'translated_at',
    ];

    protected $casts = [
        'is_auto_translated' => 'boolean',
        'is_reviewed' => 'boolean',
        'translated_at' => 'datetime',
    ];

    /**
     * Obtener el texto traducible padre.
     */
    public function translatableText(): BelongsTo
    {
        return $this->belongsTo(TranslatableText::class);
    }

    /**
     * Scope para un idioma específico.
     */
    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope para traducciones automáticas.
     */
    public function scopeAutoTranslated($query)
    {
        return $query->where('is_auto_translated', true);
    }

    /**
     * Scope para traducciones pendientes de revisión.
     */
    public function scopePendingReview($query)
    {
        return $query->where('is_auto_translated', true)
            ->where('is_reviewed', false);
    }
}
