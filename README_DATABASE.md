# 🎉 GreenBoard - Sistema Completo de Base de Datos

## ✅ ¿Qué se ha implementado?

### 📊 Base de Datos Completa y Escalable

1. **Tablas Principales**:
    - ✅ `users` - Usuarios del sistema
    - ✅ `tips` - Publicaciones de consejos ecológicos
    - ✅ `likes` - Likes a los tips
    - ✅ `comments` - Comentarios en los tips
    - ✅ `bookmarks` - Tips guardados por usuarios
    - ✅ `reports` - Reportes de contenido inapropiado

2. **Relaciones Implementadas**:
    - ✅ Un usuario puede tener muchos tips
    - ✅ Un tip pertenece a un usuario
    - ✅ Un tip puede tener muchos likes, comments, bookmarks y reports
    - ✅ Un usuario puede dar like, comentar, guardar y reportar tips
    - ✅ Relaciones bidireccionales completas

3. **Características de Base de Datos**:
    - ✅ Foreign keys con cascade delete
    - ✅ Índices para optimización de queries
    - ✅ Unique constraints para prevenir duplicados
    - ✅ Soft deletes en comentarios
    - ✅ Timestamps en todas las tablas
    - ✅ Enums para estados controlados

### 🎯 Funcionalidades Implementadas

#### En las Vistas:

- ✅ **Welcome Page** (`/`) - Usa datos de la base de datos
- ✅ **Dashboard** (`/dashboard`) - Usa datos de la base de datos
- ✅ Mostrar fecha de publicación en formato relativo ("hace 2 horas")
- ✅ Conteo dinámico de likes desde la base de datos
- ✅ Conteo dinámico de comentarios desde la base de datos

#### En los Modelos:

- ✅ Relaciones completas en todos los modelos
- ✅ Métodos auxiliares (`isLikedBy`, `isBookmarkedBy`)
- ✅ Atributos calculados (`likes_count`, `comments_count`)

#### En los Controladores:

- ✅ `TipController` con métodos `index()` y `dashboard()`
- ✅ Uso de eager loading para optimización
- ✅ Uso de `withCount()` para conteos eficientes

## 📦 Datos de Ejemplo

El sistema incluye seeders que crean:

- **6 usuarios** (incluyendo 5 usuarios temáticos)
- **8 tips** variados (mezcla con y sin imágenes)
- **40 likes** distribuidos aleatoriamente
- **42 comentarios** con contenido de ejemplo
- **6 bookmarks** en tips seleccionados

### Usuarios creados:

1. `eco_felix@example.com` - Especialista en Zero Waste
2. `solar_pro@example.com` - Experto en energía solar
3. `green_kitchen@example.com` - Chef vegano
4. `urban_cyclist@example.com` - Activista de transporte sostenible
5. `ana_green@example.com` - Educadora ambiental
6. `test@example.com` - Usuario de prueba

**Contraseña para todos**: `password`

## 🚀 Cómo Usar

### 1. Configurar la Base de Datos

Si es la primera vez o quieres resetear todo:

```bash
# Eliminar y recrear todas las tablas
php artisan migrate:fresh

# Poblar con datos de ejemplo
php artisan db:seed
```

O hacerlo en un solo comando:

```bash
php artisan migrate:fresh --seed
```

### 2. Verificar que Funciona

```bash
# Iniciar el servidor
php artisan serve

# Visitar:
# - http://localhost:8000 (Welcome page con datos de DB)
# - http://localhost:8000/dashboard (requiere login)
```

### 3. Agregar Más Datos

Si quieres agregar más datos sin borrar los existentes:

```bash
php artisan db:seed --class=TipSeeder
```

## 📖 Estructura de Archivos

### Migraciones

- `2026_03_29_223750_create_tips_table.php`
- `2026_03_29_224737_create_comments_table.php`
- `2026_03_29_224742_create_likes_table.php`
- `2026_03_29_224749_create_bookmarks_table.php`
- `2026_03_29_224754_create_reports_table.php`
- `2026_03_29_224816_remove_likes_and_comments_from_tips_table.php`

