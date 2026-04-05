# 🧪 Guía de Pruebas - Modal de Confirmación

## Requisitos Previos
- Estar autenticado en la aplicación
- Tener comentarios disponibles para eliminar
- Navegador moderno (Chrome, Firefox, Safari, Edge)

---

## 🧑‍💻 Pruebas Manuales

### Prueba 1: Modal Aparece Correctamente
**Pasos:**
1. Navega a una publicación con comentarios
2. Haz hover sobre un comentario tuyo
3. Haz click en el botón de 3 puntos (⋮)
4. Haz click en "Delete"
5. **Esperado**: Modal aparece con animación suave

**Verificar:**
- ✅ Modal está centrado
- ✅ Overlay oscuro aparece
- ✅ Texto es legible
- ✅ Botones son clickeables

---

### Prueba 2: Textos en Español
**Pasos:**
1. Cambia idioma a Español
2. Haz click en "Delete" de un comentario
3. **Esperado**: Modal muestra:
   - Título: "Eliminar Comentario"
   - Mensaje: "¿Estás seguro de que deseas eliminar este comentario? Esta acción no se puede deshacer."
   - Botón Cancelar: "Cancelar"
   - Botón Confirmar: "Eliminar"

---

### Prueba 3: Textos en Inglés
**Pasos:**
1. Cambia idioma a English
2. Haz click en "Delete" de un comentario
3. **Esperado**: Modal muestra:
   - Title: "Delete Comment"
   - Message: "Are you sure you want to delete this comment? This action cannot be undone."
   - Cancel Button: "Cancel"
   - Confirm Button: "Delete"

---

### Prueba 4: Textos en Alemán
**Pasos:**
1. Cambia idioma a Deutsch
2. Haz click en "Delete" de un comentario
3. **Esperado**: Modal muestra:
   - Titel: "Kommentar Löschen"
   - Nachricht: "Bist du sicher, dass du diesen Kommentar löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden."
   - Schaltfläche Abbrechen: "Abbrechen"
   - Schaltfläche Löschen: "Löschen"

---

### Prueba 5: Botón Cancelar Cierra Modal
**Pasos:**
1. Abre el modal de confirmación
2. Haz click en botón "Cancel" / "Cancelar" / "Abbrechen"
3. **Esperado**: Modal se cierra con animación

**Verificar:**
- ✅ Modal desaparece
- ✅ Overlay se desvanece
- ✅ Comentario NO se elimina

---

### Prueba 6: Botón X Cierra Modal
**Pasos:**
1. Abre el modal de confirmación
2. Haz click en el icono X (esquina superior derecha)
3. **Esperado**: Modal se cierra

**Verificar:**
- ✅ Modal desaparece
- ✅ Comentario NO se elimina

---

### Prueba 7: Click en Overlay Cierra Modal
**Pasos:**
1. Abre el modal de confirmación
2. Haz click en el área oscura (overlay) fuera del modal
3. **Esperado**: Modal se cierra

**Verificar:**
- ✅ Modal desaparece
- ✅ Comentario NO se elimina

---

### Prueba 8: Tecla ESC Cierra Modal
**Pasos:**
1. Abre el modal de confirmación
2. Presiona la tecla ESC
3. **Esperado**: Modal se cierra

**Verificar:**
- ✅ Modal desaparece
- ✅ Comentario NO se elimina

---

### Prueba 9: Confirmar Elimina Comentario
**Pasos:**
1. Abre el modal de confirmación
2. Haz click en botón "Delete" / "Eliminar" / "Löschen"
3. **Esperado**: 
   - Modal se cierra
   - Comentario desaparece con animación
   - Página se recarga automáticamente

**Verificar:**
- ✅ Comentario fue eliminado de la base de datos
- ✅ El contador de comentarios decrementó

---

### Prueba 10: Dark Mode
**Pasos:**
1. Activa Dark Mode
2. Abre el modal de confirmación
3. **Esperado**: Modal se ve correctamente en modo oscuro

**Verificar:**
- ✅ Fondo es gris oscuro (Slate-800)
- ✅ Texto es legible (blanco/gris claro)
- ✅ Botones tienen buen contraste
- ✅ Icono de peligro es rojo

---

### Prueba 11: Responsive - Desktop
**Pasos:**
1. En una pantalla desktop (1920x1080)
2. Abre el modal
3. **Esperado**: Modal tiene ancho máximo (~28rem)

**Verificar:**
- ✅ Modal NO ocupa toda la pantalla
- ✅ Hay espacio a los lados
- ✅ Texto es legible
- ✅ Botones son clickeables

---

### Prueba 12: Responsive - Tablet
**Pasos:**
1. En una pantalla tablet (768x1024)
2. Abre el modal
3. **Esperado**: Modal se adapta correctamente

**Verificar:**
- ✅ Modal ocupa ancho apropiado
- ✅ Padding es adecuado
- ✅ Texto es legible

---

### Prueba 13: Responsive - Mobile
**Pasos:**
1. En una pantalla mobile (375x667)
2. Abre el modal
3. **Esperado**: Modal llena la pantalla con márgenes

**Verificar:**
- ✅ Modal tiene p-4 de margen
- ✅ Texto no desborda
- ✅ Botones son clickeables con dedo
- ✅ Modal es desplazable si es necesario

