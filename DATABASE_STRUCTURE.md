# Estructura de Base de Datos - GreenBoard

## 📊 Diagrama de Relaciones

```
users
├─> tips (uno a muchos)
├─> comments (uno a muchos)
├─> likes (uno a muchos)
├─> bookmarks (uno a muchos)
└─> reports (uno a muchos)

tips
├─> user (pertenece a)
├─> comments (uno a muchos)
├─> likes (uno a muchos)
├─> bookmarks (uno a muchos)
└─> reports (uno a muchos)
```

## 📋 Tablas

### users

- id
- name
- email (único)
- email_verified_at
- password
- photo
- description
- location
- created_at
- updated_at

### tips

- id
- user_id (FK -> users)
- category
- title
- description
- image (nullable)
- created_at (fecha de publicación)
- updated_at

### comments

- id
- user_id (FK -> users)
- tip_id (FK -> tips)
- content
- created_at
- updated_at
- deleted_at (soft delete)
- **Índices**: (tip_id, created_at), user_id

### likes

- id
- user_id (FK -> users)
- tip_id (FK -> tips)
- created_at
- updated_at
- **Constraint único**: (user_id, tip_id) - Un usuario solo puede dar like una vez
- **Índices**: tip_id, user_id

### bookmarks

- id
- user_id (FK -> users)
- tip_id (FK -> tips)
- created_at
- updated_at
- **Constraint único**: (user_id, tip_id) - Un usuario solo puede guardar un tip una vez
- **Índices**: tip_id, (user_id, created_at)

### reports

- id
- user_id (FK -> users - quien reporta)
- tip_id (FK -> tips - tip reportado)
- reason (motivo del reporte)
- description (descripción detallada - nullable)
- status (enum: 'pending', 'reviewed', 'resolved', 'dismissed')
- created_at
- updated_at
- **Índices**: (tip_id, status), user_id, status

## 🔑 Relaciones en Eloquent

### Modelo User

```php
- tips() - hasMany(Tip::class)
- comments() - hasMany(Comment::class)
- likes() - hasMany(Like::class)
- bookmarks() - hasMany(Bookmark::class)
- reports() - hasMany(Report::class)
- likedTips() - belongsToMany(Tip::class, 'likes')
- bookmarkedTips() - belongsToMany(Tip::class, 'bookmarks')
```

### Modelo Tip

```php
- user() - belongsTo(User::class)
- comments() - hasMany(Comment::class)
- likes() - hasMany(Like::class)
- bookmarks() - hasMany(Bookmark::class)
- reports() - hasMany(Report::class)
```

### Modelos Comment, Like, Bookmark, Report

```php
- user() - belongsTo(User::class)
- tip() - belongsTo(Tip::class)
```

## 🎯 Métodos Útiles en Tip

### Atributos Calculados

```php
$tip->likes_count    // Número total de likes
$tip->comments_count // Número total de comentarios
```

### Métodos de Verificación

```php
$tip->isLikedBy($user)      // Verifica si el usuario le dio like
$tip->isBookmarkedBy($user) // Verifica si el usuario lo guardó
```

## 📝 Uso con Controladores

### Obtener tips con conteos

```php
Tip::with(['user', 'likes', 'comments'])
    ->withCount(['likes', 'comments'])
    ->get();
```

### Obtener tips que le gustan a un usuario

```php
$user->likedTips()->with('user')->get();
```

### Obtener tips guardados por un usuario

```php
$user->bookmarkedTips()->with('user')->get();
```

## 🚀 Escalabilidad

### Índices Implementados

- Todos los foreign keys están indexados automáticamente
- Índices compuestos para queries frecuentes:
    - `comments`: (tip_id, created_at) - Para listar comentarios ordenados
    - `bookmarks`: (user_id, created_at) - Para listar guardados del usuario
    - `reports`: (tip_id, status) - Para filtrar reportes por tip y estado

### Constraints

- Unique constraints en likes y bookmarks para evitar duplicados
- Foreign keys con cascade delete para mantener integridad referencial
- Soft deletes en comments para permitir recuperación

### Optimización de Queries

- Uso de `withCount()` para conteos eficientes
- Eager loading con `with()` para evitar N+1 queries
- Índices en columnas frecuentemente consultadas

## 🔐 Buenas Prácticas Implementadas

1. **Integridad Referencial**: Todos los FKs con cascade delete
2. **Prevención de Duplicados**: Unique constraints en likes y bookmarks
3. **Soft Deletes**: En comentarios para auditoría
4. **Índices Estratégicos**: En columnas de búsqueda y ordenamiento
5. **Enums**: Para status de reportes (valores controlados)
6. **Timestamps**: En todas las tablas para auditoría
7. **Relaciones Bidireccionales**: Modelos con relaciones completas

## 📊 Datos de Ejemplo

Los seeders crean:

- 5 usuarios de ejemplo
- 8 tips variados (con y sin imágenes)
- 5-50 likes por tip (aleatorio)
- 2-10 comentarios por tip (aleatorio)
- Bookmarks en tips seleccionados

## 🛠️ Comandos Útiles

```bash
# Refrescar base de datos y seeders
php artisan migrate:fresh --seed

# Solo seeders (sin borrar datos)
php artisan db:seed

# Seeder específico
php artisan db:seed --class=TipSeeder

# Ver estado de migraciones
php artisan migrate:status

# Rollback última migración
php artisan migrate:rollback

# Ver queries SQL en tiempo real
php artisan tinker
DB::enableQueryLog();
// ... ejecutar operaciones ...
DB::getQueryLog();
```
