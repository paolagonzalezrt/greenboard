# 📊 Monitoreo de Consumo API DeepL

## ✅ Verificar Consumo Actual

### Opción 1: Comando Artisan (Recomendado)

```bash
php artisan deepl:usage
```

**Salida:**

```
═════════════════════════════════════════════════════
           📊 DeepL API Usage Report
═════════════════════════════════════════════════════

📝 Total Characters Used : 3,714
📈 Billing Period Limit : 500,000

░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 0.74%

💡 Plan Type: DeepL Free API

═════════════════════════════════════════════════════
```

### Opción 2: Desde PHP

```php
// En una clase o controlador
use App\Services\Translation\TranslationService;

$translationService = app(TranslationService::class);
$usage = $translationService->getUsage();

if ($usage) {
    echo "Characters used: " . $usage['character_count'];
    echo "Limit: " . $usage['character_limit'];
    echo "Percentage: " . ($usage['character_count'] / $usage['character_limit'] * 100) . "%";
}
```

## 📈 Información que se Muestra

### Free API

| Campo                | Significado                             |
| -------------------- | --------------------------------------- |
| Characters Used      | Caracteres traducidos en período actual |
| Billing Period Limit | Límite de caracteres para este período  |
| Plan Type            | DeepL Free API (500K chars/mes)         |

### Pro API

| Campo           | Significado                                |
| --------------- | ------------------------------------------ |
| Characters Used | Total incluyendo traducciones + Write (AI) |
| API Key Limit   | Límite por API key (si está configurado)   |
| Start Time      | Inicio del período de facturación          |
| End Time        | Fin del período de facturación             |
| Products        | Detalles por tipo (translate/write)        |

## ⚠️ Alertas

El comando muestra advertencias si:

- **≥ 90% usado**: ⚠️ Advertencia roja
- **70-89% usado**: ℹ️ Información amarilla
- **< 70% usado**: ✓ Normal verde

## 🔄 Cómo se Calcula el Uso

```
1 carácter = 1 unicode code point
"Hola" = 4 caracteres
"Δ" = 1 carácter
"深" = 1 carácter

Por cada traducción:
- Se cuentan caracteres del TEXTO ORIGINAL
- Ejemplo: "Composting 101" (14 chars) → 14 chars consumidos
```

## 📊 Ejemplo de Consumo en tu Proyecto

### Crear 3 Tips

```
Tip 1: "Composting 101" (14 chars) × 2 idiomas = 28 chars
Tip 2: "Zero Waste Guide" (16 chars) × 2 idiomas = 32 chars
Tip 3: "Eco Fashion Tips" (16 chars) × 2 idiomas = 32 chars
─────────────────────────────────────────
Total enviado: 92 caracteres a DeepL
```

### Observación Actual

```
Characters Used: 3,714
Limit: 500,000
Uso: 0.74%

Estimación a fin de mes:
Si sigues al mismo ritmo...
- Crearías ~7,000 caracteres adicionales
- Final: ~10,700 caracteres
- Aún tienes 99.99% disponible ✓
```

## 🚀 Automatizar Monitoreo

### Opción 1: Programar Chequeo Diario

```bash
# En config/schedule.php
$schedule->command('deepl:usage')
    ->dailyAt('09:00')
    ->sendToEmail('admin@greenboard.io');
```

### Opción 2: Endpoint para Dashboard

```php
// En AdminController
public function showDeepLStatus(TranslationService $translator)
{
    $usage = $translator->getUsage();
    return view('admin.deepl-status', compact('usage'));
}

// En blade: {{ $usage['character_count'] }}/{{ $usage['character_limit'] }}
```

## 💡 Recomendaciones

### ✅ Plan Free (500K chars/mes)

- Excelente para proyectos pequeños/medianos
- Con 3,000 tips × 50 caracteres = 150K caracteres
- Sobra mucho espacio

### 🔄 Cuando Considerar Pro

- Si pasas 400K caracteres/mes
- Si necesitas API key con límite personalizado
- Si necesitas "DeepL Write" (corrector AI)

### 📉 Cómo Optimizar Consumo

```
1. ❌ NO traducir en cada request
   ✅ SÍ: Traducir una vez, guardar en BD

2. ❌ NO traducir comentarios automáticamente
   ✅ SÍ: Solo traducir si el usuario lo solicita

3. ❌ NO re-traducir contenido existente
   ✅ SÍ: Verificar si ya existe en DynamicTranslation

4. ❌ NO traducir a idiomas no soportados
   ✅ SÍ: Solo a EN y DE
```

## 🎯 Tu Consumo Actual

```
Status: ✅ Excelente
Consumo: 3,714 / 500,000 caracteres (0.74%)
Período: Mes actual
Pronóstico: Bajo
Acción: Ninguna necesaria
```

---

**Nota**: Los datos se actualizan en tiempo real desde DeepL. Ejecuta el comando con frecuencia para monitorear.
