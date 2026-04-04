{{--
    Funciones globales para manejo del tema (dark mode).
    Incluir al final del body o después del contenido principal.
--}}
<script>
window.ThemeManager = {
    toggle() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        
        html.classList.remove('light', 'dark');
        html.classList.add(newTheme);
        localStorage.setItem('theme', newTheme);
        
        this.updateIcons();
    },
    
    updateIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        const iconText = isDark ? 'light_mode' : 'dark_mode';
        
        // Actualiza todos los iconos de tema en la página
        document.querySelectorAll('[data-theme-icon]').forEach(icon => {
            icon.textContent = iconText;
        });
    },
    
    init() {
        this.updateIcons();
        
        // Escuchar cambios en preferencia del sistema
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'dark' : 'light';
                document.documentElement.classList.remove('light', 'dark');
                document.documentElement.classList.add(newTheme);
                this.updateIcons();
            }
        });
    }
};

// Función global para compatibilidad con onclick
function toggleDarkMode() {
    window.ThemeManager.toggle();
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.ThemeManager.init();
});
</script>
