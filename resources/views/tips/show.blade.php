@extends('layouts.app')

@section('title', $tip->title . ' - GreenBoard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-semibold">Back</span>
            </a>
        </div>

        <!-- Post Content -->
        <article class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg overflow-hidden mb-8">
            @php
                $categoryColors = [
                    'Consumption' => ['bg' => 'bg-purple-100/90', 'text' => 'text-purple-600'],
                    'Food' => ['bg' => 'bg-orange-100/90', 'text' => 'text-orange-600'],
                    'Energy' => ['bg' => 'bg-amber-100/90', 'text' => 'text-amber-600'],
                    'Transport' => ['bg' => 'bg-emerald-100/90', 'text' => 'text-emerald-600'],
                    'Home' => ['bg' => 'bg-blue-100/90', 'text' => 'text-blue-600'],
                    'Zero Waste' => ['bg' => 'bg-green-100/90', 'text' => 'text-green-600'],
                ];
                $colors = $categoryColors[$tip->category] ?? ['bg' => 'bg-primary/20', 'text' => 'text-primary'];
            @endphp

            <!-- Image (if exists) -->
            @if($tip->image)
                <div class="relative aspect-[16/9] w-full">
                    <img src="{{ $tip->image }}" alt="{{ $tip->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Content -->
            <div class="p-6 sm:p-8">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <img src="{{ $tip->user->photo ?? 'https://via.placeholder.com/50' }}" alt="{{ $tip->user->name }}" class="size-12 rounded-full object-cover flex-shrink-0">
                        <div class="flex flex-col min-w-0">
                            <span class="text-base font-bold text-slate-900 dark:text-slate-100">{{ $tip->user->name }}</span>
                            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $tip->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="{{ $colors['bg'] }} {{ $colors['text'] }} text-xs font-extrabold px-3 py-1.5 rounded-full uppercase whitespace-nowrap">
                            {{ $tip->category }}
                        </span>
                        <div class="relative">
                            <button onclick="toggleCardMenu('show-tip')" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <span class="material-symbols-outlined text-xl">more_vert</span>
                            </button>
                            <div id="menu-show-tip" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg shadow-xl overflow-hidden z-10">
                                @auth
                                    @if(Auth::id() === $tip->user_id)
                                        <button onclick="deletePost({{ $tip->id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                            <span class="material-symbols-outlined text-[16px] text-red-500">delete</span>
                                            <span>Eliminar</span>
                                        </button>
                                    @else
                                        <button onclick="reportPost('show-tip')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                            <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                            <span>Reportar</span>
                                        </button>
                                    @endif
                                @else
                                    <button onclick="reportPost('show-tip')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                        <span>Reportar</span>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 mb-4">
                    {{ $tip->title }}
                </h1>

                <!-- Description -->
                <div class="text-base text-slate-600 dark:text-slate-300 leading-relaxed mb-6 whitespace-pre-wrap">
                    {{ $tip->description }}
                </div>

                <!-- Interactions -->
                <div class="flex items-center gap-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <button class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors group">
                        <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">favorite</span>
                        <span class="text-sm font-semibold">{{ $tip->likes()->count() }}</span>
                    </button>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-xl">comment</span>
                        <span class="text-sm font-semibold">{{ $tip->comments()->count() }}</span>
                    </div>
                    <button class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">bookmark</span>
                    </button>
                    <button class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors ml-auto">
                        <span class="material-symbols-outlined text-xl">share</span>
                    </button>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg p-6 sm:p-8">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">
                Comments ({{ $tip->comments()->count() }})
            </h2>

            @auth
                <!-- Add Comment Form -->
                <form action="{{ route('comments.store', $tip) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex gap-3">
                        <img src="{{ Auth::user()->photo ?? 'https://via.placeholder.com/50' }}" alt="{{ Auth::user()->name }}" class="size-10 rounded-full object-cover flex-shrink-0">
                        <div class="flex-1">
                            <textarea 
                                name="content" 
                                rows="3" 
                                placeholder="Add a comment..."
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none"
                                required
                            ></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-6 py-2 bg-primary text-background-dark font-bold rounded-lg hover:brightness-105 transition-all text-sm">
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <!-- Login prompt -->
                <div class="mb-8 p-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-slate-600 dark:text-slate-400">
                        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Log in</a> to leave a comment
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-6">
                @forelse($comments as $comment)
                    <div class="flex gap-3" id="comment-{{ $comment->id }}">
                        <!-- Avatar -->
                        <img src="{{ $comment->user->photo ?? 'https://via.placeholder.com/50' }}" alt="{{ $comment->user->name }}" class="size-10 rounded-full object-cover flex-shrink-0">
                        
                        <!-- Comment Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $comment->user->name }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Comment Text -->
                            <p class="text-sm text-slate-700 dark:text-slate-300 mb-2 break-words">
                                {{ $comment->content }}
                            </p>

                            <!-- Actions -->
                            <div class="flex items-center gap-4">
                                <button class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group">
                                    <span class="material-symbols-outlined text-sm">favorite</span>
                                    <span class="font-semibold">Like</span>
                                </button>
                                @auth
                                    <button 
                                        onclick="toggleReplyForm({{ $comment->id }})"
                                        class="text-xs text-slate-500 hover:text-primary transition-colors font-semibold"
                                    >
                                        Reply
                                    </button>
                                @endauth
                            </div>

                            @auth
                                <!-- Reply Form (Hidden by default) -->
                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                                    <form action="{{ route('comments.reply', $comment) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <textarea 
                                            name="content" 
                                            rows="2" 
                                            placeholder="Write a reply..."
                                            class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none"
                                            required
                                        ></textarea>
                                        <div class="flex flex-col gap-1">
                                            <button type="submit" class="px-4 py-1 bg-primary text-background-dark font-bold rounded-lg hover:brightness-105 transition-all text-xs">
                                                Reply
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                class="px-4 py-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition-all text-xs"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                                <div class="mt-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-4">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex gap-3">
                                            <img src="{{ $reply->user->photo ?? 'https://via.placeholder.com/50' }}" alt="{{ $reply->user->name }}" class="size-8 rounded-full object-cover flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                    <span class="font-bold text-slate-900 dark:text-slate-100 text-sm">{{ $reply->user->name }}</span>
                                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-slate-700 dark:text-slate-300 mb-2 break-words">
                                                    {{ $reply->content }}
                                                </p>
                                                <button class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group">
                                                    <span class="material-symbols-outlined text-sm">favorite</span>
                                                    <span class="font-semibold">Like</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-5xl mb-3 block">chat_bubble</span>
                        <p class="text-slate-500 dark:text-slate-400">No comments yet. Be the first to comment!</p>
                    </div>
                @endforelse
            </div>

            <!-- Load More Comments (if needed) -->
            @if($comments->hasMorePages())
                <div class="flex justify-center mt-8">
                    <a href="{{ $comments->nextPageUrl() }}" class="px-6 py-2 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all text-sm">
                        Load More Comments
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Reply Forms -->
    <script>
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm.classList.contains('hidden')) {
                replyForm.classList.remove('hidden');
            } else {
                replyForm.classList.add('hidden');
            }
        }
    </script>
@endsection
