# Funcionalidad de Bookmarks (Guardado) - Implementación

## Resumen de Cambios

Se ha implementado la funcionalidad completa de bookmarks (guardado) para los posts. Los usuarios pueden:
1. Hacer clic en el icono de bookmark para guardar un post
2. Ver todos sus posts guardados en la página `/saved`
3. Desmarcar un post guardado, que se eliminará de la página sin recargar

## Archivos Creados

### 1. BookmarkController.php
**Ubicación:** `app/Http/Controllers/BookmarkController.php`

Controlador que maneja el toggle (guardar/quitar guardado) de un post:
- Método `toggle()`: Alterna el estado de guardado de un tip
- Retorna JSON con el estado actual

## Archivos Modificados

### 1. routes/web.php
**Cambios:**
- Agregada ruta POST para bookmarks: `/tips/{tip}/bookmark`
- Protegida con middleware `auth`

### 2. TipController.php
**Cambios en todos los métodos que retornan tips:**
- `index()`: Agregado campo `is_bookmarked`
- `dashboard()`: Agregado campo `is_bookmarked`
- `following()`: Agregado campo `is_bookmarked`
- `saved()`: Agregado campo `is_bookmarked` (siempre true en esta vista)

### 3. tip-card.blade.php
**Cambios:**
- Agregado prop `isBookmarked` con valor por defecto `false`
- Icono de bookmark ahora muestra estado lleno cuando está guardado
- Agregado atributo `data-tip-id` al botón
- Agregado `onclick="toggleBookmark({{ $id }}, this)"` al botón
- Aplicado estilos condicionales basados en `$isBookmarked`

### 4. layouts/app.blade.php
**Cambios:**
- Agregada función JavaScript `toggleBookmark(tipId, element)`
- Maneja la petición AJAX al backend
- Actualiza el icono en tiempo real
- Si estás en la página `/saved` y desmarcas un post:
  - Anima la tarjeta con fade out
  - Elimina la tarjeta del DOM
  - Actualiza el contador de posts guardados
  - Muestra mensaje de "No saved posts yet" si se eliminan todos

### 5. Vistas actualizadas con prop `isBookmarked`
- `resources/views/dashboard.blade.php`
- `resources/views/following.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/saved.blade.php`

## Funcionalidades Implementadas

### 1. Guardar Post
- El usuario hace clic en el icono de bookmark vacío
- Se envía petición POST a `/tips/{id}/bookmark`
- El icono se llena y cambia a color primary
- El post se guarda en la tabla `bookmarks`

### 2. Quitar Guardado
- El usuario hace clic en el icono de bookmark lleno
- Se envía petición POST a `/tips/{id}/bookmark`
- El icono vuelve a estado vacío
- El registro se elimina de la tabla `bookmarks`

### 3. Comportamiento Especial en Página Saved
Cuando se desmarca un post en `/saved`:
- La tarjeta se anima con fade out y scale
- Se elimina del DOM después de 300ms
- Se actualiza el contador automáticamente
- Si no quedan posts, se muestra mensaje de estado vacío

### 4. Protección de Rutas
- Solo usuarios autenticados pueden guardar posts
- Si un usuario no autenticado intenta guardar, se redirige a login

## Estructura de Base de Datos

### Tabla: bookmarks
```sql
- id (bigint, primary key)
- user_id (foreign key a users)
- tip_id (foreign key a tips)
- created_at (timestamp)
- updated_at (timestamp)
- UNIQUE (user_id, tip_id) -- Un usuario no puede guardar el mismo tip dos veces
```

## Relaciones de Modelos

### User Model
```php
// Relación hasMany
public function bookmarks()

// Relación belongsToMany
public function bookmarkedTips()
```

### Tip Model
```php
// Relación hasMany
public function bookmarks()

// Método helper
public function isBookmarkedBy($user)
```

### Bookmark Model
```php
public function user()
public function tip()
```

## Flujo de Datos

1. **Vista renderiza** → Controlador envía `is_bookmarked` a la vista
2. **Usuario hace clic** → JavaScript llama a `toggleBookmark()`
3. **AJAX request** → Se envía POST a `/tips/{id}/bookmark`
4. **Controlador procesa** → BookmarkController verifica y alterna el estado
5. **Respuesta JSON** → `{ success: true, bookmarked: true/false }`
6. **JavaScript actualiza UI** → Cambia icono y posiblemente elimina tarjeta

## Testing Sugerido

1. Probar guardar un post desde dashboard
2. Verificar que aparece en la página saved
3. Probar desmarcar desde saved (debe desaparecer sin recargar)
4. Probar desmarcar desde dashboard (solo debe cambiar icono)
5. Verificar que el contador se actualiza correctamente
6. Probar con múltiples posts
7. Verificar estado vacío cuando se eliminan todos los posts guardados

## Notas Técnicas

- Se usa Material Symbols para iconos
- Animaciones CSS con transitions
- CSRF token incluido en todas las peticiones AJAX
- Compatible con modo oscuro
- Responsive en todos los tamaños de pantalla
