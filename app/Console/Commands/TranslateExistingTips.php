<?php

namespace App\Console\Commands;

use App\Models\Tip;
use App\Models\TranslatableText;
use App\Services\Translation\TranslationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TranslateExistingTips extends Command
{
    protected $signature = 'tips:translate';
    protected $description = 'Translate all existing tips to supported locales using DeepL';

    public function __construct(
        private TranslationService $translationService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting translation of existing tips...');

        if (!$this->translationService->isProviderConfigured()) {
            $this->error('Translation provider is not configured. Please set DEEPL_API_KEY in .env');
            return self::FAILURE;
        }

        $this->info("Using translation provider: {$this->translationService->getProviderName()}");

        $supportedLocales = config('localization.supported_locales', []);
        $defaultLocale = config('localization.default_locale', 'es');

        $tips = Tip::all();
        $totalTips = $tips->count();
        $this->info("Found $totalTips tips to translate");

        $progressBar = $this->output->createProgressBar($totalTips);
        $progressBar->start();

        $totalTranslations = 0;

        foreach ($tips as $tip) {
            // Registrar título
            $titleRecord = TranslatableText::updateOrCreate(
                ['key' => "tip.{$tip->id}.title"],
                [
                    'group' => 'tip_title',
                    'source_text' => $tip->title,
                    'source_locale' => $defaultLocale,
                    'is_active' => true,
                ]
            );

            // Registrar descripción
            $descriptionRecord = TranslatableText::updateOrCreate(
                ['key' => "tip.{$tip->id}.description"],
                [
                    'group' => 'tip_description',
                    'source_text' => $tip->description,
                    'source_locale' => $defaultLocale,
                    'is_active' => true,
                ]
            );

            // Traducir a cada idioma
            foreach ($supportedLocales as $locale => $info) {
                if ($locale !== $defaultLocale) {
                    try {
                        $this->translationService->translateDynamic($titleRecord, $locale);
                        $this->translationService->translateDynamic($descriptionRecord, $locale);
                        $totalTranslations += 2;
                    } catch (\Exception $e) {
                        $this->warn("\nFailed to translate tip {$tip->id} to {$locale}: {$e->getMessage()}");
                        Log::error('Failed to translate tip', [
                            'tip_id' => $tip->id,
                            'locale' => $locale,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("✓ Translation complete!");
        $this->info("Total translations created: $totalTranslations");

        return self::SUCCESS;
    }
}
