<?php

namespace App\Jobs;

use App\Services\Translation\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TranslateTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private string $text,
        private string $targetLocale,
        private ?string $sourceLocale = null
    ) {
    }

    public function handle(TranslationService $translationService): void
    {
        try {
            $translationService->translate(
                $this->text,
                $this->targetLocale,
                $this->sourceLocale
            );

            Log::info('Translation job completed', [
                'target' => $this->targetLocale,
                'text_length' => strlen($this->text),
            ]);
        } catch (\Exception $e) {
            Log::error('Translation job failed', [
                'error' => $e->getMessage(),
                'target' => $this->targetLocale,
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Translation job permanently failed', [
            'error' => $exception->getMessage(),
            'target' => $this->targetLocale,
            'text' => substr($this->text, 0, 100),
        ]);
    }
}
