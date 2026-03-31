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
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-6 sm:mb-8">Discover Sustainable Living</h1>
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('dashboard') }}" method="GET" class="relative group w-full">
                <input 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="w-full h-12 sm:h-14 pl-4 pr-12 bg-white dark:bg-custom-dark-input border-2 border-primary/20 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base sm:text-lg font-medium placeholder:text-slate-400 outline-none shadow-sm [&::-webkit-search-cancel-button]:hidden" 
                    placeholder="Find your next sustainable habit..." 
                    type="search"
                />
                <button type="submit" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary text-[20px] sm:text-[24px]">search</span>
                </button>
                <input type="hidden" name="sort" value="{{ $sortBy ?? 'desc' }}">
                @if($category ?? false)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
            </form>

            @if(($search ?? false) || ($category ?? false))
                <div class="flex flex-wrap items-center justify-center gap-2 mt-3">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Active filters:</span>

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
                            <span>Clear all</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Categories Grid -->
    <section class="mb-12 sm:mb-14 lg:mb-16 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h2 class="text-xl sm:text-2xl font-bold">Browse Categories</h2>
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
                <h2 class="text-xl sm:text-2xl font-bold">Featured Sustainable Tips</h2>
                <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-primary/20 text-primary text-[9px] sm:text-[10px] font-bold rounded-full uppercase tracking-wider">Trending Now</span>
            </div>

            <!-- Sort Options -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600 dark:text-slate-400 hidden sm:block">Sort by:</span>
                <form action="{{ route('dashboard') }}" method="GET" class="flex gap-2">
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

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-xl shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">Create Post</span>
    </a>
@endsection
