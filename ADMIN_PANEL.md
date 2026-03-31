# Panel de Administración - GreenBoard

## 🛡️ Descripción

El panel de administración de GreenBoard permite a los superusuarios gestionar los tips que han sido reportados por la comunidad. Los administradores pueden revisar los reportes, actualizar su estado y eliminar tips inapropiados.

## ✨ Características

### Panel de Administración
- **Visualización de Tips Reportados**: Muestra todos los tips que tienen al menos un reporte
- **Estadísticas**: 
  - Total de tips reportados
  - Reportes pendientes
  - Total de reportes
- **Gestión de Reportes**:
  - Ver detalles de cada reporte (razón, descripción, usuario reportante)
  - Actualizar estado de reportes (Pendiente, Revisado, Resuelto, Descartado)
- **Eliminación de Tips**: Los administradores pueden eliminar tips reportados
- **Información Detallada**: Muestra autor del tip, fecha de publicación, categoría e imagen

### Sistema de Reportes para Usuarios
- **Modal de Reporte**: Interfaz intuitiva para reportar tips
- **Razones de Reporte**:
  - 🚫 Spam - Contenido repetitivo o promocional
  - ⚠️ Contenido inapropiado - Contenido ofensivo o inapropiado
  - ❌ Información falsa - Información incorrecta o engañosa
  - 👤 Acoso - Acoso o intimidación
  - 📝 Otro - Otra razón no listada
- **Descripción Adicional**: Campo opcional para proporcionar más detalles (máx. 500 caracteres)
- **Validaciones**:
  - No puedes reportar tus propios tips
  - No puedes reportar el mismo tip dos veces
  - Debes estar autenticado para reportar
- **Notificaciones**: Feedback visual al enviar reportes

## 🚀 Cómo Reportar un Tip (Para Usuarios)

### Paso 1: Abrir el Menú del Tip
1. Localiza el tip que deseas reportar
2. Haz clic en el botón de tres puntos verticales (⋮) en la esquina superior derecha de la tarjeta del tip

### Paso 2: Seleccionar "Reportar"
1. En el menú desplegable, haz clic en "Reportar"
2. Se abrirá un modal con el formulario de reporte

### Paso 3: Completar el Formulario
1. **Selecciona una razón** (obligatorio):
   - Spam
   - Contenido inapropiado
   - Información falsa
   - Acoso
   - Otro
2. **Agrega una descripción** (opcional):
   - Proporciona detalles adicionales sobre el reporte
   - Máximo 500 caracteres
3. Haz clic en "Enviar Reporte"

### Paso 4: Confirmación
- Recibirás una notificación confirmando que tu reporte fue enviado
- Los administradores revisarán el reporte lo antes posible

## 🔐 Acceso al Panel (Para Administradores)

### Crear un Superusuario

Para crear un usuario administrador, ejecuta el siguiente comando en la terminal:

```bash
php artisan admin:create
```

El comando te pedirá:
- Nombre del administrador
- Email del administrador
- Contraseña (mínimo 8 caracteres)
- Confirmación de contraseña

### Credenciales de Prueba

Se ha creado un usuario administrador de prueba con las siguientes credenciales:

- **Email**: admin@greenboard.com
- **Contraseña**: (la que ingresaste durante la creación)

## 🚀 Cómo Usar el Panel

### 1. Iniciar Sesión

1. Navega a `/login`
2. Ingresa las credenciales del administrador
3. Una vez autenticado, verás un enlace "Admin" en la barra de navegación (escritorio) o en el menú móvil

### 2. Acceder al Panel de Administración

**Opción 1: Desde el Menú de Navegación (Desktop)**
- Busca el enlace "Admin" con el ícono de escudo en la barra de navegación superior

**Opción 2: Desde el Menú Desplegable (Desktop)**
- Haz clic en tu avatar de perfil
- Selecciona "Admin Panel"

