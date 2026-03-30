@extends('layouts.app')

@section('title', 'Following - GreenBoard')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-8 sm:mb-10 lg:mb-12 w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Following</h1>
        <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
            Stay updated with posts from people and topics you follow
        </p>
    </div>

    <!-- Posts Section -->
    <section class="w-full mb-12 sm:mb-16">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold">Latest from Following</h2>
            <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-primary/20 text-primary text-[9px] sm:text-[10px] font-bold rounded-full uppercase tracking-wider">Updated</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-start">
            @forelse($tips as $tip)
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
            @empty
                <div class="col-span-full text-center py-16">
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-6xl mb-4 block">group</span>
                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">No posts yet</h3>
                    <p class="text-slate-500 dark:text-slate-500">Start following people to see their posts here</p>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-primary text-background-dark font-bold rounded-xl hover:brightness-105 transition-all">
                        Explore Posts
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Load More Button -->
        @if(count($tips) > 0)
            <div class="flex justify-center mt-8 sm:mt-10 lg:mt-12">
                <button class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-xl border-2 border-primary text-primary font-bold hover:bg-primary hover:text-background-dark transition-all text-sm sm:text-base">
                    Load More Posts
                </button>
            </div>
        @endif
    </section>

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-xl shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">Create Post</span>
    </a>
@endsection
