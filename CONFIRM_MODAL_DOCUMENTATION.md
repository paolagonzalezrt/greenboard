# Componente Confirm Modal - Documentación

## Descripción
Componente reutilizable para confirmar acciones (eliminar, etc.) con un diseño que sigue el tema de la aplicación. Soporta multiidioma (EN, ES, DE).

## Ubicación
`resources/views/components/confirm-modal.blade.php`

## Propiedades

| Propiedad | Tipo | Defecto | Descripción |
|-----------|------|---------|-------------|
| `id` | string | `confirm-modal` | ID único del modal |
| `title` | string | `Confirm Action` | Título del modal |
| `message` | string | `Are you sure?` | Mensaje de confirmación |
| `confirmText` | string | `Confirm` | Texto del botón de confirmación |
| `cancelText` | string | `Cancel` | Texto del botón de cancelación |
| `onConfirm` | string | `` | Nombre de la función a ejecutar al confirmar |
| `isDangerous` | boolean | `false` | Si es true, muestra icono de peligro (rojo) y botón rojo |

## Uso

### Ejemplo 1: Modal de Eliminación (Multiidioma)
```blade
<x-confirm-modal 
    id="delete-comment-modal" 
    title="{{ __('comments.confirm_delete_title') }}"
    message="{{ __('comments.confirm_delete_message') }}"
    confirmText="{{ __('comments.confirm_delete_button') }}"
    cancelText="{{ __('comments.cancel') }}"
    onConfirm="performDeleteComment"
    isDangerous="true"
/>
```

### Ejemplo 2: Modal de Confirmación General
```blade
<x-confirm-modal 
    id="confirm-action-modal" 
    title="Confirm Action"
    message="Are you sure you want to proceed?"
    confirmText="Yes, proceed"
    cancelText="Cancel"
    onConfirm="handleConfirmation"
/>
```

## JavaScript API

### Mostrar el modal
```javascript
showConfirmModal('delete-comment-modal');
```

### Cerrar el modal
```javascript
closeConfirmModal(null, 'delete-comment-modal');
```

### Ejecutar acción confirmada
```javascript
// El modal ejecuta automáticamente la función especificada en onConfirm
executeConfirmAction(modalId, functionName);
```

## Ejemplo de Implementación Completa

### 1. En la vista Blade:
```blade
<!-- Botón que abre el modal -->
<button onclick="deleteItem(123)">Delete</button>

<!-- Modal de confirmación -->
<x-confirm-modal 
    id="delete-item-modal" 
    title="{{ __('messages.confirm_delete_title') }}"
    message="{{ __('messages.confirm_delete_message') }}"
    confirmText="{{ __('messages.delete') }}"
    cancelText="{{ __('messages.cancel') }}"
    onConfirm="performDelete"
    isDangerous="true"
/>

<!-- Script -->
<script>
    let pendingItemId = null;

    function deleteItem(itemId) {
        pendingItemId = itemId;
        showConfirmModal('delete-item-modal');
    }

    function performDelete() {
        if (!pendingItemId) return;
        const itemId = pendingItemId;
        pendingItemId = null;

        // Realizar la acción (fetch, etc)
        fetch(`/items/${itemId}`, {
            method: 'DELETE',
            // ...
        });
    }
</script>
```

## Traducciones Incluidas

### Español (lang/es/comments.php)
```php
'confirm_delete_title' => 'Eliminar Comentario',
'confirm_delete_message' => '¿Estás seguro de que deseas eliminar este comentario? Esta acción no se puede deshacer.',
'confirm_delete_button' => 'Eliminar',
'cancel' => 'Cancelar',
```

### English (lang/en/comments.php)
```php
'confirm_delete_title' => 'Delete Comment',
'confirm_delete_message' => 'Are you sure you want to delete this comment? This action cannot be undone.',
'confirm_delete_button' => 'Delete',
'cancel' => 'Cancel',
```

### Deutsch (lang/de/comments.php)
```php
'confirm_delete_title' => 'Kommentar Löschen',
'confirm_delete_message' => 'Bist du sicher, dass du diesen Kommentar löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
'confirm_delete_button' => 'Löschen',
'cancel' => 'Abbrechen',
```

## Características

✅ **Diseño responsivo**: Se adapta a desktop y móvil
✅ **Soporte Dark Mode**: Colores ajustados para modo oscuro
✅ **Multiidioma**: Soporta 3 idiomas (EN, ES, DE)
✅ **Personalizable**: Todos los textos y estilos pueden customizarse
✅ **Reutilizable**: Usa el mismo componente para múltiples confirmaciones
✅ **Cierre con ESC**: Puede cerrarse presionando la tecla Escape
✅ **Cierre al hacer click fuera**: Cierra al hacer click en el fondo oscuro
✅ **Colores dinámicos**: Botón rojo si isDangerous=true

## Estilos

- **Modal Overlay**: Negro translúcido con blur backdrop
- **Contenedor**: Blanco (light) / Slate-800 (dark)
- **Icono**: Verde primario (defecto) / Rojo (peligroso)
- **Botón Confirmar**: Primario (defecto) / Rojo (peligroso)
- **Botón Cancelar**: Gris neutra
