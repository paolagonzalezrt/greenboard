# Sistema de Localización - GreenBoard

## 📁 Estructura de Carpetas

```
├── app/
│   ├── Console/Commands/
│   │   ├── TranslateDynamicTexts.php      # Comando para traducir textos dinámicos
│   │   └── ClearTranslationCache.php      # Comando para limpiar cache
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── LocaleController.php       # Controlador de cambio de idioma
│   │   └── Middleware/
│   │       └── SetLocale.php              # Middleware de localización
│   ├── Jobs/
│   │   ├── TranslateTextJob.php           # Job para traducción individual
│   │   └── TranslateDynamicTextsJob.php   # Job para traducción masiva
│   ├── Models/
│   │   ├── TranslatableText.php           # Modelo de textos traducibles
│   │   ├── DynamicTranslation.php         # Modelo de traducciones
│   │   └── TranslationCache.php           # Modelo de cache de traducciones
│   ├── Providers/
│   │   └── LocalizationServiceProvider.php
│   ├── Services/
│   │   └── Translation/
│   │       ├── Contracts/
│   │       │   └── TranslationProviderInterface.php
│   │       ├── Exceptions/
│   │       │   └── TranslationException.php
│   │       ├── Providers/
│   │       │   ├── DeepLProvider.php      # Proveedor DeepL
│   │       │   └── GoogleTranslateProvider.php
│   │       ├── LocaleService.php          # Servicio de localización
│   │       └── TranslationService.php     # Servicio de traducción
│   └── helpers.php                        # Funciones helper
├── config/
│   └── localization.php                   # Configuración de localización
├── database/
│   ├── migrations/
│   │   └── 2025_01_15_000001_create_localization_tables.php
│   └── seeders/
│       └── TranslatableTextSeeder.php
├── lang/
│   ├── es/                                # Español
│   │   ├── auth.php
│   │   ├── buttons.php
│   │   ├── content.php
│   │   ├── messages.php
│   │   └── nav.php
│   ├── en/                                # Inglés
│   │   └── ...
│   └── de/                                # Alemán
│       └── ...
└── resources/views/
    ├── components/
    │   └── locale-selector.blade.php      # Componente dropdown
    └── partials/
        └── language-modal.blade.php       # Modal de selección de idioma
```

## 🚀 Instalación

### 1. Ejecutar migraciones
```bash
php artisan migrate
```

### 2. Regenerar autoload (para helpers)
```bash
composer dump-autoload
```

### 3. (Opcional) Poblar textos dinámicos de ejemplo
```bash
php artisan db:seed --class=TranslatableTextSeeder
```

### 4. Configurar variables de entorno
Agregar al archivo `.env`:

```env
# Idioma por defecto
APP_LOCALE=es
APP_FALLBACK_LOCALE=en

# Servicio de traducción: 'deepl' o 'google'
TRANSLATION_SERVICE=deepl

# API Key de DeepL (obtener en https://www.deepl.com/pro#developer)
DEEPL_API_KEY=tu-api-key-aqui

# O API Key de Google Cloud Translation
GOOGLE_TRANSLATE_API_KEY=tu-api-key-aqui

# Cache de traducciones
TRANSLATION_CACHE_ENABLED=true
TRANSLATION_CACHE_TTL=2592000  # 30 días en segundos

# Colas (opcional)
TRANSLATION_USE_QUEUE=false
TRANSLATION_QUEUE_NAME=translations
```

## 📖 Uso

### Textos Estáticos (archivos lang/)

En Blade:
```blade
{{-- Usar la función __() de Laravel --}}
<button>{{ __('buttons.save') }}</button>
<p>{{ __('messages.success.created') }}</p>
<a href="#">{{ __('nav.home') }}</a>

{{-- Con parámetros --}}
<p>{{ __('auth.throttle', ['seconds' => 60]) }}</p>
```

En PHP:
```php
// Obtener traducción
$text = __('nav.home');

// Cambiar idioma temporalmente
App::setLocale('de');
$germanText = __('nav.home'); // "Startseite"
```

### Textos Dinámicos (base de datos)

```php
// Usando el helper
$welcomeMessage = trans_dynamic('site.welcome_message');

// O el alias corto
$message = __d('notification.new_follower');

// Con idioma específico
$germanMessage = __d('site.about_us', 'de');
```

En Blade:
```blade
{{-- Usando helper --}}
<p>{{ trans_dynamic('site.welcome_message') }}</p>

{{-- O la directiva Blade --}}
<p>@trans_dynamic('site.footer_description')</p>
```

### Selector de Idioma

El selector se incluye automáticamente en el layout. Para incluirlo manualmente:

```blade
{{-- Componente dropdown (requiere Alpine.js) --}}
<x-locale-selector />

{{-- O incluir el modal --}}
@include('partials.language-modal')
```

