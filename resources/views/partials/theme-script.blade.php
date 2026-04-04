{{--
    Script para prevenir el flash de color incorrecto.
    Debe incluirse en el <head> ANTES de cualquier CSS que dependa del tema.
    Se ejecuta de forma síncrona para aplicar el tema antes del primer render.
--}}
<script>
(function() {
    const theme = localStorage.getItem('theme') ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.classList.remove('light', 'dark');
    document.documentElement.classList.add(theme);
})();
</script>
