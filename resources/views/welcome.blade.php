@extends('layouts.app')

@section('content')
    <div class="text-center mb-20 max-w-4xl">
        <span class="inline-block px-4 py-1.5 mb-8 text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 rounded-full">Community Insights</span>
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-8 bg-gradient-to-br from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
            Transform Your Daily Habits into Global Impact
        </h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-12">
            Explore practical tips shared by our community to lead a more sustainable lifestyle.
        </p>
        
        <form action="#" method="GET" class="relative max-w-2xl mx-auto group">
            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 group-focus-within:text-primary transition-colors">search</span>
            </div>
            <input name="search" class="block w-full pl-14 pr-6 py-5 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-full text-lg shadow-xl shadow-slate-200/50 dark:shadow-none focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400" placeholder="Search for sustainable tips..." type="text"/>
        </form>
    </div>

    <div class="w-full mb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
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
    </div>

    {{-- CTA Section --}}
    <div class="w-full max-w-4xl mx-auto bg-white dark:bg-slate-900 p-16 rounded-3xl shadow-2xl border border-primary/20 text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-primary/5 rounded-full"></div>
        <div class="relative z-10">
            <h3 class="text-4xl font-extrabold mb-8 tracking-tight">Start Your Sustainable Journey Today</h3>
            <button class="bg-primary text-background-dark px-14 py-6 rounded-2xl font-extrabold text-2xl hover:brightness-105 shadow-2xl shadow-primary/40 transition-all flex items-center justify-center gap-4 mx-auto group">
                Join the Movement
                <span class="material-symbols-outlined text-3xl font-bold group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </div>
    </div>
@endsection