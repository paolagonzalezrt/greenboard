@props(['category', 'user', 'title', 'description', 'likes', 'comments', 'image', 'avatar'])

<div class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 hover:border-primary/50 transition-all shadow-sm hover:shadow-xl flex flex-col">
    <div class="aspect-[4/3] overflow-hidden relative">
        <img alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $image }}"/>
        <div class="absolute top-3 left-3 bg-background-dark/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
            {{ $category }}
        </div>
    </div>
    <div class="p-6 flex-1 flex flex-col">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden border border-primary/20">
                <img alt="avatar" class="w-full h-full object-cover" src="{{ $avatar }}"/>
            </div>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $user }}</span>
        </div>
        <h3 class="font-bold text-lg mb-2 line-clamp-1">{{ $title }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">{{ $description }}</p>
        <div class="mt-auto flex items-center gap-4 text-slate-500">
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-rose-500 text-lg fill-1">favorite</span>
                <span class="text-xs font-bold">{{ $likes }}</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-lg">chat_bubble</span>
                <span class="text-xs font-bold">{{ $comments }}</span>
            </div>
        </div>
    </div>
</div>