### Modelos

- `app/Models/User.php` ✅ Actualizado con relaciones
- `app/Models/Tip.php` ✅ Actualizado con relaciones
- `app/Models/Comment.php` ✅ Nuevo
- `app/Models/Like.php` ✅ Nuevo
- `app/Models/Bookmark.php` ✅ Nuevo
- `app/Models/Report.php` ✅ Nuevo

### Controladores

- `app/Http/Controllers/TipController.php` ✅ Actualizado

### Vistas

- `resources/views/welcome.blade.php` ✅ Actualizada
- `resources/views/dashboard.blade.php` ✅ Actualizada
- `resources/views/components/tip-card.blade.php` ✅ Actualizada con fecha

### Seeders

- `database/seeders/TipSeeder.php` ✅ Actualizado
- `database/seeders/DatabaseSeeder.php` ✅ Actualizado

### Rutas

- `routes/web.php` ✅ Actualizado

## 🎯 Próximos Pasos Sugeridos

### Funcionalidades por Implementar:

1. **Sistema de Likes**
    - Botón para dar/quitar like
    - API endpoint: `POST /tips/{id}/like`
    - Actualizar contador en tiempo real

2. **Sistema de Comentarios**
    - Formulario para agregar comentarios
    - Mostrar lista de comentarios
    - API endpoints: `GET /tips/{id}/comments`, `POST /tips/{id}/comments`

3. **Sistema de Guardados**
    - Botón para guardar/des-guardar
    - Página de tips guardados (`/saved`)
    - API endpoint: `POST /tips/{id}/bookmark`

4. **Sistema de Reportes**
    - Modal con formulario de reporte
    - API endpoint: `POST /tips/{id}/report`
    - Panel admin para revisar reportes

5. **Autenticación**
    - Proteger acciones (like, comment, etc.)
    - Verificar permisos de usuario

6. **Paginación**
    - Implementar paginación en listados
    - Infinite scroll opcional

## 📊 Queries Útiles

### Obtener tips con toda la información

```php
Tip::with(['user', 'likes', 'comments'])
    ->withCount(['likes', 'comments'])
    ->get();
```

### Obtener tips que le gustan a un usuario

```php
$user->likedTips()->with('user')->withCount(['likes', 'comments'])->get();
```

### Obtener comentarios de un tip

```php
$tip->comments()->with('user')->orderBy('created_at', 'desc')->get();
```

### Verificar si un usuario le dio like a un tip

```php
$tip->isLikedBy(auth()->user());
```

## 🔍 Debugging

Ver todas las queries ejecutadas:

```php
DB::enableQueryLog();
// ... tu código ...
dd(DB::getQueryLog());
```

Ver conteos de una tabla:

```bash
php artisan tinker
>>> App\Models\Tip::count()
>>> App\Models\Like::count()
```

## 📝 Notas Importantes

1. **Los conteos son dinámicos**: Los likes y comments se calculan en tiempo real desde la base de datos
2. **Fechas relativas**: Las fechas se muestran como "hace X tiempo" usando `diffForHumans()`
3. **Optimización**: Se usa eager loading y `withCount()` para evitar N+1 queries
4. **Escalabilidad**: La estructura está preparada para crecer con índices y relaciones apropiadas

## 🎨 Personalización

### Cambiar las categorías disponibles

Edita en `app/Models/Tip.php` o crea una migración para una tabla de categorías.

### Agregar campos a usuarios

Crea una migración:

```bash
php artisan make:migration add_fields_to_users_table
```

### Modificar los seeders

Edita `database/seeders/TipSeeder.php` para cambiar los datos de ejemplo.

## ✨ ¡Todo Listo!

El sistema está completamente funcional y listo para usar. Todas las páginas ahora usan datos de la base de datos de manera escalable y optimizada.

Para cualquier duda, consulta la documentación detallada en `DATABASE_STRUCTURE.md`.
