@props(['id' => null, 'userId' => null, 'category', 'user', 'user_obj', 'title', 'description', 'likes', 'comments', 'image' => null, 'published_at' => null, 'isLiked' => false, 'isBookmarked' => false])

@php
    $categoryMapping = [
        'hogar' => ['color' => 'blue', 'label' => 'hogar'],
        'alimentacion' => ['color' => 'orange', 'label' => 'alimentacion'],
        'consumo' => ['color' => 'purple', 'label' => 'consumo'],
        'transporte' => ['color' => 'teal', 'label' => 'transporte'],
        'residuos' => ['color' => 'slate', 'label' => 'residuos'],
        'energia' => ['color' => 'amber', 'label' => 'energia'],
        'naturaleza' => ['color' => 'green', 'label' => 'naturaleza'],
        'educacion' => ['color' => 'rose', 'label' => 'educacion'],
    ];

    $categoryColors = [
        'blue' => ['bg' => 'bg-blue-100/90', 'text' => 'text-blue-600'],
        'orange' => ['bg' => 'bg-orange-100/90', 'text' => 'text-orange-600'],
        'purple' => ['bg' => 'bg-violet-100/90', 'text' => 'text-violet-600'],
        'teal' => ['bg' => 'bg-cyan-100/90', 'text' => 'text-cyan-600'],
        'slate' => ['bg' => 'bg-slate-100/90', 'text' => 'text-slate-600'],
        'amber' => ['bg' => 'bg-amber-100/90', 'text' => 'text-amber-600'],
        'green' => ['bg' => 'bg-emerald-100/90', 'text' => 'text-emerald-600'],
        'rose' => ['bg' => 'bg-rose-100/90', 'text' => 'text-rose-600'],
    ];

    $catMapping = $categoryMapping[$category] ?? null;
    $colorName = $catMapping ? $catMapping['color'] : 'blue';
    $colors = $categoryColors[$colorName] ?? ['bg' => 'bg-primary/20', 'text' => 'text-primary'];
    $categoryLabel = $catMapping ? __('categories.' . $catMapping['label']) : $category;
    $uniqueId = uniqid('card-');
@endphp

