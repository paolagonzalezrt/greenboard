<?php

namespace App\Observers;

use App\Models\Tip;
use App\Models\TranslatableText;
use App\Services\Translation\TranslationService;
use Illuminate\Support\Facades\Log;

class TipObserver
{
    private TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Handle the Tip "created" event.
     */
    public function created(Tip $tip): void
    {
        // Registrar el título para traducción
        $titleRecord = $this->registerTextForTranslation(
            "tip.{$tip->id}.title",
            $tip->title,
            'tip_title'
        );

        // Registrar la descripción para traducción
        $descriptionRecord = $this->registerTextForTranslation(
            "tip.{$tip->id}.description",
            $tip->description,
            'tip_description'
        );

        // Traducir inmediatamente a todos los idiomas soportados
        $this->translateToAllLocales($titleRecord);
        $this->translateToAllLocales($descriptionRecord);

        Log::info('Tip registered and translated', [
            'tip_id' => $tip->id,
            'title' => $tip->title,
        ]);
    }

    /**
     * Handle the Tip "updated" event.
     */
    public function updated(Tip $tip): void
    {
        // Actualizar traducciones si el contenido cambió
        if ($tip->isDirty(['title', 'description'])) {
            // Actualizar o crear nuevo registro de texto traducible
            if ($tip->wasChanged('title')) {
                $titleRecord = $this->registerTextForTranslation(
                    "tip.{$tip->id}.title",
                    $tip->title,
                    'tip_title'
                );
                $this->translateToAllLocales($titleRecord);
            }

            if ($tip->wasChanged('description')) {
                $descriptionRecord = $this->registerTextForTranslation(
                    "tip.{$tip->id}.description",
                    $tip->description,
                    'tip_description'
                );
                $this->translateToAllLocales($descriptionRecord);
            }

            Log::info('Tip translations updated', [
                'tip_id' => $tip->id,
            ]);
        }
    }

    /**
     * Handle the Tip "deleted" event.
     */
    public function deleted(Tip $tip): void
    {
        // Eliminar los registros de texto traducible asociados
        TranslatableText::where('key', "tip.{$tip->id}.title")->delete();
        TranslatableText::where('key', "tip.{$tip->id}.description")->delete();

        Log::info('Tip translations deleted', [
            'tip_id' => $tip->id,
        ]);
    }

    /**
     * Registrar un texto para traducción.
     */
    private function registerTextForTranslation(string $key, string $text, string $group): TranslatableText
    {
        return TranslatableText::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'source_text' => $text,
                'source_locale' => config('localization.default_locale', 'es'),
                'is_active' => true,
            ]
        );
    }

    /**
     * Traducir un texto a todos los idiomas soportados.
     */
    private function translateToAllLocales(TranslatableText $translatableText): void
    {
        $supportedLocales = config('localization.supported_locales', []);
        $defaultLocale = config('localization.default_locale', 'es');

        // Traducir a cada idioma que no sea el idioma por defecto
        foreach ($supportedLocales as $locale => $info) {
            if ($locale !== $defaultLocale) {
                try {
                    $this->translationService->translateDynamic($translatableText, $locale);
                    Log::debug('Translation created', [
                        'text_id' => $translatableText->id,
                        'target_locale' => $locale,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to translate text', [
                        'text_id' => $translatableText->id,
                        'target_locale' => $locale,
                        'error' => $e->getMessage(),
                    ]);
                    // Continuar con el siguiente idioma en lugar de fallar completamente
                }
            }
        }
    }
}
