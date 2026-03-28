# Sistema de Tema Oscuro e Idioma

## 🎨 Características Implementadas

### ✅ Modo Oscuro (Dark Mode)

- Toggle persistente usando `localStorage`
- Iconos dinámicos que cambian según el tema activo
- Transiciones suaves entre temas
- Se mantiene al navegar entre páginas

### ✅ Cambio de Idioma

- Toggle entre Inglés (EN) y Español (ES)
- Persistencia con `localStorage`
- Feedback visual al cambiar idioma
- Archivos de traducción incluidos

### ✅ Interfaz Optimizada

- Botón "Explore" eliminado del navbar
- Botones de tema e idioma agregados
- Código no repetitivo
- Logo ahora es clickeable (redirige al home)
- Botón de logout mejorado con icono

## 📁 Archivos Modificados

### 1. `resources/views/layouts/app.blade.php`

- Script de inicialización de tema e idioma
- Actualización automática de iconos
- Gestión de `localStorage`

### 2. `resources/views/partials/nav.blade.php`

- Eliminado link "Explore"
- Añadidos botones de tema e idioma
- Mejorada UI de logout
- Logo ahora es clickeable

### 3. `resources/views/components/tip-card.blade.php`

- Actualizado con nuevo diseño
- Colores específicos por categoría
- Imagen cuadrada (aspect-square)
- Descripción visible

### 4. Archivos Nuevos Creados

- `resources/js/theme-manager.js` - Clase para gestionar tema e idioma (opcional)
- `resources/lang/en.php` - Traducciones en inglés
- `resources/lang/es.php` - Traducciones en español

## 🚀 Cómo Usar

### Modo Oscuro

El modo oscuro se activa/desactiva automáticamente al hacer clic en el botón 🌙/☀️.
El tema se guarda en `localStorage` y persiste entre sesiones.

### Cambio de Idioma

Haz clic en el botón 🌐 para cambiar entre inglés y español.
El idioma se guarda en `localStorage`.

### Para Usar Traducciones en Blade

```blade
<!-- Opción 1: Helper __ de Laravel -->
<h1>{{ __('welcome.hero_title') }}</h1>

<!-- Opción 2: Función trans() -->
<p>{{ trans('welcome.hero_description') }}</p>
```

## 🔧 Configuración Avanzada

### Agregar Más Idiomas

1. Crear nuevo archivo en `resources/lang/`:

```bash
resources/lang/fr.php  # Para francés
```

2. Actualizar el script en `app.blade.php`:

```javascript
const availableLanguages = ["en", "es", "fr"];
```

### Cambiar Idioma Mediante URL

Descomenta esta línea en `app.blade.php`:

```javascript
// window.location.href = `${window.location.pathname}?lang=${newLang}`;
```

Luego asegúrate de que el middleware `SetLocale` esté registrado en `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... otros middleware
        \App\Http\Middleware\SetLocale::class,
    ],
];
```

## 📝 Notas Importantes

1. **localStorage**: Los datos se guardan en el navegador del usuario
2. **Persistencia**: El tema e idioma persisten al recargar o cambiar de página
3. **Fallback**: Si no hay preferencia guardada, usa 'light' theme y 'en' language
4. **Iconos**: Usa Material Symbols de Google Fonts

## 🎯 Próximos Pasos (Opcional)

- [ ] Integrar con sistema de traducción de Laravel
- [ ] Añadir más idiomas
- [ ] Crear selector de idioma con dropdown
- [ ] Guardar preferencias en el perfil de usuario (DB)
- [ ] Añadir animaciones de transición entre temas

## 💡 Tips

- El tema oscuro mejora la experiencia en ambientes con poca luz
- Las traducciones están listas para ser usadas con `__()`
- Puedes personalizar los colores en `tailwind.config`
