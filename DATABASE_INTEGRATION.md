# Integración de Base de Datos - GreenBoard

## Resumen de Cambios

Se ha implementado la integración completa de la base de datos en todas las páginas del proyecto GreenBoard. Ahora todas las vistas (welcome, dashboard, following, saved) utilizan datos reales de la base de datos en lugar de datos estáticos.

## Archivos Creados

### 1. Migración: `create_follows_table.php`

**Ubicación:** `database/migrations/2026_03_29_225000_create_follows_table.php`

Crea la tabla `follows` que maneja las relaciones de seguimiento entre usuarios:

- `follower_id`: Usuario que sigue
- `following_id`: Usuario seguido
- Restricción única para evitar duplicados

### 2. Seeder: `FollowSeeder.php`

**Ubicación:** `database/seeders/FollowSeeder.php`

Genera relaciones de seguimiento aleatorias entre usuarios para datos de prueba:

- Cada usuario sigue entre 1 y 3 usuarios aleatorios
- Evita que un usuario se siga a sí mismo

### 3. Seeder: `BookmarkSeeder.php`

**Ubicación:** `database/seeders/BookmarkSeeder.php`

Genera bookmarks aleatorios para usuarios:

- Cada usuario guarda entre 1 y 4 tips aleatorios
- Simula fechas de guardado variadas

## Archivos Modificados

### 1. Modelo: `User.php`

**Cambios:**

- ✅ Agregadas relaciones `following()` - Usuarios que sigue
- ✅ Agregadas relaciones `followers()` - Seguidores del usuario
- ✅ Método `isFollowing($userId)` - Verificar si sigue a un usuario
- ✅ Método `isFollowedBy($userId)` - Verificar si es seguido por un usuario

### 2. Controlador: `TipController.php`

**Cambios:**

- ✅ Método `following()` - Muestra tips de usuarios seguidos
    - Obtiene los IDs de usuarios seguidos
    - Filtra tips por esos usuarios
    - Retorna array vacío si no sigue a nadie
- ✅ Método `saved()` - Muestra tips guardados
    - Obtiene tips guardados mediante la relación `bookmarkedTips`
    - Ordena por fecha de guardado

### 3. Rutas: `web.php`

**Cambios:**

- ✅ Ruta `/following` ahora usa `TipController::following()`
- ✅ Ruta `/saved` ahora usa `TipController::saved()`
- ✅ Eliminados arrays estáticos de datos

### 4. Vistas: `following.blade.php` y `saved.blade.php`

**Cambios:**

- ✅ Agregado atributo `published_at` en componente `tip-card`
- ✅ Mantiene mensajes de estado vacío cuando no hay datos

### 5. DatabaseSeeder: `DatabaseSeeder.php`

**Cambios:**

- ✅ Agregado `FollowSeeder::class`
- ✅ Agregado `BookmarkSeeder::class`

## Flujo de Datos

### Página Welcome (/)

```
TipController::index()
  → Obtiene todos los tips
  → Incluye: user, likes_count, comments_count
  → Ordena por fecha de creación (más recientes primero)
```

### Página Dashboard (/dashboard)

```
TipController::dashboard()
  → Obtiene todos los tips
  → Incluye: user, likes_count, comments_count
  → Ordena por fecha de creación (más recientes primero)
```

### Página Following (/following)

```
TipController::following()
  → Obtiene IDs de usuarios seguidos por el usuario actual
  → Filtra tips de esos usuarios
  → Retorna array vacío si no sigue a nadie
  → Ordena por fecha de creación
```

### Página Saved (/saved)

```
TipController::saved()
  → Obtiene tips guardados del usuario actual
  → Usa relación bookmarkedTips
  → Ordena por fecha de guardado
```

## Comandos para Actualizar la Base de Datos

```bash
# 1. Ejecutar la nueva migración
php artisan migrate

# 2. Sembrar los datos (esto ejecutará TipSeeder, FollowSeeder y BookmarkSeeder)
php artisan db:seed

# O si quieres reiniciar todo desde cero:
php artisan migrate:fresh --seed
```

## Consistencia de Datos

Todas las páginas ahora muestran:

- ✅ Datos reales de la base de datos
- ✅ Contadores de likes y comentarios consistentes
- ✅ Información del usuario (nombre, avatar)
- ✅ Fechas de publicación formateadas (e.g., "hace 2 días")
- ✅ Categorías consistentes
- ✅ Imágenes asociadas a los tips

## Manejo de Estados Vacíos

Cada página maneja correctamente cuando no hay datos:

- **Following:** Muestra mensaje "No posts yet" con botón para explorar
- **Saved:** Muestra mensaje "No saved posts yet" con botón para explorar

## Próximos Pasos Sugeridos

1. **Implementar acciones de like/bookmark:**
    - Crear rutas POST para dar like
    - Crear rutas POST para guardar/eliminar bookmarks

2. **Implementar follow/unfollow:**
    - Crear rutas POST para seguir/dejar de seguir usuarios
    - Agregar botones en las tarjetas de usuario

3. **Paginación:**
    - Implementar paginación en lugar de "Load More"
    - Usar `paginate()` en lugar de `get()` en los controladores

4. **Búsqueda:**
    - Implementar funcionalidad de búsqueda en las vistas
    - Filtrar por categorías

5. **Optimización:**
    - Implementar caché para consultas frecuentes
    - Eager loading adicional según necesidad

## Notas Importantes

- Todos los datos ahora provienen de la base de datos
- Las relaciones entre modelos están correctamente configuradas
- Los seeders generan datos de prueba realistas
- La aplicación maneja correctamente usuarios sin seguimientos o sin bookmarks
