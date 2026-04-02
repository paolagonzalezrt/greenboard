# Avatar de Perfil en Navbar - Guía de Implementación

## Cambios Realizados

### 1. **Componente Blade: `profile-avatar.blade.php`**

- Nuevo componente para mostrar el avatar del usuario
- **Si tiene foto:** Muestra la imagen de perfil en círculo
- **Si NO tiene foto:** Muestra un icono de persona con color de fondo consistente

### 2. **Método en Modelo User**

Agregados tres métodos al modelo `app/Models/User.php`:

```php
// Obtener URL del avatar
public function getAvatarUrl()
// Verificar si tiene foto
public function hasProfilePhoto()
// Obtener color de fondo basado en ID
public function getAvatarBgColor()
```

### 3. **Navbar Actualizada**

- Desktop: Avatar clickeable con dropdown de perfil
- Mobile: Avatar en el encabezado del menú lateral

## Verificación en Base de Datos

El campo `photo` ya existe en la tabla `users`:

- Tipo: `string` nullable
- Ruta almacenada: `profile-photos/filename.ext`
- Se guarda mediante `Storage::disk('public')`

### Comando para verificar:

```sql
SELECT id, name, email, photo FROM users WHERE photo IS NOT NULL LIMIT 5;
```

## Compatibilidad con Hostinger

✅ **Totalmente compatible** por las siguientes razones:

1. **Uso de `asset()` helper**
    - Genera URLs dinámicas dependiendo del dominio
    - Funciona tanto en local (`http://localhost`) como en Hostinger

2. **Sistema de almacenamiento correcto**
    - Usa `Storage::disk('public')` que apunta a `storage/app/public`
    - En Hostinger: El symlink `public -> /home/user/public_html` debe estar creado (lo hace el installer automáticamente)

3. **Métodos en el modelo**
    - Lógica centralizada y fácil de mantener
    - No depende de librerías externas

4. **Variables de color consistentes**
    - Basadas en ID del usuario (no cambian)
    - Usa colores de Tailwind estándar

## Requisitos Previos en Hostinger

Para que funcione correctamente en Hostinger:

1. **Crear el symlink de almacenamiento** (si no existe):

    ```bash
    php artisan storage:link
    ```

2. **Permisos de carpeta** (550 o 755):

    ```bash
    chmod 755 storage/app/public
    chmod 755 public/storage
    ```

3. **Verificar en .env**:
    ```
    FILESYSTEM_DISK=public
    ```

## Uso en Vistas

### En Nav (Navbar)

```blade
<x-profile-avatar :user="Auth::user()" size="md" />
```

### Tamaños disponibles

- `xs` - 24px (text-xs)
- `sm` - 32px (text-sm)
- `md` - 36px (text-lg) - **Por defecto**
- `lg` - 48px (text-2xl)
- `xl` - 64px (text-4xl)

### Ejemplos

```blade
<!-- En navbar -->
<x-profile-avatar :user="Auth::user()" size="md" />

<!-- En perfil del usuario -->
<x-profile-avatar :user="$user" size="xl" />

<!-- En lista de usuarios -->
<x-profile-avatar :user="$user" size="sm" />
```

## Prueba en Local

1. **Con foto:**
    - Sube una foto de perfil en `/profile`
    - Verifica que aparezca en navbar

2. **Sin foto:**
    - Crea un usuario nuevo sin foto
    - Debe aparecer el icono + color de fondo

## Prueba en Hostinger

1. **Ejecuta en terminal:**

    ```bash
    php artisan storage:link
    ```

2. **Sube una foto en la plataforma**

3. **Verifica que se vea en navbar**

## Solución de Problemas

### La foto no aparece en Hostinger

1. Verifica que exista el archivo en `storage/app/public/profile-photos/`
2. Ejecuta `php artisan storage:link`
3. Revisa permisos: `chmod 755 storage/app/public`

### El color del fondo cambia

- Esto es normal solo si el ID del usuario cambia
- Basado en: `$this->id % 8` (8 colores disponibles)

### Icono no se muestra

- Verifica que Material Symbols esté cargado en el layout
- Comprueba que la clase `material-symbols-outlined` esté disponible
