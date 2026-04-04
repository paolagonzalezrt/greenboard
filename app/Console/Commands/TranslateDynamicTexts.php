<?php

namespace App\Console\Commands;

use App\Jobs\TranslateDynamicTextsJob;
use App\Services\Translation\TranslationService;
use Illuminate\Console\Command;

class TranslateDynamicTexts extends Command
{
    protected $signature = 'translate:dynamic 
                            {locale? : The target locale (es, en, de). If not provided, translates to all locales}
                            {--queue : Process translations in background queue}';

    protected $description = 'Translate all pending dynamic texts to the specified locale';

    public function handle(TranslationService $translationService): int
    {
        $locale = $this->argument('locale');
        $useQueue = $this->option('queue');
        $supportedLocales = config('localization.supported_locales', []);
        $locales = $locale ? [$locale] : array_keys($supportedLocales);

        if ($locale && !isset($supportedLocales[$locale])) {
            $this->error("Locale '{$locale}' is not supported.");
            $this->info('Supported locales: ' . implode(', ', array_keys($supportedLocales)));
            return Command::FAILURE;
        }

        if (!$translationService->isProviderConfigured()) {
            $this->error('Translation provider is not configured. Please check your API keys.');
            return Command::FAILURE;
        }

        $this->info("Using translation provider: {$translationService->getProviderName()}");

        foreach ($locales as $targetLocale) {
            if ($useQueue) {
                TranslateDynamicTextsJob::dispatch($targetLocale);
                $this->info("Queued translation job for locale: {$targetLocale}");
            } else {
                $this->info("Translating to {$targetLocale}...");
                $count = $translationService->translateAllDynamicTexts($targetLocale);
                $this->info("Translated {$count} texts to {$targetLocale}");
            }
        }

        $this->info('Done!');
        return Command::SUCCESS;
    }
}