**Opción 3: Desde el Menú Móvil**
- Abre el menú hamburguesa
- Selecciona "Admin Panel"

**Opción 4: URL Directa**
- Navega a `/admin/reported-tips`

### 3. Gestionar Tips Reportados

En el panel verás:

#### Tarjetas de Estadísticas
- Total de tips reportados
- Reportes pendientes
- Total de reportes en el sistema

#### Lista de Tips Reportados
Cada tip muestra:
- Título y descripción del tip
- Categoría
- Autor (nombre y email)
- Fecha de publicación
- Imagen (si existe)
- Número de reportes recibidos

#### Detalles de Reportes
Para cada reporte se muestra:
- Razón del reporte (Spam, Contenido inapropiado, etc.)
- Descripción adicional
- Estado actual (Pendiente, Revisado, Resuelto, Descartado)
- Usuario que reportó (nombre y email)
- Fecha del reporte

#### Acciones Disponibles

**1. Actualizar Estado de Reporte**
- Selecciona un nuevo estado en el dropdown
- Haz clic en "Actualizar"
- Estados disponibles:
  - **Pendiente**: Reporte aún no revisado
  - **Revisado**: Reporte revisado por un administrador
  - **Resuelto**: Problema resuelto (tip eliminado o acción tomada)
  - **Descartado**: Reporte sin fundamento

**2. Ver Tip Completo**
- Haz clic en "Ver Tip Completo" para abrir el tip en una nueva pestaña
- Revisa el contenido completo antes de tomar una decisión

**3. Eliminar Tip**
- Haz clic en "Eliminar Tip"
- Confirma la acción en el diálogo de confirmación
- El tip y todos sus datos relacionados (comentarios, likes, reportes) serán eliminados permanentemente
- Si el tip tiene una imagen, también será eliminada del almacenamiento

## 🔒 Seguridad

- Solo usuarios con `is_admin = true` pueden acceder al panel
- Las rutas están protegidas por el middleware `AdminMiddleware`
- Intentos de acceso no autorizados resultan en error 403
- Todas las acciones requieren autenticación CSRF

## 📁 Archivos Relacionados

### Controladores
- `app/Http/Controllers/AdminController.php` - Lógica del panel de administración
- `app/Http/Controllers/ReportController.php` - Gestiona los reportes de usuarios

### Modelos
- `app/Models/User.php` - Modelo de usuario (incluye campo `is_admin`)
- `app/Models/Tip.php` - Modelo de tip
- `app/Models/Report.php` - Modelo de reporte

### Vistas
- `resources/views/admin/reported-tips.blade.php` - Vista del panel de administración
- `resources/views/layouts/app.blade.php` - Incluye el modal de reportes

### Rutas
- `routes/web.php` - Define las rutas de administración y reportes

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Protege las rutas de administración

### Comandos
- `app/Console/Commands/CreateAdminUser.php` - Comando para crear administradores

### Migraciones
- `database/migrations/*_add_is_admin_to_users_table.php` - Añade campo `is_admin` a usuarios

## 🎨 Características de UI/UX

### Panel de Administración
- **Diseño Responsivo**: Funciona perfectamente en móviles, tablets y escritorio
- **Modo Oscuro**: Compatible con el tema oscuro de la aplicación
- **Animaciones**: Transiciones suaves y feedback visual
- **Mensajes de Éxito/Error**: Notificaciones claras después de cada acción
- **Confirmación de Eliminación**: Previene eliminaciones accidentales
- **Estadísticas en Tiempo Real**: Contadores actualizados automáticamente
- **Iconos Intuitivos**: Usa Material Symbols para una mejor experiencia

