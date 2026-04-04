@extends('layouts.app')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-500 text-green-700 dark:text-green-400 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Hero Search Section -->
    <div class="text-center mb-8 sm:mb-10 lg:mb-12 w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-6 sm:mb-8">{{ __('content.discover_sustainable') }}</h1>
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('dashboard') }}" method="GET" class="relative max-w-2xl mx-auto group px-2">
            <input 
                name="search" 
                value="{{ $search ?? '' }}" 
                class="block w-full pl-4 sm:pl-6 pr-12 sm:pr-14 py-3 sm:py-4 lg:py-5 bg-white dark:bg-custom-dark-input border-2 border-slate-100 dark:border-slate-800 rounded-full text-sm sm:text-base lg:text-lg shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 [&::-webkit-search-cancel-button]:hidden" 
                placeholder="{{ __('content.search_placeholder') }}" 
                type="search"
            />
            <button type="submit" class="absolute inset-y-0 right-0 pr-4 sm:pr-8 flex items-center cursor-pointer hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary hover:text-primary transition-colors text-[20px] sm:text-[24px]">search</span>
            </button>
            <input type="hidden" name="sort" value="{{ $sortBy ?? 'desc' }}">
            @if($category ?? false)
                <input type="hidden" name="category" value="{{ $category }}">
            @endif
        </form>

        @if(($search ?? false) || ($category ?? false))
            <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('content.active_filters') }}</span>

                @if($search ?? false)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                        <span>{{ $search }}</span>
                        <a href="{{ route('dashboard') }}?sort={{ $sortBy ?? 'desc' }}{{ ($category ?? false) ? '&category=' . $category : '' }}" class="hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    </div>
                @endif

                @if($category ?? false)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                        <span>{{ $category }}</span>
                        <a href="{{ route('dashboard') }}?sort={{ $sortBy ?? 'desc' }}{{ ($search ?? false) ? '&search=' . $search : '' }}" class="hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    </div>
                @endif

                @if(($search ?? false) && ($category ?? false))
                    <a href="{{ route('dashboard') }}?sort={{ $sortBy ?? 'desc' }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-[14px]">clear_all</span>
                        <span>{{ __('content.clear_all') }}</span>
                    </a>
                @endif
            </div>
        @endif
        </div>
    </div>

    <!-- Categories Grid -->
    <section class="mb-12 sm:mb-14 lg:mb-16 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h2 class="text-xl sm:text-2xl font-bold">{{ __('content.browse_categories') }}</h2>
            @if($category ?? false)
                <a class="text-primary text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all" href="{{ route('dashboard') }}?sort={{ $sortBy ?? 'desc' }}{{ ($search ?? false) ? '&search=' . $search : '' }}">
                    View all 
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
            <x-category-card 
                icon="home" 
                title="Home" 
                subtitle="Indoor sustainability"
                color="blue"
                :active="($category ?? '') === 'Home'"
            />
            <x-category-card 
                icon="bolt" 
                title="Energy" 
                subtitle="Renewable efficiency"
                color="amber"
                :active="($category ?? '') === 'Energy'"
            />
            <x-category-card 
                icon="shopping_bag" 
                title="Consumption" 
                subtitle="Zero waste shopping"
                color="purple"
                :active="($category ?? '') === 'Consumption'"
            />
            <x-category-card 
                icon="directions_bike" 
                title="Transport" 
                subtitle="Eco-friendly travel"
                color="emerald"
                :active="($category ?? '') === 'Transport'"
            />
            <x-category-card 
                icon="restaurant" 
                title="Food" 
                subtitle="Plant-based lifestyle"
                color="orange"
                :active="($category ?? '') === 'Food'"
            />
        </div>
    </section>

    <!-- Featured Tips Section -->
    <section class="w-full mb-12 sm:mb-16">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="flex items-center gap-2 sm:gap-3">
                <h2 class="text-xl sm:text-2xl font-bold">{{ __('content.featured_tips') }}</h2>
            </div>

            <!-- Sort Options -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-sort-text-light dark:text-sort-border-dark sm:block">{{ __('content.sort_by') }}</span>
                <div class="relative">
                    <button onclick="toggleSortDropdown()" class="flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full bg-white dark:bg-custom-dark-button text-sort-text-light dark:text-white shadow-md dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        <span>{{ ($sortBy ?? 'desc') === 'desc' ? __('content.newest') : __('content.oldest') }}</span>
                        <span class="material-symbols-outlined text-lg">unfold_more</span>
                    </button>
                    <div id="sort-dropdown" class="hidden absolute right-0 mt-2 w-28 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-custom-dark-button rounded-xl shadow-xl overflow-hidden z-50">
                        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col">
                            @if($search ?? false)
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                            @if($category ?? false)
                                <input type="hidden" name="category" value="{{ $category }}">
                            @endif
                            <button type="submit" name="sort" value="desc" class="block px-4 py-2 text-sm hover:bg-primary hover:text-white transition-colors text-left {{ ($sortBy ?? 'desc') === 'desc' ? 'bg-primary/10 text-primary' : 'text-sort-text-light dark:text-slate-300' }}">
                                {{ __('content.newest') }}
                            </button>
                            <button type="submit" name="sort" value="asc" class="block px-4 py-2 text-sm hover:bg-primary hover:text-white transition-colors text-left {{ ($sortBy ?? 'desc') === 'asc' ? 'bg-primary/10 text-primary' : 'text-sort-text-light dark:text-slate-300' }}">
                                {{ __('content.oldest') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
            @forelse($tips as $tip)
                <x-tip-card
                    :id="$tip['id']"
                    :userId="$tip['user_id']"
                    :category="$tip['category']"
                    :user="$tip['user']"
                    :user_obj="$tip['user_obj']"
                    :title="$tip['title']"
                    :description="$tip['description']"
                    :likes="$tip['likes']"
                    :comments="$tip['comments']"
                    :image="$tip['image']"
                    :published_at="$tip['published_at']"
                    :isLiked="$tip['is_liked']"
                    :isBookmarked="$tip['is_bookmarked']"
                />
            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-16 px-4">
                        <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4">search_off</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('content.no_tips_found') }}</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-center max-w-md mb-6">{{ __('content.no_tips_desc') }}</p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all">
                            <span class="material-symbols-outlined">clear_all</span>
                            <span>{{ __('content.clear_filters') }}</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 sm:mt-10 lg:mt-12">
            {{ $tips->links() }}
        </div>
    </section>

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-full shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">{{ __('content.create_post') }}</span>
    </a>

<script>
function toggleSortDropdown() {
    const dropdown = document.getElementById('sort-dropdown');
    dropdown.classList.toggle('hidden');
}

window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('sort-dropdown');
    const button = event.target.closest('button[onclick*="toggleSortDropdown"]');
    
    if (!button && dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endsection
