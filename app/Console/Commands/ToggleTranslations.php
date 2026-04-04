<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ToggleTranslations extends Command
{
    protected $signature = 'translations:toggle {status?}';
    protected $description = 'Enable or disable automatic translations (for development)';

    public function handle(): int
    {
        $status = $this->argument('status');
        $currentStatus = config('localization.translations_enabled');

        // Determinar nuevo estado
        if ($status === 'on' || $status === '1' || $status === 'true') {
            $newStatus = true;
        } elseif ($status === 'off' || $status === '0' || $status === 'false') {
            $newStatus = false;
        } else {
            // Toggle si no se especifica
            $newStatus = !$currentStatus;
        }

        // Actualizar .env
        $this->updateEnvFile($newStatus);

        // Limpiar cache
        $this->call('config:clear');

        // Mostrar resultado
        $this->newLine();
        if ($newStatus) {
            $this->info('✅ Translations ENABLED');
            $this->line('   New tips will be translated to EN and DE');
            $this->line('   API calls will be made to DeepL');
        } else {
            $this->warn('⚠️  Translations DISABLED');
            $this->line('   New tips will NOT be translated');
            $this->line('   No API calls will be made to DeepL');
            $this->line('   Useful for development without consuming quota');
        }
        $this->newLine();

        return self::SUCCESS;
    }

    private function updateEnvFile(bool $enabled): void
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $value = $enabled ? 'true' : 'false';

        // Si la línea ya existe, reemplazarla
        if (str_contains($envContent, 'TRANSLATIONS_ENABLED=')) {
            $envContent = preg_replace(
                '/TRANSLATIONS_ENABLED=.*/i',
                "TRANSLATIONS_ENABLED={$value}",
                $envContent
            );
        } else {
            // Si no existe, agregarla después de TRANSLATION_SERVICE
            $envContent = str_replace(
                'TRANSLATION_SERVICE=',
                "TRANSLATIONS_ENABLED={$value}\nTRANSLATION_SERVICE=",
                $envContent
            );
        }

        file_put_contents($envPath, $envContent);
    }
}