### Cambio de Idioma Programático

```php
use App\Services\Translation\LocaleService;

$localeService = app(LocaleService::class);

// Cambiar idioma
$localeService->setLocale('de');

// Obtener idioma actual
$current = $localeService->getCurrentLocale(); // 'de'

// Verificar si es soportado
if ($localeService->isSupported('fr')) {
    // ...
}
```

### Traducción Automática

```php
use App\Services\Translation\TranslationService;

$translator = app(TranslationService::class);

// Traducir texto simple
$translated = $translator->translate('Hola mundo', 'en', 'es');
// Result: "Hello world"

// Traducir múltiples textos (más eficiente)
$texts = ['Hola', 'Adiós', 'Gracias'];
$translated = $translator->translateBatch($texts, 'de', 'es');
// Result: ['Hallo', 'Auf Wiedersehen', 'Danke']

// Traducción asíncrona (con colas)
$translator->translateAsync('Texto largo...', 'en');
```

## 🔧 Comandos Artisan

```bash
# Traducir todos los textos dinámicos pendientes
php artisan translate:dynamic

# Traducir a un idioma específico
php artisan translate:dynamic en

# Traducir usando colas
php artisan translate:dynamic --queue

# Limpiar cache de traducciones expirado
php artisan translate:clear-cache --expired

# Limpiar todo el cache de traducciones
php artisan translate:clear-cache
```

## 🗄️ Estructura de Base de Datos

### translatable_texts
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Primary key |
| key | string | Clave única (ej: 'site.welcome') |
| group | string | Grupo de categorización |
| source_text | text | Texto original |
| source_locale | string(5) | Idioma origen (es, en, de) |
| is_active | boolean | Si está activo |

### dynamic_translations
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Primary key |
| translatable_text_id | bigint | FK a translatable_texts |
| locale | string(5) | Código de idioma |
| translated_text | text | Texto traducido |
| is_auto_translated | boolean | Si fue automático |
| is_reviewed | boolean | Si fue revisado |
| translated_at | timestamp | Fecha de traducción |

### translation_cache
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Primary key |
| cache_key | string | Hash único |
| source_locale | string(5) | Idioma origen |
| target_locale | string(5) | Idioma destino |
| source_text | text | Texto original |
| translated_text | text | Texto traducido |
| service | string | deepl/google |
| expires_at | timestamp | Expiración |

## 🔄 Flujo de Traducción

```
┌─────────────────────────────────────────────────────────────┐
│                    TEXTOS ESTÁTICOS                         │
│                                                             │
│  Usuario → __('key') → Laravel → lang/{locale}/file.php    │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    TEXTOS DINÁMICOS                         │
│                                                             │
│  Usuario → trans_dynamic('key')                             │
│       ↓                                                     │
│  ¿Existe en BD?                                             │
│       ↓ Sí                    ↓ No                          │
│  ¿Tiene traducción?     Retornar fallback                   │
│       ↓ Sí       ↓ No                                       │
│  Retornar    ¿Cache?                                        │
│              ↓ Sí    ↓ No                                   │
│           Retornar  API (DeepL/Google)                      │
│                         ↓                                   │
│                   Guardar en cache                          │
│                         ↓                                   │
│                   Guardar en BD                             │
│                         ↓                                   │
│                   Retornar                                  │
└─────────────────────────────────────────────────────────────┘
```

## ⚡ Compatibilidad con Hosting Compartido

Este sistema está diseñado para funcionar en hosting compartido (Hostinger, etc.):

1. **Sin dependencias de Redis**: Usa cache de base de datos
2. **Sin dependencias de extensiones especiales**: Solo PHP básico + cURL
3. **Colas opcionales**: Funciona con `QUEUE_CONNECTION=database`
4. **Cache persistente**: Las traducciones se guardan en BD

### Configuración recomendada para hosting compartido:

```env
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
TRANSLATION_USE_QUEUE=false
```

## 🎯 Agregar Nuevos Idiomas

1. Crear carpeta en `lang/`:
```bash
mkdir lang/fr
```

2. Copiar archivos base:
```bash
cp lang/en/*.php lang/fr/
```

3. Traducir los archivos

4. Agregar a configuración (`config/localization.php`):
```php
'supported_locales' => [
    // ...
    'fr' => [
        'name' => 'French',
        'native' => 'Français',
        'flag' => '🇫🇷',
    ],
],
```

## 📝 Buenas Prácticas

1. **Usar claves descriptivas**: `nav.home` en lugar de `nav_1`
2. **Agrupar por contexto**: `buttons.save`, `buttons.cancel`
3. **Evitar textos hardcodeados**: Siempre usar `__()`
4. **Revisar traducciones automáticas**: Marcar `is_reviewed = true`
5. **Cachear agresivamente**: TTL de 30 días para traducciones
