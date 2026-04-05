# 🎨 Vista Previa del Modal de Confirmación

## Estructura del Modal

```
┌─────────────────────────────────────────────┐
│  [🗑️]  Delete Comment          [✕]          │  ← Header
├─────────────────────────────────────────────┤
│                                             │
│  Are you sure you want to delete this      │  ← Mensaje
│  comment? This action cannot be undone.    │
│                                             │
├─────────────────────────────────────────────┤
│                    [Cancel]  [Delete]      │  ← Botones
└─────────────────────────────────────────────┘
```

## Estados del Modal

### 1. Modal Normal (Predeterminado)
- Icono: 🔍 Verde (help_outline)
- Botón Confirmar: Color primario (verde/azul)
- Uso: Confirmaciones generales

### 2. Modal Peligroso (isDangerous="true")
- Icono: 🗑️ Rojo (delete_outline)
- Botón Confirmar: Rojo
- Uso: Acciones destructivas (eliminar, cerrar cuenta)

## Ejemplo Visual - Eliminación de Comentario

### Inglés (English)
```
┌─────────────────────────────────────────────┐
│  [🗑️]  Delete Comment          [✕]          │
├─────────────────────────────────────────────┤
│                                             │
│  Are you sure you want to delete this      │
│  comment? This action cannot be undone.    │
│                                             │
├─────────────────────────────────────────────┤
│                    [Cancel]  [Delete]      │
└─────────────────────────────────────────────┘
```

### Español
```
┌─────────────────────────────────────────────┐
│  [🗑️]  Eliminar Comentario     [✕]          │
├─────────────────────────────────────────────┤
│                                             │
│  ¿Estás seguro de que deseas eliminar      │
│  este comentario? Esta acción no se puede  │
│  deshacer.                                 │
│                                             │
├─────────────────────────────────────────────┤
│                 [Cancelar]  [Eliminar]     │
└─────────────────────────────────────────────┘
```

### Deutsch
```
┌─────────────────────────────────────────────┐
│  [🗑️]  Kommentar Löschen       [✕]          │
├─────────────────────────────────────────────┤
│                                             │
│  Bist du sicher, dass du diesen Kommentar  │
│  löschen möchtest? Diese Aktion kann       │
│  nicht rückgängig gemacht werden.          │
│                                             │
├─────────────────────────────────────────────┤
│                 [Abbrechen]  [Löschen]     │
└─────────────────────────────────────────────┘
```

## Características Visuales

### Light Mode (Tema Claro)
```
├─ Fondo Modal: Blanco (#FFFFFF)
├─ Texto Principal: Gris Oscuro (#111827)
├─ Texto Secundario: Gris Medio (#6B7280)
├─ Icono: Verde Primario o Rojo (#EF4444)
├─ Botón Cancelar: Gris (#E5E7EB)
├─ Botón Confirmar: Primario o Rojo
└─ Overlay: Negro translúcido (#000000 60%)
```

### Dark Mode (Tema Oscuro)
```
├─ Fondo Modal: Slate-800 (#1E293B)
├─ Texto Principal: Slate-100 (#F1F5F9)
├─ Texto Secundario: Slate-400 (#94A3B8)
├─ Icono: Verde Primario o Rojo (#EF4444)
├─ Botón Cancelar: Slate-700 (#334155)
├─ Botón Confirmar: Primario o Rojo
└─ Overlay: Negro translúcido (#000000 60%)
```

## Animaciones

### Overlay
- Aparece: Fade-in (0.3s)
- Desaparece: Fade-out (0.3s)
- Efecto: Blur background (backdrop-blur-sm)

### Modal
- Aparece: Scale-up + fade-in (0.3s)
- Desaparece: Scale-down + fade-out (0.3s)
- Transform origin: Center

### Botones
- Hover: Brightness increase o color darker
- Active: Scale-down (98%)
- Transition: 150ms ease

## Responsividad

### Desktop (sm y mayores)
```
Width: max-w-md (28rem)
Padding: p-8 (2rem)
Text: text-xl (1.25rem) título
Gap entre botones: gap-3
```

### Mobile (< sm)
```
Width: Full - p-4 margin
Padding: p-6 (1.5rem)
Text: text-xl título
Gap entre botones: gap-3
Flex direction: Column en mobile si es necesario
```

## Flujo de Interacción

```
1. Usuario hace click en "Delete"
   ↓
2. Se llama deleteComment(id)
   ↓
3. Se abre el modal: showConfirmModal('delete-comment-modal')
   ↓
4. Usuario puede:
   
   A) Hacer click en "Cancel" → Cierra el modal
   B) Hacer click en [✕] → Cierra el modal
   C) Presionar ESC → Cierra el modal
   D) Hacer click fuera → Cierra el modal
   E) Hacer click en "Delete" → executeConfirmAction()
      ↓
      Se ejecuta performDeleteComment()
      ↓
      Fetch DELETE /comments/{id}
      ↓
      Si success: Anima y elimina elemento
      Si error: Muestra notificación de error
```

## Casos de Uso

### 1. Eliminar Comentario ✓ (Implementado)
```blade
<x-confirm-modal 
    id="delete-comment-modal" 
    isDangerous="true"
/>
```

### 2. Eliminar Publicación
```blade
<x-confirm-modal 
    id="delete-post-modal" 
    title="{{ __('post.confirm_delete_title') }}"
    message="{{ __('post.confirm_delete_message') }}"
    confirmText="{{ __('post.confirm_delete_button') }}"
    onConfirm="deletePost"
    isDangerous="true"
/>
```

### 3. Cerrar Sesión
```blade
<x-confirm-modal 
    id="logout-modal" 
    title="{{ __('auth.confirm_logout_title') }}"
    message="{{ __('auth.confirm_logout_message') }}"
    confirmText="{{ __('auth.logout') }}"
    onConfirm="performLogout"
    isDangerous="false"
/>
```

## Accesibilidad

✅ Soporte keyboard:
  - TAB: Navega entre botones
  - ENTER: Activa botón enfocado
  - ESC: Cierra modal

✅ Contraste de colores:
  - WCAG AA compliant
  - Ratio 4.5:1 o superior

✅ ARIA labels (estructura semántica)

## Ventajas sobre `confirm()`

| Característica | confirm() | Modal Personalizado |
|---|---|---|
| Diseño | Nativo del navegador | Personalizado |
| Estilos | No customizable | Totalmente personalizable |
| Dark Mode | No | ✅ Sí |
| Multiidioma | No | ✅ Sí |
| Animaciones | Ninguna | Suaves transiciones |
| Responsive | Parcial | ✅ Totalmente |
| UX | Básico | Moderno |
| Branding | Nativo | Acorde a marca |
