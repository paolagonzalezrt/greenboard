{{-- Dropdown de Selector de Idioma --}}
<div class="relative" id="language-dropdown-container">
    <button 
        id="language-toggle-btn"
        onclick="toggleLanguageDropdown(event)" 
        class="p-2 w-10 h-10 rounded-full bg-slate-100 dark:bg-custom-dark-button text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center"
        title="Cambiar idioma"
    >
        <span class="material-symbols-outlined text-xl">translate</span>
    </button>

    {{-- Dropdown Menu --}}
    <div 
        id="language-dropdown-menu" 
        class="hidden absolute right-0 top-full mt-2 w-24 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-custom-dark-button rounded-xl shadow-xl overflow-hidden z-50"
    >
        @foreach($availableLocales as $code => $locale)
            <a 
                href="{{ route('locale.switch', $code) }}" 
                class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors {{ $currentLocale === $code ? 'bg-primary/10 text-primary' : '' }}"
            >
                {{ strtoupper($code) }}
            </a>
        @endforeach
    </div>
</div>

<script>
    // Toggle Language Dropdown
    function toggleLanguageDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('language-dropdown-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('language-dropdown-container');
        if (container && !container.contains(event.target)) {
            const menu = document.getElementById('language-dropdown-menu');
            if (menu) {
                menu.classList.add('hidden');
            }
        }
    });

    // Close dropdown when a language is selected
    document.querySelectorAll('#language-dropdown-menu a').forEach(link => {
        link.addEventListener('click', function() {
            const menu = document.getElementById('language-dropdown-menu');
            if (menu) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
