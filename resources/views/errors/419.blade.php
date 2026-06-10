@extends('layouts.app')

@section('title', '419 — GreenBoard')

@section('content')
<div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20 min-h-[60vh] w-full px-4 py-16 fade-in">

    {{-- Leaf icon --}}
    <div class="flex-shrink-0 flex items-center justify-center w-48 h-48 rounded-full bg-primary/10">
        <span class="material-symbols-outlined text-primary" style="font-size: 96px; font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;">eco</span>
    </div>

    {{-- Text content --}}
    <div class="text-center lg:text-left max-w-md">
        <p class="text-7xl font-black text-primary mb-4 leading-none">419</p>
        <h1 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-slate-100 mb-4 leading-tight">
            Your session has expired
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-base mb-8 leading-relaxed">
            The page token expired due to inactivity. Click the button below to reload the page and try again.
        </p>
        {{-- Reload the previous page to get a fresh CSRF token --}}
        <button onclick="window.location.href = document.referrer || '{{ route('home') }}';"
                class="inline-flex items-center justify-center gap-2 bg-primary text-slate-900 font-bold px-6 py-3 rounded-full hover:brightness-105 transition-all active:scale-95 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-xl">refresh</span>
            Reload page
        </button>
    </div>

</div>
@endsection
