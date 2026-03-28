@props(['category', 'user', 'title', 'description', 'likes', 'comments', 'image', 'avatar'])

@php
    $categoryColors = [
        'Consumption' => ['bg' => 'bg-purple-100/90', 'text' => 'text-purple-600'],
        'Food' => ['bg' => 'bg-orange-100/90', 'text' => 'text-orange-600'],
        'Energy' => ['bg' => 'bg-amber-100/90', 'text' => 'text-amber-600'],
        'Transport' => ['bg' => 'bg-emerald-100/90', 'text' => 'text-emerald-600'],
        'Home' => ['bg' => 'bg-blue-100/90', 'text' => 'text-blue-600'],
    ];

    $colors = $categoryColors[$category] ?? ['bg' => 'bg-primary/20', 'text' => 'text-primary'];
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-md hover:shadow-xl transition-all group flex flex-col h-full">
    <div class="relative aspect-square">
        <img class="w-full h-full object-cover" data-alt="{{ $title }}" src="{{ $image }}"/>
        <span class="absolute top-2 right-2 sm:top-3 sm:right-3 {{ $colors['bg'] }} backdrop-blur-sm {{ $colors['text'] }} text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase">{{ $category }}</span>
    </div>
    <div class="p-4 sm:p-5 lg:p-6 flex-1 flex flex-col">
        <div class="flex items-center gap-2 mb-3 sm:mb-4">
            <img alt="{{ $user }}" class="size-6 sm:size-7 rounded-full bg-slate-100 object-cover" src="{{ $avatar }}"/>
            <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 truncate">{{ $user }}</span>
        </div>
        <div class="mb-4 sm:mb-6 flex-1">
            <h4 class="text-base sm:text-lg font-bold leading-tight mb-1.5 sm:mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $title }}</h4>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-normal leading-relaxed line-clamp-2 sm:line-clamp-3">{{ $description }}</p>
        </div>
        <div class="flex items-center justify-between text-slate-400 mt-auto pt-3 sm:pt-0">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">favorite</span>
                    <span class="text-[11px] sm:text-xs font-bold">{{ $likes }}</span>
                </div>
                <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">chat_bubble</span>
                    <span class="text-[11px] sm:text-xs font-bold">{{ $comments }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <button class="hover:text-primary transition-colors p-1">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
                </button>
                <button class="hover:text-primary transition-colors p-1">
                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">bookmark</span>
                </button>
            </div>
        </div>
    </div>
</div>
