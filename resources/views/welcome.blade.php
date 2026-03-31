@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-12 sm:mb-16 lg:mb-20 w-full px-2 sm:px-4">
        <span class="inline-block px-3 sm:px-4 py-1 sm:py-1.5 mb-4 sm:mb-6 lg:mb-8 text-[10px] sm:text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 rounded-full">Community Insights</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 sm:mb-6 lg:mb-8 bg-gradient-to-br from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent px-2">
            Transform Your Daily Habits into Global Impact
        </h1>
        <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-6 sm:mb-8 lg:mb-12 px-4">
            Explore practical tips shared by our community to lead a more sustainable lifestyle.
        </p>

        <form action="{{ route('home') }}" method="GET" class="relative max-w-2xl mx-auto group px-2">
            <input 
                name="search" 
                value="{{ $search ?? '' }}" 
                class="block w-full pl-4 sm:pl-6 pr-12 sm:pr-14 py-3 sm:py-4 lg:py-5 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-full text-sm sm:text-base lg:text-lg shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 [&::-webkit-search-cancel-button]:hidden" 
                placeholder="Search for sustainable tips..." 
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
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Active filters:</span>

                @if($search ?? false)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                        <span>{{ $search }}</span>
                        <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}{{ ($category ?? false) ? '&category=' . $category : '' }}" class="hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    </div>
                @endif

                @if($category ?? false)
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                        <span>{{ $category }}</span>
                        <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}{{ ($search ?? false) ? '&search=' . $search : '' }}" class="hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    </div>
                @endif

                @if(($search ?? false) && ($category ?? false))
                    <a href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                        <span class="material-symbols-outlined text-[14px]">clear_all</span>
                        <span>Clear all</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Categories Grid -->
    <section class="mb-12 sm:mb-14 lg:mb-16 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h2 class="text-xl sm:text-2xl font-bold">Browse Categories</h2>
            @if($category ?? false)
                <a class="text-primary text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all" href="{{ route('home') }}?sort={{ $sortBy ?? 'desc' }}{{ ($search ?? false) ? '&search=' . $search : '' }}">
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
                <h2 class="text-xl sm:text-2xl font-bold">Featured Sustainable Tips</h2>
                <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-primary/20 text-primary text-[9px] sm:text-[10px] font-bold rounded-full uppercase tracking-wider">Trending Now</span>
            </div>

            <!-- Sort Options -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600 dark:text-slate-400 hidden sm:block">Sort by:</span>
                <form action="{{ route('home') }}" method="GET" class="flex gap-2">
                    @if($search ?? false)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    @if($category ?? false)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    <button type="submit" name="sort" value="desc" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all {{ ($sortBy ?? 'desc') === 'desc' ? 'bg-slate-700 dark:bg-slate-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' }}">
                        Newest
                    </button>
                    <button type="submit" name="sort" value="asc" class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full transition-all {{ ($sortBy ?? 'desc') === 'asc' ? 'bg-slate-700 dark:bg-slate-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' }}">
                        Oldest
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
            @foreach($tips as $tip)
                <x-tip-card 
                    :id="$tip['id']"
                    :userId="$tip['user_id']"
                    :category="$tip['category']"
                    :user="$tip['user']"
                    :title="$tip['title']"
                    :description="$tip['description']"
                    :likes="$tip['likes']"
                    :comments="$tip['comments']"
                    :image="$tip['image']"
                    :avatar="$tip['avatar']"
                    :published_at="$tip['published_at']"
                    :isLiked="$tip['is_liked']"
                    :isBookmarked="$tip['is_bookmarked']"
                />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 sm:mt-10 lg:mt-12">
            {{ $tips->links() }}
        </div>
    </section>

    {{-- CTA Section --}}
    <div class="w-full max-w-4xl mx-auto bg-white dark:bg-slate-900 p-8 sm:p-12 lg:p-16 rounded-2xl sm:rounded-3xl shadow-2xl border border-primary/20 text-center relative overflow-hidden mb-12 sm:mb-16">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-primary/5 rounded-full"></div>
        <div class="relative z-10">
            <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-6 sm:mb-8 tracking-tight px-2">Start Your Sustainable Journey Today</h3>
            <button class="bg-primary text-background-dark px-8 sm:px-10 lg:px-14 py-4 sm:py-5 lg:py-6 rounded-xl sm:rounded-2xl font-extrabold text-lg sm:text-xl lg:text-2xl hover:brightness-105 shadow-2xl shadow-primary/40 transition-all flex items-center justify-center gap-3 sm:gap-4 mx-auto group w-full sm:w-auto">
                Join the Movement
                <span class="material-symbols-outlined text-2xl sm:text-3xl font-bold group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </div>
    </div>
@endsection
