@props(['icon', 'title', 'subtitle', 'color' => 'blue', 'active' => false])

@php
    $colorClasses = [
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
        'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
        'emerald' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
        'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
    ];

    $colors = $colorClasses[$color] ?? $colorClasses['blue'];

    // Construir URL con parámetros
    $currentUrl = request()->fullUrl();
    $params = request()->all();
    $params['category'] = $title;
    unset($params['page']); // Reset pagination
    $categoryUrl = request()->url() . '?' . http_build_query($params);
@endphp

<a href="{{ $categoryUrl }}" class="group cursor-pointer bg-white dark:bg-slate-800 p-4 sm:p-5 lg:p-6 rounded-xl sm:rounded-2xl border transition-all relative overflow-hidden text-center sm:text-left {{ $active ? 'border-primary shadow-xl shadow-primary/10' : 'border-primary/10 hover:border-primary hover:shadow-xl hover:shadow-primary/5' }}">
    <div class="size-10 sm:size-12 rounded-lg sm:rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform mx-auto sm:mx-0">
        <span class="material-symbols-outlined text-2xl sm:text-3xl">{{ $icon }}</span>
    </div>
    <h3 class="font-bold text-base sm:text-lg">{{ $title }}</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle }}</p>
</a>