---

### Prueba 14: Animaciones Suaves
**Pasos:**
1. Abre el modal
2. Observa la aparición
3. Cierra el modal
4. Observa la desaparición

**Verificar:**
- ✅ Aparición: suave fade + scale
- ✅ Desaparición: suave fade + scale inverso
- ✅ Duración: ~0.3 segundos
- ✅ Sin saltos o parpadeos

---

### Prueba 15: Icono Rojo (Peligro)
**Pasos:**
1. Abre el modal de eliminación
2. Observa el icono
3. **Esperado**: Icono es rojo (🗑️)

**Verificar:**
- ✅ Icono es delete_outline
- ✅ Color es rojo (#EF4444)
- ✅ Botón confirmar es rojo

---

## 🐛 Pruebas de Errores

### Prueba E1: Intento de Eliminar 2 Veces
**Pasos:**
1. Abre modal y confirma eliminación
2. Mientras se procesa, intenta hacer click nuevamente
3. **Esperado**: No pasa nada, se ejecuta una sola vez

**Verificar:**
- ✅ No hay requests duplicados
- ✅ Comentario se elimina una sola vez

---

### Prueba E2: Error de Conexión
**Pasos:**
1. Desactiva internet
2. Intenta eliminar un comentario
3. **Esperado**: Muestra mensaje de error

**Verificar:**
- ✅ Aparece notificación de error roja
- ✅ Comentario NO se elimina
- ✅ Modal se cierra

---

### Prueba E3: Acceso Denegado
**Pasos:**
1. Intenta eliminar comentario de otro usuario
2. **Esperado**: Error 403 Forbidden

**Verificar:**
- ✅ No aparece botón de eliminar
- ✅ O muestra error si intenta acceso directo

---

## 📊 Pruebas Automatizadas (Si aplica)

```javascript
// Test: Modal aparece al llamar showConfirmModal
test('showConfirmModal displays the modal', () => {
    showConfirmModal('delete-comment-modal');
    const modal = document.getElementById('delete-comment-modal');
    expect(modal.classList.contains('hidden')).toBe(false);
});

// Test: Modal se cierra al llamar closeConfirmModal
test('closeConfirmModal hides the modal', () => {
    showConfirmModal('delete-comment-modal');
    closeConfirmModal(null, 'delete-comment-modal');
    const modal = document.getElementById('delete-comment-modal');
    expect(modal.classList.contains('hidden')).toBe(true);
});

// Test: ESC cierra el modal
test('Escape key closes the modal', () => {
    showConfirmModal('delete-comment-modal');
    const event = new KeyboardEvent('keydown', { key: 'Escape' });
    document.dispatchEvent(event);
    const modal = document.getElementById('delete-comment-modal');
    expect(modal.classList.contains('hidden')).toBe(true);
});
```

---

## ✅ Checklist de Verificación

- [ ] Modal aparece correctamente
- [ ] Textos en Español correctos
- [ ] Textos en Inglés correctos
- [ ] Textos en Alemán correctos
- [ ] Botón Cancelar cierra modal
- [ ] Botón X cierra modal
- [ ] Click en overlay cierra modal
- [ ] Tecla ESC cierra modal
- [ ] Confirmar elimina comentario
- [ ] Dark Mode funciona
- [ ] Responsive en desktop
- [ ] Responsive en tablet
- [ ] Responsive en mobile
- [ ] Animaciones son suaves
- [ ] Icono es rojo (peligro)
- [ ] Sin errores en consola
- [ ] Sin requests duplicados
- [ ] Manejo de errores correcto

---

## 📸 Capturas de Pantalla Esperadas

### Light Mode - Español
```
┌─────────────────────────────────────────┐
│  [🗑️]  Eliminar Comentario    [✕]       │
├─────────────────────────────────────────┤
│                                         │
│  ¿Estás seguro de que deseas            │
│  eliminar este comentario? Esta         │
│  acción no se puede deshacer.           │
│                                         │
├─────────────────────────────────────────┤
│              [Cancelar]  [Eliminar]    │
└─────────────────────────────────────────┘
```

### Dark Mode - English
```
┌─────────────────────────────────────────┐
│  [🗑️]  Delete Comment        [✕]       │
├─────────────────────────────────────────┤
│                                         │
│  Are you sure you want to delete        │
│  this comment? This action cannot       │
│  be undone.                             │
│                                         │
├─────────────────────────────────────────┤
│              [Cancel]  [Delete]        │
└─────────────────────────────────────────┘
```

---

## 🎯 Criterios de Aceptación

✅ **DEBE cumplir con:**
1. Modal aparece cuando se hace click en "Delete"
2. Modal muestra textos correctos (3 idiomas)
3. Modal tiene botones funcionales
4. Modal se cierra correctamente (5 métodos)
5. Confirmación elimina el comentario
6. Funciona en todas las resoluciones
7. Dark Mode está soportado
8. Animaciones son suaves

---

## 📞 Reporte de Bugs

Si encuentras algún problema, reporte:
- Pantalla/resolución
- Navegador y versión
- Idioma configurado
- Dark Mode activado/desactivado
- Pasos para reproducir
- Comportamiento esperado vs actual
- Captura de pantalla o video
- Errores en consola (F12)

