{{-- Selector de Idioma - Dropdown --}}
<div class="relative" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.outside="open = false"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
        type="button"
    >
        <span class="text-lg">{{ $availableLocales[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="hidden sm:inline">{{ $availableLocales[$currentLocale]['native'] ?? $currentLocale }}</span>
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
        style="display: none;"
    >
        <div class="py-1">
            @foreach($availableLocales as $code => $locale)
                <a 
                    href="{{ route('locale.switch', $code) }}" 
                    class="flex items-center gap-3 px-4 py-2 text-sm {{ $currentLocale === $code ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors"
                >
                    <span class="text-lg">{{ $locale['flag'] }}</span>
                    <span>{{ $locale['native'] }}</span>
                    @if($currentLocale === $code)
                        <svg class="w-4 h-4 ml-auto text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
