<?php

/**
 * Get DeepL API usage information.
 * 
 * @return array|null
 */
function deepl_usage()
{
    try {
        return app(\App\Services\Translation\TranslationService::class)->getUsage();
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Get DeepL API usage percentage.
 * 
 * @return float|null
 */
function deepl_usage_percentage(): ?float
{
    $usage = deepl_usage();
    if (!$usage || $usage['character_limit'] == 0) {
        return null;
    }
    return round(($usage['character_count'] / $usage['character_limit']) * 100, 2);
}

/**
 * Get formatted DeepL usage display.
 * 
 * @return string
 */
function deepl_usage_display(): string
{
    $usage = deepl_usage();
    if (!$usage) {
        return 'N/A';
    }
    
    $used = number_format($usage['character_count']);
    $limit = number_format($usage['character_limit']);
    $percentage = deepl_usage_percentage();
    
    return "{$used} / {$limit} ({$percentage}%)";
}

/**
 * Check if DeepL quota is running low.
 * 
 * @param float $threshold Default 70%
 * @return bool
 */
function deepl_usage_warning(float $threshold = 70): bool
{
    $percentage = deepl_usage_percentage();
    return $percentage !== null && $percentage >= $threshold;
}
