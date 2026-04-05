<div class="flex items-center justify-center gap-3 mb-6">
    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('pagination.items_per_page') }}</span>
    <form method="GET" class="inline-flex gap-2" id="per-page-form">
        <!-- Preserve existing query parameters -->
        @foreach(request()->query() as $key => $value)
            @if($key !== 'per_page' && $key !== 'page')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <select name="per_page" onchange="document.getElementById('per-page-form').submit()" class="pl-3 pr-8 py-2 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-300 hover:border-primary focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
            @foreach([10, 20, 50, 100] as $option)
                <option value="{{ $option }}" {{ request('per_page', 20) == $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    </form>
</div>
