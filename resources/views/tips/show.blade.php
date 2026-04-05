@extends('layouts.app')

@section('title', $tip->title . ' - GreenBoard')

@section('content')
    <div class="w-full px-2 sm:px-4 md:px-6">
        <div class="mx-auto max-w-md">
            <div class="mb-6">
                @auth
                    <a href="{{route('dashboard')}}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span class="font-semibold">{{ __('buttons.back') }}</span>
                    </a>
                @else
                    <a href="{{route('home')}}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span class="font-semibold">{{ __('buttons.back') }}</span>
                    </a>
                @endauth
            </div>

            <article class="bg-white dark:bg-custom-dark-bg rounded-2xl border border-gray-200 dark:border-custom-gray-border shadow-lg overflow-hidden mb-8">
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

                function formatCommentCount($count, $locale = 'en') {
                    if ($locale === 'es') {
                        return $count === 1 ? 'Comentario' : 'Comentarios';
                    } elseif ($locale === 'de') {
                        return $count === 1 ? 'Kommentar' : 'Kommentare';
                    } else {
                        return $count === 1 ? 'Comment' : 'Comments';
                    }
                }
            @endphp

            @if($tip->image)
                <div class="relative aspect-[16/9] w-full">
                    <img src="{{ $tip->image }}" alt="{{ $tip->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <a href="{{ route('users.show', $tip->user->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <x-profile-avatar :user="$tip->user" size="xs" class="hover:opacity-80 transition-opacity" onclick="event.stopPropagation()" />
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-primary transition-colors">{{ $tip->user->name }}</span>
                                <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">{{ $tip->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @auth
                            @if(Auth::id() !== $tip->user_id)
                                <button 
                                    id="follow-btn-{{ $tip->user_id }}" 
                                    onclick="toggleFollow({{ $tip->user_id }})" 
                                    class="follow-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all
                                    {{ Auth::user()->isFollowing($tip->user_id) ? 'bg-gray-200 dark:bg-custom-dark-input text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-custom-dark-button' : 'bg-primary text-background-dark hover:brightness-105' }}">
                                    <span class="material-symbols-outlined text-[18px]">{{ Auth::user()->isFollowing($tip->user_id) ? 'person_check' : 'person_add' }}</span>
                                    <span class="follow-text hidden sm:inline">{{ Auth::user()->isFollowing($tip->user_id) ? __('buttons.unfollow') : __('buttons.follow') }}</span>
                                </button>
                            @endif
                        @endauth
                        <div class="relative">
                            <button onclick="toggleCardMenu('show-tip')" class="px-1 py-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                            </button>
                            <div id="menu-show-tip" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-custom-dark-bg border border-gray-200 dark:border-custom-gray-border rounded-lg shadow-xl overflow-hidden z-10">
                                @auth
                                    @if(Auth::id() === $tip->user_id)
                                        <button onclick="deletePost({{ $tip->id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                            <span class="material-symbols-outlined text-[16px] text-red-500">delete</span>
                                            <span>{{ __('buttons.delete') }}</span>
                                        </button>
                                    @else
                                        <button onclick="openReportModal({{ $tip->id }}, 'post')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                            <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                            <span>{{ __('buttons.report') }}</span>
                                        </button>
                                    @endif
                                @else
                                    <button onclick="openReportModal({{ $tip->id }}, 'post')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                        <span class="material-symbols-outlined text-[16px] text-red-500">flag</span>
                                        <span>{{ __('buttons.report') }}</span>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <h1 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-gray-100 mb-4">
                    {{ $tip->title }}
                </h1>

                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    {{ $tip->description }}
                </div>

                <div class="flex items-center justify-between pt-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        @auth
                            <button onclick="toggleLike({{ $tip->id }}, this)" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-red-500 transition-colors group cursor-pointer">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px] group-hover:scale-110 transition-transform {{ Auth::user()->hasLiked($tip) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLiked($tip) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                <span class="text-[11px] sm:text-xs font-semibold like-count">{{ $tip->likes()->count() }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-red-500 transition-colors group">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px] group-hover:scale-110 transition-transform">favorite</span>
                                <span class="text-[11px] sm:text-xs font-semibold">{{ $tip->likes()->count() }}</span>
                            </a>
                        @endauth
                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">chat_bubble</span>
                            <span class="text-[11px] sm:text-xs font-semibold">{{ $tip->comments()->count() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <button onclick="shareTip({{ $tip->id }}, '{{ addslashes($tip->title) }}')" class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors p-1">
                            <span class="material-symbols-outlined text-[18px] sm:text-[20px]">share</span>
                        </button>
                        @auth
                            <button class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors p-1 bookmark-btn" onclick="toggleBookmark({{ $tip->id }}, this)" data-tip-id="{{ $tip->id }}">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px] {{ Auth::user()->hasBookmarked($tip) ? 'filled text-primary' : '' }}" style="{{ Auth::user()->hasBookmarked($tip) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">bookmark</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary transition-colors p-1">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">bookmark</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </article>

        <div class="p-4 sm:p-6 md:p-8 overflow-visible">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                {{ $tip->comments()->count() }} {{ formatCommentCount($tip->comments()->count(), app()->getLocale()) }}
            </h2>

            @auth
                <form action="{{ route('comments.store', $tip) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex gap-3 flex-row">
                        <x-profile-avatar :user="Auth::user()" size="sm" class="flex-shrink-0" />
                        <div class="flex-1 min-w-0">
                            <textarea 
                                name="content" 
                                rows="3" 
                                placeholder="{{ __('content.add_comment') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-custom-dark-input border border-gray-200 dark:border-custom-gray-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none text-gray-900 dark:text-gray-100"
                                required
                            ></textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="px-6 py-2 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all text-xs">
                                    {{ __('content.post_comment') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="mb-8 p-4 bg-gray-50 dark:bg-custom-dark-bg border border-gray-200 dark:border-custom-gray-border rounded-xl text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">{{ __('nav.login') }}</a> {{ __('content.login_to_comment') }}
                    </p>
                </div>
            @endauth

            <div class="space-y-6">
                @forelse($comments as $comment)
                    <div class="flex gap-3" id="comment-{{ $comment->id }}">
                        <x-profile-avatar :user="$comment->user" size="sm" />
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 break-words w-full min-w-0">
                                {{ $comment->content }}
                            </p>

                            <div class="flex items-center gap-4">
                                @auth
                                    <button onclick="toggleCommentLike({{ $comment->id }}, this)" class="flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition-colors group cursor-pointer">
                                        <span class="material-symbols-outlined text-sm {{ Auth::user()->hasLikedComment($comment) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLikedComment($comment) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                        <span class="font-semibold comment-like-count">{{ $comment->likes()->count() > 0 ? $comment->likes()->count() : __('comments.like') }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition-colors group">
                                        <span class="material-symbols-outlined text-sm">favorite</span>
                                        <span class="font-semibold">{{ $comment->likes()->count() > 0 ? $comment->likes()->count() : __('comments.like') }}</span>
                                    </a>
                                @endauth
                                @auth
                                    <button 
                                        onclick="toggleReplyForm({{ $comment->id }})"
                                        class="text-xs text-gray-500 hover:text-primary transition-colors font-semibold"
                                    >
                                        {{ __('comments.reply') }}
                                    </button>
                                @endauth
                                @auth
                                    <div class="relative ml-auto">
                                        <button onclick="toggleCommentMenu({{ $comment->id }})" class="text-xs text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors font-semibold">
                                            <span class="material-symbols-outlined text-sm">more_vert</span>
                                        </button>
                                        <div id="comment-menu-{{ $comment->id }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-custom-dark-bg border border-gray-200 dark:border-custom-gray-border rounded-lg shadow-xl overflow-hidden z-10">
                                            @if(Auth::id() === $comment->user_id || Auth::user()->is_admin)
                                                <button onclick="deleteComment({{ $comment->id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                                    <span class="material-symbols-outlined text-[14px] text-red-500">delete</span>
                                                    <span>{{ __('comments.delete') }}</span>
                                                </button>
                                            @endif
                                            @if(Auth::id() !== $comment->user_id)
                                                <button onclick="openReportModal({{ $comment->id }}, 'comment')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                                    <span class="material-symbols-outlined text-[14px] text-yellow-500">flag</span>
                                                    <span>{{ __('comments.report') }}</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endauth
                            </div>

                            @auth
                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                                    <form action="{{ route('comments.reply', $comment) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <textarea 
                                            name="content" 
                                            rows="3" 
                                            placeholder="{{ __('comments.reply_placeholder') }}"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-custom-dark-input border border-gray-200 dark:border-custom-gray-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm resize-none outline-none text-gray-900 dark:text-gray-100"
                                            required
                                        ></textarea>
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-2 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 transition-all text-xs">
                                                {{ __('comments.reply') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            @if($comment->replies->count() > 0)
                                <div class="mt-4 pl-4 border-l-2 border-gray-200 dark:border-custom-gray-border space-y-4">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex gap-3">
                                            <x-profile-avatar :user="$reply->user" size="sm" />
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                    <span class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $reply->user->name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 break-words w-full min-w-0">
                                                    {{ $reply->content }}
                                                </p>
                                                <div class="flex items-center gap-4">
                                                    @auth
                                                            <button onclick="toggleCommentLike({{ $reply->id }}, this)" class="flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition-colors group cursor-pointer">
                                                                <span class="material-symbols-outlined text-sm {{ Auth::user()->hasLikedComment($reply) ? 'filled text-red-500' : '' }}" style="{{ Auth::user()->hasLikedComment($reply) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                                                <span class="font-semibold comment-like-count">{{ $reply->likes()->count() > 0 ? $reply->likes()->count() : __('comments.like') }}</span>
                                                            </button>
                                                        @else
                                                            <a href="{{ route('login') }}" class="flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition-colors group">
                                                                <span class="material-symbols-outlined text-sm">favorite</span>
                                                                <span class="font-semibold">{{ $reply->likes()->count() > 0 ? $reply->likes()->count() : __('comments.like') }}</span>
                                                            </a>
                                                        @endauth
                                                    @auth
                                                        <div class="relative ml-auto">
                                                            <button onclick="toggleCommentMenu({{ $reply->id }})" class="text-xs text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors font-semibold">
                                                                <span class="material-symbols-outlined text-sm">more_vert</span>
                                                            </button>
                                                            <div id="comment-menu-{{ $reply->id }}" class="hidden absolute right-0 mt-1 w-40 bg-white dark:bg-custom-dark-bg border border-gray-200 dark:border-custom-gray-border rounded-lg shadow-xl overflow-hidden z-10">
                                                                @if(Auth::id() === $reply->user_id || Auth::user()->is_admin)
                                                                    <button onclick="deleteComment({{ $reply->id }})" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                                                        <span class="material-symbols-outlined text-[14px] text-red-500">delete</span>
                                                                        <span>{{ __('comments.delete') }}</span>
                                                                    </button>
                                                                @endif
                                                                @if(Auth::id() !== $reply->user_id)
                                                                    <button onclick="openReportModal({{ $reply->id }}, 'comment')" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-colors text-left">
                                                                        <span class="material-symbols-outlined text-[14px] text-yellow-500">flag</span>
                                                                        <span>{{ __('comments.report') }}</span>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 dark:text-custom-dark-button text-5xl mb-3 block">chat_bubble</span>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('comments.no_comments_yet') }}</p>
                    </div>
                @endforelse
            </div>

            @if($comments->hasMorePages())
                <div class="flex justify-center mt-8">
                    <a href="{{ $comments->nextPageUrl() }}" class="px-6 py-2 border-2 border-gray-300 dark:border-custom-gray-border text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-custom-dark-button transition-all text-sm">
                        Load More Comments
                    </a>
                </div>
            @endif
        </div>
        </div>
    </div>

    <!-- Confirm Delete Modal Component -->
    <x-confirm-modal 
        id="delete-comment-modal" 
        title="{{ __('comments.confirm_delete_title') }}"
        message="{{ __('comments.confirm_delete_message') }}"
        confirmText="{{ __('comments.confirm_delete_button') }}"
        cancelText="{{ __('comments.cancel') }}"
        onConfirm="performDeleteComment"
        isDangerous="true"
    />

    <!-- JavaScript for Reply Forms -->
    <script>
        let pendingCommentId = null;
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm.classList.contains('hidden')) {
                replyForm.classList.remove('hidden');
            } else {
                replyForm.classList.add('hidden');
            }
        }

        function toggleCommentMenu(commentId) {
            const menu = document.getElementById(`comment-menu-${commentId}`);
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menus = document.querySelectorAll('[id^="comment-menu-"]');
            menus.forEach(menu => {
                if (!menu.contains(event.target) && !event.target.closest('button[onclick*="toggleCommentMenu"]')) {
                    menu.classList.add('hidden');
                }
            });
        });

        function deleteComment(commentId) {
            pendingCommentId = commentId;
            showConfirmModal('delete-comment-modal');
        }

        function performDeleteComment() {
            if (!pendingCommentId) return;
            const commentId = pendingCommentId;
            pendingCommentId = null;

            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Find the comment container (could be parent or reply)
                    const commentElement = document.getElementById(`comment-${commentId}`);
                    if (commentElement) {
                        // Animate removal
                        commentElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        commentElement.style.opacity = '0';
                        commentElement.style.transform = 'scale(0.95)';

                        setTimeout(() => {
                            commentElement.remove();
                        }, 300);
                    }

                    // Reload page immediately
                    location.reload();
                } else {
                    // Show error notification
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                    errorMsg.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">error</span>
                            <span>Error al eliminar el comentario</span>
                        </div>
                    `;
                    document.body.appendChild(errorMsg);

                    // Remove notification after 3 seconds
                    setTimeout(() => {
                        errorMsg.style.transition = 'opacity 0.3s ease-out';
                        errorMsg.style.opacity = '0';
                        setTimeout(() => errorMsg.remove(), 300);
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                errorMsg.innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <span>Error al procesar la solicitud</span>
                    </div>
                `;
                document.body.appendChild(errorMsg);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    errorMsg.style.transition = 'opacity 0.3s ease-out';
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.remove(), 300);
                }, 3000);
            });
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
