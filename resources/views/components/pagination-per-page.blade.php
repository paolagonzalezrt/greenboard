<div class="flex items-center justify-center gap-3 mb-6">
    <span class="text-sm text-sort-text-light dark:text-sort-border-dark">{{ __('pagination.items_per_page') }}</span>
    <div class="relative">
        <button onclick="togglePerPageDropdown()" class="flex items-center gap-1 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full bg-white dark:bg-custom-dark-button text-sort-text-light dark:text-white shadow-md dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
            <span class="pl-2">{{ request('per_page', 20) }}</span>
            <span class="material-symbols-outlined text-lg">unfold_more</span>
        </button>
        <div id="per-page-dropdown" class="hidden absolute right-0 mt-2 w-32 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-custom-dark-button rounded-xl shadow-xl overflow-hidden z-50">
            <form action="{{ request()->url() }}" method="GET" class="flex flex-col">
                <!-- Preserve existing query parameters -->
                @foreach(request()->query() as $key => $value)
                    @if($key !== 'per_page' && $key !== 'page')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                
                @foreach([10, 20, 50, 100] as $option)
                    <button type="submit" name="per_page" value="{{ $option }}" class="block px-4 py-2 text-sm hover:bg-primary hover:text-white transition-colors text-left {{ request('per_page', 20) == $option ? 'bg-primary/10 text-primary' : 'text-sort-text-light dark:text-slate-300' }}">
                        {{ $option }}
                    </button>
                @endforeach
            </form>
        </div>
    </div>
</div>

<script>
    function togglePerPageDropdown() {
        const dropdown = document.getElementById('per-page-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('per-page-dropdown');
        const button = event.target.closest('button[onclick="togglePerPageDropdown()"]');
        
        if (!button && dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
