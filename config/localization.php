<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Idiomas Soportados
    |--------------------------------------------------------------------------
    */
    'supported_locales' => [
        'es' => [
            'name' => 'Español',
            'native' => 'Español',
            'flag' => '🇪🇸',
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
        ],
        'de' => [
            'name' => 'German',
            'native' => 'Deutsch',
            'flag' => '🇩🇪',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Idioma por defecto
    |--------------------------------------------------------------------------
    */
    'default_locale' => env('APP_LOCALE', 'es'),

    /*
    |--------------------------------------------------------------------------
    | Idioma de fallback
    |--------------------------------------------------------------------------
    */
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Habilitar/Deshabilitar Traducciones
    |--------------------------------------------------------------------------
    | Si está en false, las traducciones se desactivan completamente.
    | Útil para desarrollo sin gastar caracteres de API.
    */
    'translations_enabled' => env('TRANSLATIONS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Traducción Automática
    |--------------------------------------------------------------------------
    */
    'translation_service' => env('TRANSLATION_SERVICE', 'deepl'), // 'deepl' o 'google'

    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
        'api_url' => env('DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate'),
    ],

    'google' => [
        'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
        'api_url' => 'https://translation.googleapis.com/language/translate/v2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('TRANSLATION_CACHE_ENABLED', true),
        'ttl' => env('TRANSLATION_CACHE_TTL', 86400 * 30), // 30 días
        'prefix' => 'translation_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Usar colas para traducciones
    |--------------------------------------------------------------------------
    */
    'use_queue' => env('TRANSLATION_USE_QUEUE', false),
    'queue_name' => env('TRANSLATION_QUEUE_NAME', 'translations'),
];
