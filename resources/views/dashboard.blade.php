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
            <div class="relative group w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary">
                    <span class="material-symbols-outlined text-[20px] sm:text-[24px]">search</span>
                </div>
                <input class="w-full h-12 sm:h-14 pl-11 sm:pl-12 pr-4 bg-white dark:bg-slate-800 border-2 border-primary/20 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base sm:text-lg font-medium placeholder:text-slate-400 outline-none shadow-sm" placeholder="Find your next sustainable habit..." type="text"/>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <section class="mb-12 sm:mb-14 lg:mb-16 w-full">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-3">
            <h2 class="text-xl sm:text-2xl font-bold">Browse Categories</h2>
            <a class="text-primary text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all" href="#">
                View all 
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
            <x-category-card 
                icon="home" 
                title="Home" 
                subtitle="Indoor sustainability"
                color="blue"
            />
            <x-category-card 
                icon="bolt" 
                title="Energy" 
                subtitle="Renewable efficiency"
                color="amber"
            />
            <x-category-card 
                icon="shopping_bag" 
                title="Consumption" 
                subtitle="Zero waste shopping"
                color="purple"
            />
            <x-category-card 
                icon="directions_bike" 
                title="Transport" 
                subtitle="Eco-friendly travel"
                color="emerald"
            />
            <x-category-card 
                icon="restaurant" 
                title="Food" 
                subtitle="Plant-based lifestyle"
                color="orange"
            />
        </div>
    </section>

    <!-- Featured Tips Section -->
    <section class="w-full mb-12 sm:mb-16">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold">Featured Sustainable Tips</h2>
            <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-primary/20 text-primary text-[9px] sm:text-[10px] font-bold rounded-full uppercase tracking-wider">Trending Now</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
            @foreach($tips as $tip)
                <x-tip-card
                    :id="$tip['id']"
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
        
        <!-- Load More Button -->
        <div class="flex justify-center mt-8 sm:mt-10 lg:mt-12">
            <button class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-xl border-2 border-primary text-primary font-bold hover:bg-primary hover:text-background-dark transition-all text-sm sm:text-base">
                Load More Tips
            </button>
        </div>
    </section>

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-xl shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">Create Post</span>
    </a>
@endsection
