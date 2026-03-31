@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center size-10 text-slate-500 dark:text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center justify-center size-10 rounded-lg bg-primary text-background-dark font-bold text-sm shadow-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all font-semibold text-sm" aria-label="Go to page {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center justify-center size-10 rounded-lg border-2 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-600 cursor-not-allowed">
                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
