<!-- @props(['icon', 'title', 'subtitle', 'color' => 'blue', 'active' => false])

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
</a> -->

@props(['icon', 'title', 'color' => 'blue', 'active' => false])

@php
    $colorClasses = [
        'blue'    => [
            'base'   => 'bg-blue-100/60 border-blue-200 text-blue-700/90 dark:bg-blue-900/10 dark:border-blue-900/40 dark:text-blue-400/90',
            'active' => 'bg-blue-200/80 border-blue-500 text-blue-800 dark:bg-blue-900/40 dark:border-blue-500 dark:text-blue-300 shadow-sm',
            'hover'  => 'hover:bg-blue-100 hover:border-blue-400 dark:hover:bg-blue-900/20'
        ],
        'amber'   => [
            'base'   => 'bg-amber-100/60 border-amber-200 text-amber-700/90 dark:bg-amber-900/10 dark:border-amber-900/40 dark:text-amber-400/90',
            'active' => 'bg-amber-200/80 border-amber-500 text-amber-800 dark:bg-amber-900/40 dark:border-amber-500 dark:text-amber-300 shadow-sm',
            'hover'  => 'hover:bg-amber-100 hover:border-amber-400 dark:hover:bg-amber-900/20'
        ],
        'purple'  => [
            'base'   => 'bg-purple-100/60 border-purple-200 text-purple-700/90 dark:bg-purple-900/10 dark:border-purple-900/40 dark:text-purple-400/90',
            'active' => 'bg-purple-200/80 border-purple-500 text-purple-800 dark:bg-purple-900/40 dark:border-purple-500 dark:text-purple-300 shadow-sm',
            'hover'  => 'hover:bg-purple-100 hover:border-purple-400 dark:hover:bg-purple-900/20'
        ],
        'emerald' => [
            'base'   => 'bg-emerald-100/60 border-emerald-200 text-emerald-700/90 dark:bg-emerald-900/10 dark:border-emerald-900/40 dark:text-emerald-400/90',
            'active' => 'bg-emerald-200/80 border-emerald-500 text-emerald-800 dark:bg-emerald-900/40 dark:border-emerald-500 dark:text-emerald-300 shadow-sm',
            'hover'  => 'hover:bg-emerald-100 hover:border-emerald-400 dark:hover:bg-emerald-900/20'
        ],
        'orange'  => [
            'base'   => 'bg-orange-100/60 border-orange-200 text-orange-700/90 dark:bg-orange-900/10 dark:border-orange-900/40 dark:text-orange-400/90',
            'active' => 'bg-orange-200/80 border-orange-500 text-orange-800 dark:bg-orange-900/40 dark:border-orange-500 dark:text-orange-300 shadow-sm',
            'hover'  => 'hover:bg-orange-100 hover:border-orange-400 dark:hover:bg-orange-900/20'
        ],
    ];

    $style = $colorClasses[$color] ?? $colorClasses['blue'];
    
    $params = request()->all();
    $params['category'] = $title;
    unset($params['page']); 
    $categoryUrl = request()->url() . '?' . http_build_query($params);

    $currentClasses = $active ? $style['active'] : "{$style['base']} {$style['hover']}";
@endphp

<a href="{{ $categoryUrl }}" 
   class="group cursor-pointer p-4 rounded-2xl border-2 transition-all duration-300 text-center flex flex-col items-center justify-center min-w-[110px] relative overflow-hidden {{ $currentClasses }}">
    
    <span class="material-symbols-outlined text-3xl mb-1 transition-transform duration-300 {{ $active ? 'scale-105' : 'group-hover:scale-110' }}"
          style="font-variation-settings: 'wght' 300, 'opsz' 48;">
        {{ $icon }}
    </span>
    
    <h3 class="font-semibold text-xs sm:text-sm transition-colors">
        {{ $title }}
    </h3>

    <div class="absolute inset-0 bg-gradient-to-br from-white/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
</a>