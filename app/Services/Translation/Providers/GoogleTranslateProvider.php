<?php

namespace App\Services\Translation\Providers;

use App\Services\Translation\Contracts\TranslationProviderInterface;
use App\Services\Translation\Exceptions\TranslationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTranslateProvider implements TranslationProviderInterface
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('localization.google.api_key', '');
        $this->apiUrl = config('localization.google.api_url', 'https://translation.googleapis.com/language/translate/v2');
    }

    public function translate(string $text, string $targetLocale, ?string $sourceLocale = null): string
    {
        if (!$this->isConfigured()) {
            throw new TranslationException(
                'Google Translate API key not configured',
                $this->getProviderName()
            );
        }

        try {
            $params = [
                'key' => $this->apiKey,
                'q' => $text,
                'target' => $targetLocale,
                'format' => 'text',
            ];

            if ($sourceLocale) {
                $params['source'] = $sourceLocale;
            }

            $response = Http::post($this->apiUrl, $params);

            if ($response->failed()) {
                throw new TranslationException(
                    "Google Translate API error: {$response->status()}",
                    $this->getProviderName(),
                    ['response' => $response->body()]
                );
            }

            $data = $response->json();

            return $data['data']['translations'][0]['translatedText'] ?? $text;
        } catch (TranslationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Google Translate error', [
                'message' => $e->getMessage(),
                'text' => substr($text, 0, 100),
                'target' => $targetLocale,
            ]);

            throw new TranslationException(
                "Translation failed: {$e->getMessage()}",
                $this->getProviderName(),
                null,
                0,
                $e
            );
        }
    }

    public function translateBatch(array $texts, string $targetLocale, ?string $sourceLocale = null): array
    {
        if (!$this->isConfigured()) {
            throw new TranslationException(
                'Google Translate API key not configured',
                $this->getProviderName()
            );
        }

        try {
            $params = [
                'key' => $this->apiKey,
                'q' => $texts,
                'target' => $targetLocale,
                'format' => 'text',
            ];

            if ($sourceLocale) {
                $params['source'] = $sourceLocale;
            }

            $response = Http::post($this->apiUrl, $params);

            if ($response->failed()) {
                throw new TranslationException(
                    "Google Translate API error: {$response->status()}",
                    $this->getProviderName()
                );
            }

            $data = $response->json();

            return array_map(
                fn($item) => $item['translatedText'],
                $data['data']['translations'] ?? []
            );
        } catch (TranslationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new TranslationException(
                "Batch translation failed: {$e->getMessage()}",
                $this->getProviderName(),
                null,
                0,
                $e
            );
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function getProviderName(): string
    {
        return 'google';
    }
}
