# ✅ Guía de Configuración del Sistema de Traducción

## 🔧 Problema Encontrado y Arreglado

### ❌ Problema

El campo `preferred_locale` no estaba incluido en el array `$fillable` del modelo `User.php`, lo que causaba que Laravel ignorara este campo por protección contra asignación masiva.

### ✅ Solución Aplicada

Se agregó `'preferred_locale'` al array `$fillable` en:

- **Archivo**: `app/Models/User.php`

Ahora el usuario puede guardar su idioma preferido en la base de datos.

---

## 📋 Requisitos para Funcionamiento Completo

### 1. Base de Datos

Las migraciones ya están creadas. Ejecuta:

```bash
php artisan migrate
```

### 2. Configuración del .env

Copia `env.example` a `.env` y configura lo siguiente:

```env
# Idiima por defecto
APP_LOCALE=es
APP_FALLBACK_LOCALE=en

# ========== TRADUCCIÓN AUTOMÁTICA ==========
# Servicio: 'deepl' o 'google'
TRANSLATION_SERVICE=deepl

# DeepL Configuration
# Obtén tu API key en: https://www.deepl.com/pro#developer
# (Nivel gratuito disponible con 500K caracteres/mes)
DEEPL_API_KEY=tu-api-key-aqui
DEEPL_API_URL=https://api-free.deepl.com/v2/translate

# Google Cloud Translation API (alternativa)
# Habilita en: https://console.cloud.google.com/apis/library/translate.googleapis.com
GOOGLE_TRANSLATE_API_KEY=

# Cache de Traducciones
TRANSLATION_CACHE_ENABLED=true
TRANSLATION_CACHE_TTL=2592000  # 30 días en segundos

# Colas para traducción en background (opcional)
TRANSLATION_USE_QUEUE=false
TRANSLATION_QUEUE_NAME=translations
```

---

## 🚀 Uso Del Sistema de Traducción

### 1. Cambiar Idioma (ESTÁTICO - archivos lang/)

En las vistas:

```blade
{{ __('login.email') }}
{{ __('buttons.save') }}
```

### 2. Traducir Textos Dinámicos (BASE DE DATOS)

En las vistas:

```blade
<p>{{ trans_dynamic('site.welcome_message') }}</p>
<!-- O alias corto -->
<p>{{ __d('notification.new_follower') }}</p>
```

En PHP:

```php
$message = trans_dynamic('site.welcome_message');
$deMessage = trans_dynamic('site.welcome_message', 'de');
```

### 3. Cambiar Idioma Programáticamente

```php
use App\Services\Translation\LocaleService;

$localeService = app(LocaleService::class);
$localeService->setLocale('de'); // Cambia a Alemán

// Se guarda automáticamente en:
// - Sesión
// - Base de datos (si el usuario está autenticado)
```

---

## 📝 Comandos Artisan Disponibles

### Traducir Textos Dinámicos

```bash
# Traducir a un idioma específico
php artisan translate:dynamic es

# Traducir a todos los idiomas soportados
php artisan translate:dynamic

# Usar colas (background)
php artisan translate:dynamic es --queue
```

### Limpiar Cache de Traducciones

```bash
php artisan translate:cache:clear
```

---

## 🧪 Checklist de Verificación

- [ ] `.env` configurado con API key de traducción
- [ ] `php artisan migrate` ejecutado
- [ ] Modelo User tiene `preferred_locale` en `$fillable`
- [ ] Cache guardando/recuperando traducciones
- [ ] Cambio de idioma funciona desde navbar/UI
- [ ] Traducciones se guardan en base de datos
- [ ] Textos dinámicos traducen correctamente

---

## 🔍 Solución de Problemas

### "Translation provider is not configured"

- Verifica que tienes configurada una API key en `.env`
- Ejecuta: `TRANSLATION_SERVICE=deepl` (o google)

### Las traducciones no se guardan

- Asegúrate que correr `php artisan migrate`
- Verifica que el usuario está autenticado
- Revisa logs: `storage/logs/laravel.log`

### Cambio de idioma no persiste

- Verifica que `SESSION_DRIVER=database` en `.env`
- Ejecuta migraciones de sesión: `php artisan session:table`

---

## 📚 Arquitectura del Sistema

```
Textos Estáticos (lang/es/, lang/en/, lang/de/)
    ↓
helper: __('key.name')
    ↓
    Laravel Translation

Textos Dinámicos (BD)
    ↓
helper: trans_dynamic('key') o __d('key')
    ↓
    TranslatableText Model +
    DynamicTranslation Model +
    TranslationService
    ↓
    DeepL API / Google Translate API
    ↓
    TranslationCache (Cache + BD)
    ↓
    Usuario ve traducción
```

---

## ✨ Características Implementadas

✅ Soporte para ES, EN, DE  
✅ Cambio de idioma persistente (sesión + BD)  
✅ Traducción automática con DeepL/Google  
✅ Cache de traducciones (memoria + BD)  
✅ Traducciones en background (con colas)  
✅ Directivas Bladefiles (`@trans_dynamic()`)  
✅ Helpers PHP (`trans_dynamic()`, `__d()`)