### Modal de Reportes
- **Diseño Limpio**: Interfaz moderna y fácil de usar
- **Responsive**: Se adapta perfectamente a cualquier pantalla
- **Contador de Caracteres**: Muestra caracteres restantes en descripción
- **Animaciones Suaves**: Entrada y salida del modal con animaciones elegantes
- **Cierre Inteligente**: Se cierra con ESC, clic en backdrop o botón cerrar
- **Estados de Carga**: Indica cuando se está enviando el reporte
- **Notificaciones**: Feedback inmediato sobre el resultado del reporte

## 🐛 Solución de Problemas

### Panel de Administración

#### No veo el enlace "Admin" en el menú
- Verifica que hayas iniciado sesión con un usuario administrador
- Revisa que el usuario tenga `is_admin = true` en la base de datos

#### Error 403 al acceder al panel
- Asegúrate de estar autenticado
- Verifica que tu usuario tenga privilegios de administrador

#### No se muestran los tips reportados
- Verifica que existan tips con reportes en la base de datos
- Revisa que la relación entre `Tip` y `Report` esté configurada correctamente

### Sistema de Reportes

#### No puedo reportar un tip
- Asegúrate de estar autenticado (inicia sesión)
- Verifica que no sea tu propio tip (no puedes reportar tus propios tips)
- Confirma que no lo hayas reportado anteriormente

#### El modal no se abre
- Verifica que JavaScript esté habilitado en tu navegador
- Revisa la consola del navegador para errores
- Actualiza la página e intenta nuevamente

#### Error al enviar el reporte
- Verifica tu conexión a internet
- Asegúrate de haber seleccionado una razón
- Verifica que el tip aún exista (puede haber sido eliminado)

## 📊 Base de Datos

### Campo `is_admin` en la tabla `users`
```sql
is_admin BOOLEAN DEFAULT FALSE
```

### Tabla `reports`
```sql
CREATE TABLE reports (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    tip_id BIGINT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tip_id) REFERENCES tips(id) ON DELETE CASCADE
);
```

### Relaciones
- `User` hasMany `Report`
- `Tip` hasMany `Report`
- `Report` belongsTo `User` (reportante)
- `Report` belongsTo `Tip` (tip reportado)

## 🔄 Flujo de Trabajo Recomendado

### Para Usuarios
1. **Identificar Contenido Inapropiado**
   - Revisa el tip que viola las reglas de la comunidad

2. **Abrir el Reporte**
   - Haz clic en el menú de tres puntos (⋮)
   - Selecciona "Reportar"

3. **Completar el Formulario**
   - Selecciona la razón más apropiada
   - Proporciona detalles adicionales si es necesario
   - Envía el reporte

4. **Esperar Revisión**
   - Los administradores revisarán tu reporte
   - Se tomarán las acciones apropiadas

### Para Administradores
1. **Revisar Reportes Nuevos**
   - Accede al panel diariamente
   - Revisa los reportes con estado "Pendiente"

2. **Evaluar el Contenido**
   - Haz clic en "Ver Tip Completo"
   - Lee el tip y los reportes cuidadosamente
   - Considera el contexto y las reglas de la comunidad

3. **Tomar Acción**
   - Si el reporte es válido:
     - Actualiza el estado a "Revisado"
     - Elimina el tip si es necesario
     - Marca el reporte como "Resuelto"
   - Si el reporte no es válido:
     - Actualiza el estado a "Descartado"

4. **Documentación**
   - Los cambios de estado quedan registrados
   - Las eliminaciones son permanentes

## 🚀 Próximas Mejoras Sugeridas

- [ ] Historial de acciones de administración
- [ ] Notificaciones a usuarios cuando sus tips son eliminados
- [ ] Búsqueda y filtrado de tips reportados
- [ ] Exportación de reportes para análisis
- [ ] Sistema de advertencias para usuarios
- [ ] Dashboard con gráficos y métricas
- [ ] Logs de auditoría

## 📞 Soporte

Si encuentras algún problema o tienes sugerencias, por favor contacta al equipo de desarrollo.

---

**¡Gracias por mantener GreenBoard seguro y limpio! 🌱**
