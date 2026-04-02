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
                class="block w-full pl-4 sm:pl-6 pr-12 sm:pr-14 py-3 sm:py-4 lg:py-5 bg-white dark:bg-custom-dark-input border-2 border-slate-100 dark:border-slate-800 rounded-full text-sm sm:text-base lg:text-lg shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 [&::-webkit-search-cancel-button]:hidden" 
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
            <h2 class="text-xl sm:text-2xl font-bold">Featured Sustainable Tips</h2>

            <!-- Sort Options -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-sort-text-light dark:text-sort-border-dark sm:block">Sort by:</span>
                <div class="relative">
                    <button onclick="toggleSortDropdown()" class="flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full bg-white dark:bg-custom-dark-button text-sort-text-light dark:text-white shadow-md dark:shadow-none hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        
                        <span>{{ ($sortBy ?? 'desc') === 'desc' ? 'Newest' : 'Oldest' }}</span>
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
                                Newest
                            </button>
                            <button type="submit" name="sort" value="asc" class="block px-4 py-2 text-sm hover:bg-primary hover:text-white transition-colors text-left {{ ($sortBy ?? 'desc') === 'asc' ? 'bg-primary/10 text-primary' : 'text-sort-text-light dark:text-slate-300' }}">
                                Oldest
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
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-700 dark:text-slate-300 mb-2">No Tips Found</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-center max-w-md mb-6">We couldn't find any sustainable tips matching your search or filter. Try adjusting your filters or browse our categories.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all">
                            <span class="material-symbols-outlined">clear_all</span>
                            <span>Clear Filters</span>
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

    {{-- CTA Section (Only for guests) --}}
    @guest
        <div class="w-full max-w-4xl mx-auto bg-white dark:bg-custom-dark-button p-8 sm:p-12 lg:p-16 rounded-2xl sm:rounded-3xl shadow-2xl border border-primary/20 text-center relative overflow-hidden mb-12 sm:mb-16">
            <div class="relative z-10">
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-6 sm:mb-8 tracking-tight px-2">Start Your Sustainable Journey Today</h3>
                <a href="{{ route('register') }}" class="inline-block bg-primary text-background-dark px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-bold text-base sm:text-lg hover:brightness-105 hover:scale-105 hover:shadow-[0_0_30px_rgba(19,236,91,0.4)] shadow-lg shadow-primary/30 transition-all duration-300">
                    Join the Movement
                </a>
            </div>
        </div>
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
