@extends('layouts.app')

@section('title', $tip->title . ' - GreenBoard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{route('dashboard')}}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-semibold">Back</span>
            </a>
        </div>

        <!-- Post Content -->
        <article class="bg-white dark:bg-custom-dark-button rounded-2xl border border-slate-200 dark:border-transparent shadow-lg overflow-hidden mb-8">
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
                        <a href="{{ route('users.show', $tip->user->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <img src="{{ $tip->user->photo ?? 'https://via.placeholder.com/50' }}" alt="{{ $tip->user->name }}" class="size-12 rounded-full object-cover flex-shrink-0 hover:opacity-80 transition-opacity">
                            <div class="flex flex-col min-w-0">
                                <span class="text-base font-bold text-slate-900 dark:text-slate-100 hover:text-primary transition-colors">{{ $tip->user->name }}</span>
                                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $tip->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                        @auth
                            @if(Auth::id() !== $tip->user_id)
                                <button 
                                    id="follow-btn-{{ $tip->user_id }}" 
                                    onclick="toggleFollow({{ $tip->user_id }})" 
                                    class="follow-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all flex-shrink-0
                                    {{ Auth::user()->isFollowing($tip->user_id) ? 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' : 'bg-primary text-background-dark hover:brightness-105' }}">
                                    <span class="material-symbols-outlined text-base">{{ Auth::user()->isFollowing($tip->user_id) ? 'person_check' : 'person_add' }}</span>
                                    <span class="follow-text">{{ Auth::user()->isFollowing($tip->user_id) ? 'Following' : 'Follow' }}</span>
                                </button>
                            @endif
                        @endauth
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
                <div class="text-base text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    {{ $tip->description }}
                </div>

                <!-- Interactions -->
                <div class="flex items-center justify-between pt-4">
                    <div class="flex items-center gap-4 sm:gap-6">
                        @auth
                            <button onclick="toggleLike({{ $tip->id }}, this)" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors group cursor-pointer">
                                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform {{ Auth::user()->hasLiked($tip) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLiked($tip) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                <span class="text-sm font-semibold like-count">{{ $tip->likes()->count() }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors group">
                                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">favorite</span>
                                <span class="text-sm font-semibold">{{ $tip->likes()->count() }}</span>
                            </a>
                        @endauth
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined text-xl">chat_bubble</span>
                            <span class="text-sm font-semibold">{{ $tip->comments()->count() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <button onclick="shareTip({{ $tip->id }}, '{{ addslashes($tip->title) }}')" class="text-slate-600 dark:text-slate-400 hover:text-primary transition-colors p-1">
                            <span class="material-symbols-outlined text-xl">share</span>
                        </button>
                        @auth
                            <button class="text-slate-600 dark:text-slate-400 hover:text-primary transition-colors p-1 bookmark-btn" onclick="toggleBookmark({{ $tip->id }}, this)" data-tip-id="{{ $tip->id }}">
                                <span class="material-symbols-outlined text-xl {{ Auth::user()->hasBookmarked($tip) ? 'filled text-primary' : '' }}" style="{{ Auth::user()->hasBookmarked($tip) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 hover:text-primary transition-colors p-1">
                                <span class="material-symbols-outlined text-xl">bookmark</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="p-6 sm:p-8">
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
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-button border border-slate-200 dark:border-slate-700 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none"
                                required
                            ></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-6 py-2 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all text-sm">
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
                                @auth
                                    <button onclick="toggleCommentLike({{ $comment->id }}, this)" class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group cursor-pointer">
                                        <span class="material-symbols-outlined text-sm {{ Auth::user()->hasLikedComment($comment) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLikedComment($comment) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                        <span class="font-semibold comment-like-count">{{ $comment->likes()->count() > 0 ? $comment->likes()->count() : 'Like' }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group">
                                        <span class="material-symbols-outlined text-sm">favorite</span>
                                        <span class="font-semibold">{{ $comment->likes()->count() > 0 ? $comment->likes()->count() : 'Like' }}</span>
                                    </a>
                                @endauth
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
                                            class="flex-1 px-3 py-2 bg-slate-50 dark:bg-custom-dark-button border border-slate-200 dark:border-slate-700 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none"
                                            required
                                        ></textarea>
                                        <div class="flex flex-col gap-2">
                                            <button type="submit" class="px-4 py-1 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all text-xs">
                                                Reply
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                class="px-4 py-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-full hover:bg-slate-300 dark:hover:bg-slate-600 transition-all text-xs"
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
                                                @auth
                                                    <button onclick="toggleCommentLike({{ $reply->id }}, this)" class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group cursor-pointer">
                                                        <span class="material-symbols-outlined text-sm {{ Auth::user()->hasLikedComment($reply) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLikedComment($reply) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                                        <span class="font-semibold comment-like-count">{{ $reply->likes()->count() > 0 ? $reply->likes()->count() : 'Like' }}</span>
                                                    </button>
                                                @else
                                                    <a href="{{ route('login') }}" class="flex items-center gap-1 text-xs text-slate-500 hover:text-red-500 transition-colors group">
                                                        <span class="material-symbols-outlined text-sm">favorite</span>
                                                        <span class="font-semibold">{{ $reply->likes()->count() > 0 ? $reply->likes()->count() : 'Like' }}</span>
                                                    </a>
                                                @endauth
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

        // Toggle Follow/Unfollow
        function toggleFollow(userId) {
            const button = document.getElementById(`follow-btn-${userId}`);
            const icon = button.querySelector('.material-symbols-outlined');
            const text = button.querySelector('.follow-text');

            fetch(`/users/${userId}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.following) {
                        button.classList.remove('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        button.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        icon.textContent = 'person_check';
                        text.textContent = 'Following';
                    } else {
                        button.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        button.classList.add('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        icon.textContent = 'person_add';
                        text.textContent = 'Follow';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud. Por favor intenta de nuevo.');
            });
        }

        // Toggle like on comments
        function toggleCommentLike(commentId, element) {
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the heart icon
                    const heartIcon = element.querySelector('.material-symbols-outlined');
                    const likeCount = element.querySelector('.comment-like-count');

                    if (data.liked) {
                        // Add filled style
                        heartIcon.classList.add('filled', 'text-red-500');
                        heartIcon.style.fontVariationSettings = "'FILL' 1";
                    } else {
                        // Remove filled style
                        heartIcon.classList.remove('filled', 'text-red-500');
                        heartIcon.style.fontVariationSettings = "'FILL' 0";
                    }

                    // Update count - show number or "Like" text
                    if (data.likes_count > 0) {
                        likeCount.textContent = data.likes_count;
                    } else {
                        likeCount.textContent = 'Like';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el like. Por favor intenta de nuevo.');
            });
        }
    </script>
@endsection
