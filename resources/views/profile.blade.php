@extends('layouts.app')

@section('content')
    <!-- Unified Profile Header Section -->
    <div class="mb-8 sm:mb-10 lg:mb-12 w-full">
        <div class="flex flex-col lg:flex-row justify-between gap-6 sm:gap-8">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 lg:gap-8">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="size-20 sm:size-24 md:size-32 rounded-full border-4 border-white dark:border-slate-800 bg-slate-200 shadow-lg overflow-hidden mx-auto sm:mx-0">
                        <img alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=400&background=13ec5b&color=102216&bold=true"/>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="flex-1 flex flex-col gap-3 sm:gap-4 text-center sm:text-left">
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</h1>
                    </div>
                    <div class="max-w-2xl">
                        <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400">
                            Urban gardener and zero-waste enthusiast. I've been on a mission to reduce my environmental footprint since 2020. Lover of composting, native plants, and DIY upcycling. Join me in making the world a bit greener, one tip at a time! 🌱
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-x-4 sm:gap-x-8 gap-y-2">
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">calendar_today</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">Joined {{ Auth::user()->created_at->format('F Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">article</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $postsCount }} {{ $postsCount == 1 ? 'Post' : 'Posts' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 sm:gap-3 flex-shrink-0 items-start justify-center sm:justify-start">
                <button class="flex items-center gap-2 rounded-full bg-primary px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-background-dark shadow-md transition-all hover:brightness-105">
                    <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                    <span class="hidden sm:inline">Edit Profile</span>
                    <span class="sm:hidden">Edit</span>
                </button>
                <button class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-base sm:text-lg">share</span>
                    <span class="hidden sm:inline">Share</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 sm:mb-8 w-full">
        <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
            <a href="{{ route('profile', ['tab' => 'my-tips']) }}" class="border-b-2 {{ $tab === 'my-tips' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">My Tips</a>
            <a href="{{ route('profile', ['tab' => 'saved']) }}" class="border-b-2 {{ $tab === 'saved' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">Saved</a>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="w-full">
        @if($tips->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4">{{ $tab === 'saved' ? 'bookmark' : 'article' }}</span>
                <p class="text-slate-500 dark:text-slate-400 text-lg">
                    @if($tab === 'saved')
                        You haven't saved any tips yet.
                    @else
                        You haven't published any tips yet.
                    @endif
                </p>
                @if($tab !== 'saved')
                    <a href="{{ route('tips.create') }}" class="inline-flex items-center gap-2 mt-4 rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-background-dark shadow-md transition-all hover:brightness-105">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Create Your First Tip
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                @foreach($tips as $tip)
                    <x-tip-card 
                        :category="$tip['category']"
                        :user="$tip['user']"
                        :title="$tip['title']"
                        :description="$tip['description']"
                        :likes="$tip['likes']"
                        :comments="$tip['comments']"
                        :image="$tip['image']"
                        :avatar="$tip['avatar']"
                    />
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
