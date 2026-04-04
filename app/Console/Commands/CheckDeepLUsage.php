<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckDeepLUsage extends Command
{
    protected $signature = 'deepl:usage';
    protected $description = 'Check DeepL API usage and quota for current billing period';

    public function handle(): int
    {
        $apiKey = config('localization.deepl.api_key');
        $apiUrl = config('localization.deepl.api_url');

        if (!$apiKey) {
            $this->error('❌ DEEPL_API_KEY not configured in .env');
            return self::FAILURE;
        }

        // Determinar si es Free o Pro API
        $isFree = str_contains($apiUrl, 'api-free');
        $usageUrl = $isFree 
            ? 'https://api-free.deepl.com/v2/usage'
            : 'https://api.deepl.com/v2/usage';

        try {
            $response = Http::withHeaders([
                'Authorization' => "DeepL-Auth-Key {$apiKey}",
            ])->get($usageUrl);

            if ($response->failed()) {
                $this->error("❌ DeepL API Error: {$response->status()}");
                $this->error("Response: {$response->body()}");
                return self::FAILURE;
            }

            $data = $response->json();
            $this->displayUsage($data, $isFree);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    private function displayUsage(array $data, bool $isFree): void
    {
        $this->newLine();
        $this->info('═════════════════════════════════════════════════════');
        $this->info('           📊 DeepL API Usage Report');
        $this->info('═════════════════════════════════════════════════════');
        $this->newLine();

        // Información básica
        $characterCount = $data['character_count'] ?? 0;
        $characterLimit = $data['character_limit'] ?? 0;

        // Formato de números con separador de miles
        $formattedUsed = number_format($characterCount);
        $formattedLimit = number_format($characterLimit);

        // Calcular porcentaje de uso
        $percentage = $characterLimit > 0 ? round(($characterCount / $characterLimit) * 100, 2) : 0;

        $this->line("📝 <fg=cyan>Total Characters Used</> : <fg=green>{$formattedUsed}</>");
        $this->line("📈 <fg=cyan>Billing Period Limit</> : <fg=yellow>{$formattedLimit}</>");
        $this->newLine();

        // Barra de progreso visual
        $barLength = 50;
        $filledLength = (int) ($barLength * $percentage / 100);
        $bar = str_repeat('█', $filledLength) . str_repeat('░', $barLength - $filledLength);

        if ($percentage < 70) {
            $barColor = 'green';
            $percentColor = 'green';
        } elseif ($percentage < 90) {
            $barColor = 'yellow';
            $percentColor = 'yellow';
        } else {
            $barColor = 'red';
            $percentColor = 'red';
        }

        $this->line("<fg={$barColor}>{$bar}</> <fg={$percentColor}>{$percentage}%</>");
        $this->newLine();

        // Información de período
        if (isset($data['start_time']) && isset($data['end_time'])) {
            $startDate = \Carbon\Carbon::parse($data['start_time'])->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::parse($data['end_time'])->format('Y-m-d H:i:s');

            $this->line("📅 <fg=cyan>Billing Period</>");
            $this->line("   Start: <fg=white>{$startDate}</>");
            $this->line("   End:   <fg=white>{$endDate}</>");
            $this->newLine();
        }

        // Información por producto (si es Pro)
        if (isset($data['products']) && !$isFree) {
            $this->line("📦 <fg=cyan>Usage by Product</>");

            foreach ($data['products'] as $product) {
                $productType = $product['product_type'] ?? 'unknown';
                $keyCharCount = $product['api_key_character_count'] ?? 0;
                $totalCharCount = $product['character_count'] ?? 0;

                $formattedKeyCount = number_format($keyCharCount);
                $formattedTotalCount = number_format($totalCharCount);

                $this->line("   <fg=white>{$productType}:</>");
                $this->line("     • API Key: <fg=yellow>{$formattedKeyCount}</> chars");
                $this->line("     • Total:   <fg=yellow>{$formattedTotalCount}</> chars");
            }
            $this->newLine();
        }

        // Límites de clave API
        if ($isFree) {
            $this->line("💡 <fg=cyan>Plan Type:</> <fg=green>DeepL Free API</>");
        } else {
            $apiKeyLimit = $data['api_key_character_limit'] ?? 0;
            $this->line("💡 <fg=cyan>Plan Type:</> <fg=blue>DeepL Pro API</>");
            
            if ($apiKeyLimit > 0) {
                $formattedKeyLimit = number_format($apiKeyLimit);
                $this->line("   <fg=cyan>API Key Limit:</> <fg=yellow>{$formattedKeyLimit}</> chars");
            }
        }

        $this->newLine();

        // Recomendaciones
        if ($percentage >= 90) {
            $this->warn('⚠️  Warning: Using more than 90% of quota!');
        } elseif ($percentage >= 70) {
            $this->info('ℹ️  Info: Using 70%+ of quota');
        }

        $this->info('═════════════════════════════════════════════════════');
        $this->newLine();
    }
}
