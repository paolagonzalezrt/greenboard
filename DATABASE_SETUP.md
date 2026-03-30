# Instrucciones para configurar la base de datos

Para que la función de mostrar la fecha de publicación funcione correctamente, sigue estos pasos:

## 1. Ejecutar las migraciones

```bash
php artisan migrate
```

Esto creará la tabla `tips` en la base de datos con los siguientes campos:

- id
- user_id (relación con usuarios)
- category
- title
- description
- image (nullable)
- likes
- comments
- created_at (fecha de publicación)
- updated_at

## 2. Poblar la base de datos con datos de ejemplo

```bash
php artisan db:seed --class=TipSeeder
```

O ejecutar todos los seeders:

```bash
php artisan db:seed
```

## 3. Verificar que todo funciona

Accede a la página principal y deberías ver las tarjetas de tips con la fecha de publicación mostrada debajo del nombre del usuario (por ejemplo: "hace 5 minutos", "hace 2 horas", etc.).

## Estructura de la base de datos

### Modelo Tip

- Relación `belongsTo` con el modelo User
- Campos: category, title, description, image, likes, comments
- Timestamps automáticos (created_at, updated_at)

### Controlador TipController

- Obtiene los tips de la base de datos
- Incluye la relación con el usuario
- Formatea la fecha usando `diffForHumans()` (formato relativo: "hace X tiempo")

### Vista

- El componente `tip-card` ahora acepta el parámetro `published_at`
- La fecha se muestra en ambas versiones de tarjetas (con y sin imagen)
