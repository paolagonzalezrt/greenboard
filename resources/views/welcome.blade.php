@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-12 sm:mb-16 lg:mb-20 w-full px-2 sm:px-4">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-2 sm:mb-4 lg:mb-6 text-slate-900 dark:text-white px-2">
            {{ __('content.hero_title') }}
        </h1>
        <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-6 sm:mb-8 lg:mb-12 px-4">
            {{ __('content.hero_description') }}
        </p>

        <form action="{{ route('home') }}" method="GET" class="relative max-w-2xl mx-auto group px-2">
            <input 
                name="search" 
                value="{{ $search ?? '' }}" 
                class="search-typing-input block w-full pl-4 sm:pl-6 pr-12 sm:pr-14 py-3 sm:py-4 lg:py-5 bg-white dark:bg-custom-dark-input border-2 border-slate-100 dark:border-slate-800 rounded-full text-sm sm:text-base lg:text-lg shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 [&::-webkit-search-cancel-button]:hidden" 
                placeholder="" 
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
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-custom-dark-input border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-bold shadow-sm">
                        <span>{{ $search }}</span>
                        <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}{{ ($category ?? false) ? '&category=' . $category : '' }}" class="hover:scale-110 transition-transform flex items-center">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </a>
                    </div>
                @endif

                @if($category ?? false)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-custom-dark-input border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-bold shadow-sm">
                        <span>{{ __('categories.' . $category) }}</span>
                        <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}{{ ($search ?? false) ? '&search=' . $search : '' }}" class="hover:scale-110 transition-transform flex items-center">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </a>
                    </div>
                @endif

                @if(($search ?? false) && ($category ?? false))
                    <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-[14px]">clear_all</span>
                        <span>{{ __('content.clear_all') }}</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Categories Grid -->
    <section class="mb-12 sm:mb-14 lg:mb-16 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h2 class="text-xl sm:text-2xl font-bold">{{ __('content.browse_categories') }}</h2>
        </div>
        <div class="overflow-x-auto sm:overflow-x-visible pt-2 -mt-2 sm:pt-0 sm:mt-0 pb-3 sm:pb-0 -mx-4 sm:-mx-4 lg:-mx-8 px-4 sm:px-4 lg:px-8">
            <div class="flex gap-4 sm:gap-4 lg:gap-6 min-w-min sm:w-full sm:justify-between overflow-visible">
                @php
                    $categoryKeys = [
                        ['key' => 'alimentacion', 'icon' => 'restaurant', 'color' => 'orange'],
                        ['key' => 'energia', 'icon' => 'bolt', 'color' => 'amber'],
                        ['key' => 'naturaleza', 'icon' => 'eco', 'color' => 'green'],
                        ['key' => 'transporte', 'icon' => 'directions_bike', 'color' => 'teal'],
                        ['key' => 'hogar', 'icon' => 'home', 'color' => 'blue'],
                        ['key' => 'residuos', 'icon' => 'recycling', 'color' => 'slate'],
                        ['key' => 'consumo', 'icon' => 'shopping_bag', 'color' => 'purple'],
                        ['key' => 'educacion', 'icon' => 'school', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($categoryKeys as $cat)
                    <x-category-card 
                        icon="{{ $cat['icon'] }}" 
                        title="{{ __('categories.' . $cat['key']) }}" 
                        color="{{ $cat['color'] }}"
                        slug="{{ $cat['key'] }}"
                        :active="($category ?? '') === $cat['key']"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Tips Section -->
    <section class="w-full mb-12 sm:mb-16">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold">{{ __('content.featured_tips') }}</h2>

            <!-- Sort Options -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-sort-text-light dark:text-sort-border-dark sm:block">{{ __('content.sort_by') }}</span>
                <div class="relative">
                    <button onclick="toggleSortDropdown()" class="flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full bg-white dark:bg-custom-dark-button text-sort-text-light dark:text-white shadow-md dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        <span>{{ ($sortBy ?? 'desc') === 'desc' ? __('content.newest') : __('content.oldest') }}</span>
                        <span class="material-symbols-outlined text-lg">unfold_more</span>
                    </button>
                    <div id="sort-dropdown" class="hidden absolute right-0 mt-2 w-28 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-custom-dark-button rounded-xl shadow-xl overflow-hidden z-50">
                        <form action="{{ route('home') }}" method="GET" class="flex flex-col">
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
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all">
                            <span class="material-symbols-outlined">clear_all</span>
                            <span>{{ __('content.clear_filters') }}</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 sm:mt-10 lg:mt-12">
            @if($tips->count() > 0)
                <x-pagination-per-page />
            @endif
            {{ $tips->links() }}
        </div>
    </section>

    {{-- CTA Section (Only for guests) --}}
    @guest
        <section class="py-12 lg:py-20 relative overflow-hidden w-full mb-4">
            <div class="w-full rounded-3xl p-10 lg:p-16 relative overflow-hidden flex flex-col items-center text-center border border-primary/20 bg-primary/5 shadow-xl shadow-slate-200/50 dark:shadow-none z-10">
                <!-- Animated green glows -->
                <style>
                    @keyframes welcomeCtaFloat1 {
                        0%, 100% { transform: translate(0px, 0px); }
                        33% { transform: translate(40px, -50px); }
                        66% { transform: translate(-30px, -30px); }
                    }
                    @keyframes welcomeCtaFloat2 {
                        0%, 100% { transform: translate(0px, 0px); }
                        33% { transform: translate(-50px, 40px); }
                        66% { transform: translate(30px, 20px); }
                    }
                    @keyframes welcomeCtaPulse {
                        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
                        50% { transform: translate(-50%, -50%) scale(1.4); opacity: 0.7; }
                    }
                </style>
                <div class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div class="absolute -top-16 -left-16 w-72 h-72 bg-primary/10 rounded-full blur-[90px]" style="animation: welcomeCtaFloat1 5s ease-in-out infinite;"></div>
                    <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-primary/8 rounded-full blur-[90px]" style="animation: welcomeCtaFloat2 6s ease-in-out infinite;"></div>
                    <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-primary/6 rounded-full blur-[70px]" style="animation: welcomeCtaPulse 3s ease-in-out infinite;"></div>
                </div>

                <div class="relative z-10">
                    <h2 class="text-2xl md:text-5xl font-black mb-3 leading-tight text-slate-900 dark:text-white">{{ __('content.join_title') }}</h2>
                    <p class="text-sm md:text-lg font-medium mb-6 max-w-xl mx-auto leading-relaxed text-slate-600 dark:text-slate-400">
                        Contribuye al desarrollo de soluciones sostenibles dentro de tu entorno y conecta con una comunidad consciente
                    </p>
                    <div class="flex flex-row gap-4 justify-center items-center">
                        <a href="{{ route('about-us') }}" class="bg-transparent text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 px-6 py-2.5 rounded-full font-bold text-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-all text-center">
                            Conoce más
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary text-background-dark px-6 py-2.5 rounded-full font-bold text-sm hover:bg-primary/90 transition-all shadow-sm text-center">
                            {{ __('content.join_button') }}
                        </a>
                    </div>
                </div>
        </section>
    @endguest


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
