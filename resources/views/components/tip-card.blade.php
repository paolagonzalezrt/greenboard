@props(['category', 'user', 'title', 'description', 'likes', 'comments', 'image' => null, 'avatar'])

@php
    $categoryColors = [
        'Consumption' => ['bg' => 'bg-purple-100/90', 'text' => 'text-purple-600'],
        'Food' => ['bg' => 'bg-orange-100/90', 'text' => 'text-orange-600'],
        'Energy' => ['bg' => 'bg-amber-100/90', 'text' => 'text-amber-600'],
        'Transport' => ['bg' => 'bg-emerald-100/90', 'text' => 'text-emerald-600'],
        'Home' => ['bg' => 'bg-blue-100/90', 'text' => 'text-blue-600'],
        'Zero Waste' => ['bg' => 'bg-green-100/90', 'text' => 'text-green-600'],
    ];

    $colors = $categoryColors[$category] ?? ['bg' => 'bg-primary/20', 'text' => 'text-primary'];
    $uniqueId = uniqid('card-');
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl sm:rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-md hover:shadow-xl transition-all group flex flex-col">
    @if($image)
        <!-- Card with Image -->
        <div class="relative aspect-square">
            <img class="w-full h-full object-cover" data-alt="{{ $title }}" src="{{ $image }}"/>
        </div>
        <div class="p-4 sm:p-5 lg:p-6 flex-1 flex flex-col">
            <!-- Header with Category Badge (Right aligned) - Same as cards without image -->
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="flex items-center gap-2 flex-1 min-w-0 pr-2">
                    <img alt="{{ $user }}" class="size-6 sm:size-7 rounded-full bg-slate-100 object-cover flex-shrink-0" src="{{ $avatar }}"/>
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 truncate">{{ $user }}</span>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="{{ $colors['bg'] }} {{ $colors['text'] }} text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase whitespace-nowrap">{{ $category }}</span>
                    <div class="relative">
                        <button onclick="toggleCardMenu('{{ $uniqueId }}')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </button>
                        <div id="menu-{{ $uniqueId }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden z-10">
                            <button onclick="reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                <span>Reportar</span>
                            </button>
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
    @else
        <!-- Card without Image -->
        <div class="p-4 sm:p-5 lg:p-6 flex flex-col">
            <!-- Header with Category Badge (Right aligned) -->
            <div class="flex items-start justify-between mb-3 sm:mb-4">
                <div class="flex items-center gap-2 flex-1 min-w-0 pr-2">
                    <img alt="{{ $user }}" class="size-6 sm:size-7 rounded-full bg-slate-100 object-cover flex-shrink-0" src="{{ $avatar }}"/>
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 truncate">{{ $user }}</span>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <span class="{{ $colors['bg'] }} {{ $colors['text'] }} text-[9px] sm:text-[10px] font-extrabold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full uppercase whitespace-nowrap">{{ $category }}</span>
                    <div class="relative">
                        <button onclick="toggleCardMenu('{{ $uniqueId }}')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </button>
                        <div id="menu-{{ $uniqueId }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden z-10">
                            <button onclick="reportPost('{{ $uniqueId }}')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                <span>Reportar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-4">
                <h4 class="text-base sm:text-lg font-bold leading-tight mb-2 group-hover:text-primary transition-colors">{{ $title }}</h4>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-normal leading-relaxed">{{ $description }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between text-slate-400">
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
    @endif
</div>

<script>
function toggleCardMenu(cardId) {
    const menu = document.getElementById('menu-' + cardId);
    const allMenus = document.querySelectorAll('[id^="menu-"]');

    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== 'menu-' + cardId) {
            m.classList.add('hidden');
        }
    });

    // Toggle current menu
    menu.classList.toggle('hidden');

    // Prevent event from bubbling
    event.stopPropagation();
}

function reportPost(cardId) {
    // Close the menu
    const menu = document.getElementById('menu-' + cardId);
    menu.classList.add('hidden');

    // Show confirmation (you can replace this with a modal)
    alert('Post reportado. Gracias por ayudarnos a mantener la comunidad segura.');

    // Here you would typically send a request to your backend
    // Example: fetch('/report-post', { method: 'POST', body: JSON.stringify({ cardId }) })
}

// Close all card menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleCardMenu"]')) {
        const allMenus = document.querySelectorAll('[id^="menu-"]');
        allMenus.forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
</script>
