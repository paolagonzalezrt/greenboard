# 🔌 Deshabilitar Temporalmente DeepL (Sin Gastar Caracteres)

## 🎯 3 Formas de Hacerlo

### Opción 1: Comando Artisan (Más Fácil)

```bash
# Deshabilitar traducciones
php artisan translations:toggle off

# O simplemente toggle (invierte el estado actual)
php artisan translations:toggle

# Habilitar de nuevo
php artisan translations:toggle on
```

**Salida:**

```
✅ Translations ENABLED
   New tips will be translated to EN and DE
   API calls will be made to DeepL
```

O cuando está deshabilitado:

```
⚠️  Translations DISABLED
   New tips will NOT be translated
   No API calls will be made to DeepL
   Useful for development without consuming quota
```

### Opción 2: Editar .env (Manual)

```env
# En tu .env, busca:
TRANSLATIONS_ENABLED=true

# Cambia a:
TRANSLATIONS_ENABLED=false

# Guarda el archivo
# Ejecuta: php artisan config:clear
```

### Opción 3: Usar Variable de Ambiente

```bash
# En terminal (Linux/Mac):
export TRANSLATIONS_ENABLED=false
php artisan serve

# En PowerShell (Windows):
$env:TRANSLATIONS_ENABLED = "false"
php artisan serve
```

## ✅ Cuando Está Deshabilitado

### Qué Funciona

- ✓ Puedes crear tips normalmente
- ✓ Las traducciones se guardan en BD (LocalizableText)
- ✓ NO hay llamadas a DeepL (sin gastar caracteres)
- ✓ Cambiar idiomas sigue funcionando
- ✓ Caché de traducciones se usa si existe

### Qué CAMBIA

- Tips nuevos aparecen en el idioma original
- Si cambias a EN/DE, ves el texto en español
- No hay traducción automática
- Es como si DeepL no existiera

### Ejemplo de Flujo Deshabilitado

```
1. Usuario crea: "Composting 101" (ES)
   ↓
2. Sistema guarda en tabla tips
   ↓
3. Observer detects creación
   ↓
4. Pero como TRANSLATIONS_ENABLED=false:
   → NO registra en TranslatableText
   → NO llama a DeepL
   → NO crea traducciones
   ↓
5. Resultado: Tip creado sin consumir API ✓
```

## 📊 Comparativa

| Estado       | Tipea Creada         | Traducciones   | Consumo API | BD           |
| ------------ | -------------------- | -------------- | ----------- | ------------ |
| **ENABLED**  | ✓ Traducida a EN, DE | ✓ Guardadas    | ✓ Sí        | Crece rápido |
| **DISABLED** | ✓ Solo en ES         | ✗ No guardadas | ✗ No        | Crece lento  |

## 🚀 Flujo de Desarrollo Recomendado

### 1️⃣ Durante Desarrollo

```bash
# Al iniciar tu sesión de desarrollo
php artisan translations:toggle off   # ⚠️ Deshabilitar
php artisan serve
```

### 2️⃣ Crear y Probar

```
- Crear tips sin preocuparte por consumo ✓
- Cambiar idiomas funciona (con traducciones cacheadas)
- Desarrollar features sin restricciones ✓
```

### 3️⃣ Antes de Producción

```bash
# Cuando esté todo listo
php artisan translations:toggle on    # ✅ Habilitar
php artisan tips:translate           # Traducir todo
php artisan serve
```

## 💡 Casos de Uso

### ✅ Usar `TRANSLATIONS_ENABLED=false`

- 🔨 Desarrollo activo (cambios frecuentes)
- 🧪 Testing de features nuevas
- 🐛 Debugging
- 📝 Crear contenido de prueba
- 💰 No tienes presupuesto API este mes

### ✅ Usar `TRANSLATIONS_ENABLED=true`

- 🚀 Producción
- 👥 Testing con usuarios reales
- 📊 QA antes de deploy
- 💹 Cuando presupuesto lo permite

## 🔄 Cambiar Entre Estados

```bash
# Ver estado actual
grep TRANSLATIONS_ENABLED .env

# Deshabilitar (sin gastar)
php artisan translations:toggle off

# Habilitar (gastar caracteres)
php artisan translations:toggle on

# Simplemente toggle (invierte)
php artisan translations:toggle

# Limpiar cache después
php artisan config:clear
```

## 📈 Controlar Consumo

```bash
# Ver consumo en tiempo real
php artisan deepl:usage

# Si está muy alto, deshabilitar
php artisan translations:toggle off

# Cuando baje, habilitar de nuevo
php artisan translations:toggle on
```

**Ejemplo:**

```
Si gastaste 400K/500K de cuota:
1. php artisan translations:toggle off  # Parar consumo
2. php artisan deepl:usage              # Monitorear
3. Esperar a próximo mes o...
4. php artisan translations:toggle on   # Continuar
```

## 🎯 Estado Actual del Sistema

```
TRANSLATIONS_ENABLED: true (ACTIVO)

Si quieres desarrollar sin gastar:
→ php artisan translations:toggle off
```

## ⚙️ Configuración Detallada

### En .env

```env
# DESHABILITADO (desarrollo)
TRANSLATIONS_ENABLED=false
TRANSLATION_SERVICE=deepl
DEEPL_API_KEY=***

# HABILITADO (producción)
TRANSLATIONS_ENABLED=true
TRANSLATION_SERVICE=deepl
DEEPL_API_KEY=***
```

### En config/localization.php

```php
'translations_enabled' => env('TRANSLATIONS_ENABLED', true),
```

Cuando `false`, usa `PassthroughProvider` que:

- ✓ Devuelve texto original
- ✗ NO traduce
- ✗ NO llama API
- ✓ Es instantáneo

## 🔐 Que Pasa en Segundo Plano

Cuando `TRANSLATIONS_ENABLED=false`:

```
TranslationService
  ├─ resolveProvider()
  └─ Checks: translations_enabled?
     ├─ NO → PassthroughProvider
     │       (devuelve texto sin cambios)
     │
     └─ SÍ → DeepLProvider
             (llama API DeepL)
```

## 📞 Soporte Rápido

```bash
# ¿Quiero deshabilitar temporalmente?
php artisan translations:toggle off

# ¿Quiero verificar estado?
grep TRANSLATIONS_ENABLED .env

# ¿Ver consumo?
php artisan deepl:usage

# ¿Volver a habilitar?
php artisan translations:toggle on
```

---

**Resumen:** Ejecuta `php artisan translations:toggle off` y ¡sin más gastos de API durante desarrollo!
