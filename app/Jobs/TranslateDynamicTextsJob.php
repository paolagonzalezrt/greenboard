<?php

namespace App\Jobs;

use App\Services\Translation\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateDynamicTextsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 300; // 5 minutos

    public function __construct(
        private string $targetLocale
    ) {
    }

    public function handle(TranslationService $translationService): void
    {
        try {
            $count = $translationService->translateAllDynamicTexts($this->targetLocale);

            Log::info('Dynamic texts translation job completed', [
                'locale' => $this->targetLocale,
                'translated_count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('Dynamic texts translation job failed', [
                'error' => $e->getMessage(),
                'locale' => $this->targetLocale,
            ]);

            throw $e;
        }
    }
}
