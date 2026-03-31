# 🚫 Sistema de Reportes - GreenBoard

## 📋 Descripción

El sistema de reportes de GreenBoard permite a los usuarios de la comunidad reportar tips que violan las reglas o contienen contenido inapropiado. Los reportes son revisados por administradores quienes toman las acciones necesarias.

## ✨ Características

### Para Usuarios
- ✅ Modal intuitivo y fácil de usar
- ✅ Múltiples razones de reporte predefinidas
- ✅ Campo opcional para descripción detallada
- ✅ Validaciones automáticas
- ✅ Notificaciones de confirmación
- ✅ Diseño responsive (móvil, tablet, escritorio)
- ✅ Compatible con modo oscuro

### Validaciones Automáticas
- 🔒 Debes estar autenticado para reportar
- 🚫 No puedes reportar tus propios tips
- 🔁 No puedes reportar el mismo tip dos veces
- ✔️ Debes seleccionar una razón obligatoriamente

## 🎯 Razones de Reporte Disponibles

### 1. 🚫 Spam
**Descripción**: Contenido repetitivo o promocional

**Ejemplos**:
- Publicaciones duplicadas
- Promoción de productos o servicios no relacionados
- Enlaces a sitios externos sospechosos
- Contenido que no aporta valor

### 2. ⚠️ Contenido Inapropiado
**Descripción**: Contenido ofensivo o inapropiado

**Ejemplos**:
- Lenguaje vulgar u ofensivo
- Contenido violento o perturbador
- Material explícito o inapropiado
- Contenido que viola los términos de uso

### 3. ❌ Información Falsa
**Descripción**: Información incorrecta o engañosa

**Ejemplos**:
- Datos ambientales incorrectos
- Afirmaciones sin fundamento científico
- Consejos peligrosos o dañinos
- Información deliberadamente falsa

### 4. 👤 Acoso
**Descripción**: Acoso o intimidación

**Ejemplos**:
- Ataques personales
- Intimidación o amenazas
- Discriminación
- Doxxing o divulgación de información personal

### 5. 📝 Otro
**Descripción**: Otra razón no listada

**Uso**: Cuando el problema no encaja en las categorías anteriores

## 📖 Guía de Uso

### Paso 1: Localizar el Tip a Reportar
Navega por el dashboard, following o saved hasta encontrar el tip que deseas reportar.

### Paso 2: Abrir el Menú del Tip
1. En la esquina superior derecha de la tarjeta del tip, haz clic en el botón de tres puntos verticales (⋮)
2. Se desplegará un menú con opciones

### Paso 3: Seleccionar "Reportar"
1. Haz clic en la opción "Reportar" (ícono de bandera roja)
2. El menú se cerrará y se abrirá el modal de reporte

### Paso 4: Completar el Formulario

#### Seleccionar Razón (Obligatorio)
- Elige la razón que mejor describe el problema
- Solo puedes seleccionar una razón
- Lee las descripciones para elegir la más apropiada

#### Agregar Descripción (Opcional)
- Proporciona detalles adicionales sobre el problema
- Máximo 500 caracteres
- Incluye información relevante que ayude a los administradores
- El contador muestra caracteres restantes

#### Ejemplos de Buenas Descripciones
✅ **Bueno**: "Este tip recomienda usar productos químicos tóxicos que son dañinos para el medio ambiente y la salud."

✅ **Bueno**: "El usuario está promocionando su tienda online en lugar de compartir consejos ecológicos genuinos."

❌ **Malo**: "No me gusta"

❌ **Malo**: "Es malo"

### Paso 5: Enviar el Reporte
1. Revisa tu selección
2. Haz clic en "Enviar Reporte"
3. Espera la confirmación

### Paso 6: Confirmación
- Verás una notificación verde de éxito
- El modal se cerrará automáticamente
- Tu reporte ha sido enviado a los administradores

## 🔐 Seguridad y Privacidad

### Tu Información
- Tu identidad como reportante es visible solo para administradores
- Los reportes son confidenciales
- El autor del tip reportado no sabrá quién lo reportó

### Protección contra Abuso
- Un usuario solo puede reportar un tip una vez
- Los reportes falsos o malintencionados pueden resultar en sanciones
- Los administradores revisan cada reporte cuidadosamente

## 🛠️ Implementación Técnica

### Endpoint API
```
POST /tips/{tip}/report
```

### Parámetros
```json
{
    "reason": "spam|inappropriate|misleading|harassment|other",
    "description": "string (opcional, máx. 500 caracteres)"
}
```

