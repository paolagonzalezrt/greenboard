@props(['icon', 'title', 'color' => 'blue', 'active' => false, 'slug' => null])

@php
    $colorClasses = [
        'orange' => 'bg-orange-500 dark:bg-orange-600',      // Alimentacion: #F97316
        'amber' => 'bg-amber-500 dark:bg-amber-600',         // Energia: #F59E0B
        'green' => 'bg-emerald-700 dark:bg-emerald-800',     // Naturaleza: #059669
        'teal' => 'bg-cyan-600 dark:bg-cyan-700',            // Transporte: #0891B2
        'blue' => 'bg-blue-600 dark:bg-blue-700',            // Hogar: #2563EB
        'slate' => 'bg-slate-600 dark:bg-slate-700',         // Residuos: #475569
        'purple' => 'bg-violet-600 dark:bg-violet-700',      // Consumo: #9333EA
        'rose' => 'bg-rose-600 dark:bg-rose-700',            // Educacion: #E11D48
    ];

    $bgColor = $colorClasses[$color] ?? $colorClasses['blue'];

    $params = request()->all();
    if ($active) {
        unset($params['category']);
    } else {
        $params['category'] = $slug ?? $title;
    }
    unset($params['page']); 
    $categoryUrl = request()->url() . (empty($params) ? '' : '?' . http_build_query($params));
@endphp

<a href="{{ $categoryUrl }}" 
   class="group cursor-pointer flex flex-col items-center gap-3 transition-all duration-300 flex-shrink-0 min-w-[72px] sm:min-w-0 sm:flex-1 {{ $active ? 'opacity-100 scale-110' : 'opacity-80 hover:opacity-100' }}">

    <div class="size-16 md:size-20 rounded-full {{ $bgColor }} flex items-center justify-center text-white group-hover:scale-110 group-hover:shadow-lg transition-all origin-center">
        <span class="material-symbols-outlined text-2xl md:text-3xl">{{ $icon }}</span>
    </div>

    <h3 class="font-semibold text-xs md:text-sm text-center px-2">
        {{ $title }}
    </h3>
</a>

