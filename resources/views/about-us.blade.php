@extends('layouts.app')

@section('title', 'GreenBoard - About Us')

@section('content')
<!-- Hero Section -->
<header class="relative w-full pt-16 pb-24 lg:pt-32 lg:pb-40">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="grid lg:grid-cols-12 gap-12 items-center">
<div class="lg:col-span-7">
            <h2 class="text-lg font-bold text-primary uppercase tracking-[0.3em] mb-6">{{ __('pages.about_title') }}</h2>
            <h1 class="text-5xl md:text-7xl font-black leading-[0.9] tracking-tighter mb-8">
                {{ __('pages.hero_title_part1') }} <span class="text-primary italic">{{ __('pages.hero_title_part2') }}</span> {{ __('pages.hero_title_part3') }}
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed">
                {{ __('pages.hero_description') }}
            </p>
</div>
<div class="lg:col-span-5 relative">
<div class="organic-shape bg-primary/20 absolute -inset-4 blur-3xl"></div>
<div class="relative rounded-[2rem] overflow-hidden border-8 border-white dark:border-slate-800 shadow-2xl rotate-3">
<img alt="Students collaborating on a green project" class="w-full h-[500px] object-cover" data-alt="A lush, green university campus with modern sustainable architecture and students walking" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCG8lNDdy9WVPlyt3GB9wsw9iZi7vyueK4ipm1_GslNkmfHmXXc57FfXboRoRCkoIAVXZ9uUPhEydlItjBQJ8HL6Z1CVqpxbeIrXxo_no2K3-eDnrNoR0oku2M_qg1P7f187svu7tPEzywGb6jVzTQB5Y2HXHFnjV-bY71_MuoKIuUAP3bfDHNh6RViaLpBB-RncOnVBHxVESq-GzdMzYcUNrOUcpLJqQjXc0nvirHs5aH2pDf0lY7zKJbDBTqeQr-WhVGzU4C1SsB4"/>
</div>
<div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl max-w-[200px] -rotate-6">
<span class="material-symbols-outlined text-primary text-4xl mb-2">groups</span>
<p class="text-xs font-bold uppercase text-slate-400">{{ __('pages.community_driven') }}</p>
<p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('pages.built_by_students') }}</p>
</div>
</div>
</div>
</div>
</header>
<!-- Mission Section -->
<section class="py-24 bg-background-light dark:bg-background-dark relative overflow-hidden w-full">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="text-center max-w-3xl mx-auto mb-20">
<h2 class="text-sm font-bold text-primary uppercase tracking-[0.2em] mb-4">{{ __('pages.our_mission') }}</h2>
<p class="text-4xl md:text-5xl font-extrabold leading-tight text-slate-900 dark:text-white">
                {{ __('pages.mission_main') }}
            </p>
</div>
<div class="grid md:grid-cols-2 gap-8 lg:gap-12 items-center">
<!-- Card 1 -->
<div class="group relative p-10 rounded-[2.5rem] bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
<div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-colors duration-300">
<span class="material-symbols-outlined text-3xl font-light">hub</span>
</div>
<h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white">{{ __('pages.uniting_fragments') }}</h3>
<p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    {{ __('pages.uniting_description') }}
                </p>
<div class="mt-8 h-1 w-12 bg-primary rounded-full group-hover:w-24 transition-all duration-500"></div>
</div>
<!-- Card 2 -->
<div class="group relative p-10 rounded-[2.5rem] bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
<div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-colors duration-300">
<span class="material-symbols-outlined text-3xl font-light">energy_savings_leaf</span>
</div>
<h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white">{{ __('pages.action_oriented') }}</h3>
<p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    {{ __('pages.action_description') }}
                </p>
<div class="mt-8 h-1 w-12 bg-primary rounded-full group-hover:w-24 transition-all duration-500"></div>
</div>
</div>
</div>
</section>
<!-- Call to Action -->
<section class="py-12 lg:py-20 bg-background-light dark:bg-background-dark relative overflow-hidden w-full">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="w-full rounded-[3rem] p-10 lg:p-16 relative overflow-hidden flex flex-col items-center text-center border border-primary/20 bg-primary/5">
<!-- Animated green glows -->
<style>
    @keyframes ctaFloat1 {
        0%, 100% { transform: translate(0px, 0px); }
        33% { transform: translate(40px, -50px); }
        66% { transform: translate(-30px, -30px); }
    }
    @keyframes ctaFloat2 {
        0%, 100% { transform: translate(0px, 0px); }
        33% { transform: translate(-50px, 40px); }
        66% { transform: translate(30px, 20px); }
    }
    @keyframes ctaPulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
        50% { transform: translate(-50%, -50%) scale(1.4); opacity: 0.7; }
    }
</style>
<div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-16 -left-16 w-72 h-72 bg-primary/10 rounded-full blur-[90px]" style="animation: ctaFloat1 5s ease-in-out infinite;"></div>
    <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-primary/8 rounded-full blur-[90px]" style="animation: ctaFloat2 6s ease-in-out infinite;"></div>
    <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-primary/6 rounded-full blur-[70px]" style="animation: ctaPulse 3s ease-in-out infinite;"></div>
</div>
<div class="relative z-10">
<h2 class="text-2xl md:text-5xl font-black mb-6 leading-tight text-slate-900 dark:text-white">
                {{ __('pages.ready_legacy') }}
</h2>
<p class="text-sm md:text-lg font-medium mb-10 max-w-xl mx-auto leading-relaxed text-slate-600 dark:text-slate-400">
                {{ __('pages.legacy_description') }}
            </p>
<div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="bg-transparent text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all text-center">
                    {{ __('pages.explore_initiatives') }}
                </a>
                @guest
                    <a href="{{ route('register') }}" class="bg-primary text-background-dark px-10 py-4 rounded-full font-bold text-lg hover:bg-primary/90 transition-all shadow-sm text-center">
                        {{ __('pages.create_profile') }}
                    </a>
                @endguest
            </div>
</div>
</div>
</section>

 @endsection 
