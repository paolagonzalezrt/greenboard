@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center w-full">
        <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto max-w-full px-2 py-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-lg sm:text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-lg sm:text-xl">chevron_left</span>
                </a>
            @endif

            {{-- Pagination Elements (Limited to 5 numbers max) --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                
                // Calculate range: 2 before current + current + 2 after = 5 max
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
                
                // Adjust range if we're near the beginning or end
                if ($end - $start < 4) {
                    if ($start == 1) {
                        $end = min($lastPage, 5);
                    } else if ($end == $lastPage) {
                        $start = max(1, $lastPage - 4);
                    }
                }
            @endphp

            {{-- Show first page if not in range --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all font-semibold text-xs sm:text-sm">
                    1
                </a>
                @if ($start > 2)
                    <span class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 text-slate-500 dark:text-slate-400 text-xs sm:text-sm">...</span>
                @endif
            @endif

            {{-- Page numbers in range --}}
            @foreach (range($start, $end) as $page)
                @if ($page == $currentPage)
                    <span aria-current="page" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg bg-primary text-background-dark font-bold text-xs sm:text-sm shadow-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all font-semibold text-xs sm:text-sm" aria-label="Go to page {{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Show last page if not in range --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 text-slate-500 dark:text-slate-400 text-xs sm:text-sm">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all font-semibold text-xs sm:text-sm">
                    {{ $lastPage }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-lg sm:text-xl">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center justify-center size-8 min-w-max sm:size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-lg sm:text-xl">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