### Respuestas

#### Éxito (201 Created)
```json
{
    "success": true,
    "message": "Reporte enviado exitosamente. Lo revisaremos pronto.",
    "report": {
        "id": 1,
        "user_id": 5,
        "tip_id": 10,
        "reason": "spam",
        "description": "...",
        "status": "pending",
        "created_at": "2024-03-31T10:00:00.000000Z",
        "updated_at": "2024-03-31T10:00:00.000000Z"
    }
}
```

#### Error: No autenticado (401 Unauthorized)
```json
{
    "success": false,
    "message": "Debes iniciar sesión para reportar un tip."
}
```

#### Error: Reportando propio tip (403 Forbidden)
```json
{
    "success": false,
    "message": "No puedes reportar tu propio tip."
}
```

#### Error: Ya reportado (409 Conflict)
```json
{
    "success": false,
    "message": "Ya has reportado este tip anteriormente."
}
```

### Archivos Involucrados

#### Backend
- `app/Http/Controllers/ReportController.php` - Controlador de reportes
- `app/Models/Report.php` - Modelo de reporte
- `routes/web.php` - Ruta: `POST /tips/{tip}/report`

#### Frontend
- `resources/views/layouts/app.blade.php` - Contiene el modal y JavaScript
- `resources/views/components/tip-card.blade.php` - Botón de reportar

### Base de Datos
```sql
CREATE TABLE reports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    tip_id BIGINT NOT NULL,
    reason ENUM('spam', 'inappropriate', 'misleading', 'harassment', 'other') NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'reviewed', 'resolved', 'dismissed') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tip_id) REFERENCES tips(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tip_report (user_id, tip_id)
);
```

## 🎨 Características de UI/UX

### Modal de Reporte
- **Diseño Limpio**: Interfaz moderna con Material Design
- **Responsive**: Se adapta a móvil, tablet y escritorio
- **Modo Oscuro**: Totalmente compatible
- **Animaciones**: Entrada y salida suaves
- **Backdrop Blur**: Efecto de desenfoque en fondo
- **Contador de Caracteres**: Muestra 0/500 en tiempo real

### Estados del Botón
- **Normal**: Icono de enviar + texto "Enviar Reporte"
- **Cargando**: Icono girando + texto "Enviando..."
- **Deshabilitado**: Durante el envío para evitar doble submit

### Notificaciones
- **Éxito**: Verde con ícono ✓
- **Error**: Rojo con ícono ⚠️
- **Posición**: Esquina superior derecha
- **Animación**: Slide-in desde la derecha
- **Duración**: 4 segundos
- **Auto-cierre**: Con fade-out suave

### Cierre del Modal
- ✅ Click en botón "Cancelar"
- ✅ Click en botón X (cerrar)
- ✅ Presionar tecla ESC
- ✅ Click en el backdrop (fondo oscuro)
- ✅ Después de enviar exitosamente

## ❓ Preguntas Frecuentes

### ¿Qué pasa después de reportar un tip?
Los administradores recibirán tu reporte y lo revisarán. Si es válido, tomarán las acciones apropiadas que pueden incluir:
- Actualizar el estado del reporte
- Contactar al autor del tip
- Eliminar el tip si viola las reglas
- Marcar el reporte como resuelto

### ¿Puedo ver el estado de mis reportes?
Actualmente los reportes se envían a los administradores. En futuras versiones podrás ver el estado de tus reportes.

### ¿Qué debo hacer si no veo el botón de reportar?
- Verifica que hayas iniciado sesión
- Si es tu propio tip, no puedes reportarlo
- Actualiza la página e intenta de nuevo

### ¿Puedo cancelar un reporte después de enviarlo?
No, los reportes no se pueden cancelar una vez enviados. Los administradores pueden marcarlo como "descartado" si no es válido.

### ¿Cuántos tips puedo reportar?
Puedes reportar tantos tips como sea necesario, pero solo una vez por tip. Úsalo responsablemente.

## 🚀 Mejoras Futuras

- [ ] Ver historial de reportes propios
- [ ] Notificaciones sobre estado de reportes
- [ ] Categorías adicionales de reporte
- [ ] Reportes de comentarios
- [ ] Reportes de usuarios
- [ ] Sistema de puntos por reportes válidos
- [ ] Dashboard de reportes para usuarios

## 📞 Soporte

Si tienes problemas con el sistema de reportes o preguntas sobre qué reportar, contacta al equipo de administración.

---

**¡Gracias por ayudarnos a mantener GreenBoard limpio y seguro! 🌱**