<div class="bg-white dark:bg-custom-dark-button rounded-xl sm:rounded-2xl overflow-hidden border border-slate-100 dark:border-transparent shadow-md hover:shadow-xl transition-all group flex flex-col cursor-pointer h-full" onclick="window.location.href='{{ $id ? route('tips.show', $id) : '#' }}'">
    @if($image)
        <!-- Card with Image -->
        <div class="relative aspect-[4/3] max-h-64">
            <img class="w-full h-full object-cover" data-alt="{{ $title }}" src="{{ $image }}"/>
        </div>
        <div class="p-4 sm:p-5 lg:p-6 flex-1 flex flex-col min-h-0">
            <!-- Header with Category Badge (Right aligned) - Same as cards without image -->
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="flex items-start gap-2 flex-1 min-w-0 pr-2">
                    <x-profile-avatar :user="$user_obj" size="xs" class="cursor-pointer hover:opacity-80 transition-opacity" onclick="event.stopPropagation(); window.location.href='{{ $userId ? route('users.show', $userId) : '#' }}'"/>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:!text-primary truncate cursor-pointer transition-colors" onclick="event.stopPropagation(); window.location.href='{{ $userId ? route('users.show', $userId) : '#' }}'">{{ $user }}</span>
                        @if($published_at)
                            <span class="text-[10px] sm:text-xs text-slate-400 dark:text-slate-500">{{ $published_at }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="{{ $colors['bg'] }} {{ $colors['text'] }} text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase whitespace-nowrap">{{ $categoryLabel }}</span>
                    <div class="relative">
                        <button onclick="event.stopPropagation(); toggleCardMenu('{{ $uniqueId }}')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </button>
                        <div id="menu-{{ $uniqueId }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden z-10">
                            @auth
                                @if(Auth::id() === $userId)
                                    <button onclick="event.stopPropagation(); deletePost({{ $id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">delete</span>
                                        <span>{{ __('buttons.delete') }}</span>
                                    </button>
                                @else
                                    <button onclick="event.stopPropagation(); reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                        <span>{{ __('buttons.report') }}</span>
                                    </button>
                                @endif
                            @else
                                <button onclick="event.stopPropagation(); reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                    <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                    <span>{{ __('buttons.report') }}</span>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 sm:mb-6 flex-1">
                <h4 class="text-base sm:text-lg font-bold leading-tight mb-1.5 sm:mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $title }}</h4>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-normal leading-relaxed line-clamp-2 sm:line-clamp-3">{{ $description }}</p>
            </div>
            <div class="flex items-center justify-between text-slate-400 mt-auto">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors" onclick="event.stopPropagation(); toggleLike({{ $id }}, this)">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px] {{ $isLiked ? 'filled text-red-500' : '' }}" style="{{ $isLiked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                        <span class="text-[11px] sm:text-xs font-bold like-count">{{ $likes }}</span>
                    </div>
                    <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">chat_bubble</span>
                        <span class="text-[11px] sm:text-xs font-bold">{{ $comments }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="hover:text-primary transition-colors p-1" onclick="event.stopPropagation(); shareTip({{ $id }}, '{{ addslashes($title) }}')">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
                    </button>
                    <button class="hover:text-primary transition-colors p-1 bookmark-btn" onclick="event.stopPropagation(); toggleBookmark({{ $id }}, this)" data-tip-id="{{ $id }}">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px] {{ $isBookmarked ? 'filled text-primary' : '' }}" style="{{ $isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Card without Image -->
        <div class="p-4 sm:p-5 lg:p-6 flex flex-col flex-1">
            <!-- Header with Category Badge (Right aligned) -->
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="flex items-start gap-2 flex-1 min-w-0 pr-2">
                    <x-profile-avatar :user="$user_obj" size="xs" class="cursor-pointer hover:opacity-80 transition-opacity" onclick="event.stopPropagation(); window.location.href='{{ $userId ? route('users.show', $userId) : '#' }}'"/>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:!text-primary truncate cursor-pointer transition-colors" onclick="event.stopPropagation(); window.location.href='{{ $userId ? route('users.show', $userId) : '#' }}'">{{ $user }}</span>
                        @if($published_at)
                            <span class="text-[10px] sm:text-xs text-slate-400 dark:text-slate-500">{{ $published_at }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="{{ $colors['bg'] }} {{ $colors['text'] }} text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase whitespace-nowrap">{{ $categoryLabel }}</span>
                    <div class="relative">
                        <button onclick="event.stopPropagation(); toggleCardMenu('{{ $uniqueId }}')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </button>
                        <div id="menu-{{ $uniqueId }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden z-10">
                            @auth
                                @if(Auth::id() === $userId)
                                    <button onclick="event.stopPropagation(); deletePost({{ $id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">delete</span>
                                        <span>{{ __('buttons.delete') }}</span>
                                    </button>
                                @else
                                    <button onclick="event.stopPropagation(); reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                        <span>{{ __('buttons.report') }}</span>
                                    </button>
                                @endif
                            @else
                                <button onclick="event.stopPropagation(); reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                    <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                    <span>{{ __('buttons.report') }}</span>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-4 flex-1">
                <h4 class="text-base sm:text-lg font-bold leading-tight mb-2 group-hover:text-primary transition-colors">{{ $title }}</h4>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-normal leading-relaxed">{{ $description }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between text-slate-400 mt-auto">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors" onclick="event.stopPropagation(); toggleLike({{ $id }}, this)">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px] {{ $isLiked ? 'filled text-red-500' : '' }}" style="{{ $isLiked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                        <span class="text-[11px] sm:text-xs font-bold like-count">{{ $likes }}</span>
                    </div>
                    <div class="flex items-center gap-1 hover:text-primary cursor-pointer transition-colors">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">chat_bubble</span>
                        <span class="text-[11px] sm:text-xs font-bold">{{ $comments }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <button class="hover:text-primary transition-colors p-1" onclick="event.stopPropagation(); shareTip({{ $id }}, '{{ addslashes($title) }}')">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
                    </button>
                    <button class="hover:text-primary transition-colors p-1 bookmark-btn" onclick="event.stopPropagation(); toggleBookmark({{ $id }}, this)" data-tip-id="{{ $id }}">
                        <span class="material-symbols-outlined text-[18px] sm:text-[20px] {{ $isBookmarked ? 'filled text-primary' : '' }}" style="{{ $isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
