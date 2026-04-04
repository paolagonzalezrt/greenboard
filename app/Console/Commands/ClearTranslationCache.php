<?php

namespace App\Console\Commands;

use App\Models\TranslationCache;
use Illuminate\Console\Command;

class ClearTranslationCache extends Command
{
    protected $signature = 'translate:clear-cache 
                            {--expired : Only clear expired cache entries}';

    protected $description = 'Clear the translation cache';

    public function handle(): int
    {
        $onlyExpired = $this->option('expired');

        if ($onlyExpired) {
            $count = TranslationCache::clearExpired();
            $this->info("Cleared {$count} expired translation cache entries.");
        } else {
            $count = TranslationCache::count();
            TranslationCache::truncate();
            $this->info("Cleared all {$count} translation cache entries.");
        }

        return Command::SUCCESS;
    }
}
