@extends('layouts.app')

@section('title', 'Following - GreenBoard')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-8 sm:mb-10 lg:mb-12 w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-2">{{ __('content.following_title') }}</h1>
        <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
            {{ __('content.following_desc') }}
        </p>

        <!-- Followers/Following Stats -->
        <div class="flex justify-center gap-6 mt-6">
            <button onclick="showFollowersList()" class="flex flex-col items-center hover:opacity-80 transition-opacity">
                <span class="text-2xl font-bold text-slate-900 dark:text-slate-100" id="followers-count">{{ Auth::user()->followers()->count() }}</span>
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('content.followers_label') }}</span>
            </button>
            <button onclick="showFollowingList()" class="flex flex-col items-center hover:opacity-80 transition-opacity">
                <span class="text-2xl font-bold text-slate-900 dark:text-slate-100" id="following-count">{{ Auth::user()->following()->count() }}</span>
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('content.following_label') }}</span>
            </button>
        </div>
    </div>

    <!-- Posts Section -->
    <section class="w-full mb-12 sm:mb-16">
        

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
            @forelse($tips as $tip)
                <x-tip-card 
                    :id="$tip['id']"
                    :userId="$tip['user_id']"
                    :category="$tip['category']"
                    :user="$tip['user']"
                    :user_obj="$tip['user_obj']"
                    :title="$tip['title']"
                    :description="$tip['description']"
                    :likes="$tip['likes']"
                    :comments="$tip['comments']"
                    :image="$tip['image']"
                    :published_at="$tip['published_at']"
                    :isLiked="$tip['is_liked']"
                    :isBookmarked="$tip['is_bookmarked']"
                />
            @empty
                <div class="col-span-full text-center py-16">
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-6xl mb-4 block">group</span>
                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">{{ __('content.no_posts_yet') }}</h3>
                    <p class="text-slate-500 dark:text-slate-500">{{ __('content.start_following') }}</p>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-primary text-background-dark font-bold rounded-xl hover:brightness-105 transition-all">
                        {{ __('content.explore_posts') }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(count($tips) > 0)
            <div class="mt-8 sm:mt-10 lg:mt-12">
                <x-pagination-per-page />
                {{ $tips->links() }}
            </div>
        @endif
    </section>

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-full shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">{{ __('content.create_post') }}</span>
    </a>

    <!-- Modal for Followers/Following List -->
    <div id="users-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4" onclick="closeModal(event)">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-8 pt-6 pb-2">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100" id="modal-title">Followers</h3>
                <button onclick="closeModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-4 pt-0 pb-5" id="users-list">
                <!-- Users will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Modal for Remove Follower Confirmation -->
    <div 
        id="remove-follower-modal" 
        class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center overflow-auto"
        onclick="if (event.target.id === 'remove-follower-modal') closeRemoveFollowerModal()"
    >
        <div 
            class="bg-white dark:bg-custom-dark-bg rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-4 p-5 sm:p-6 transform transition-all max-h-[90vh] flex flex-col border border-transparent dark:border-custom-gray-border"
            onclick="event.stopPropagation()"
        >
            <div class="flex flex-col items-center my-2">
                <span class="material-symbols-outlined text-red-500 text-4xl mb-2">
                    delete_outline
                </span>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center">
                    {{ __('users.remove_follower_title') ?? 'Remove Follower' }}
                </h3>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed text-center">
                {{ __('users.remove_follower_message') ?? 'Are you sure you want to remove this follower?' }}
            </p>

            <div class="mb-2 flex gap-2 justify-center">
                <button 
                    type="button"
                    onclick="closeRemoveFollowerModal()"
                    class="px-6 py-2 bg-gray-100 dark:bg-custom-dark-input text-gray-700 dark:text-gray-200 font-bold text-sm rounded-full hover:bg-gray-200 dark:hover:bg-custom-dark-button transition-colors cursor-pointer"
                >
                    {{ __('buttons.cancel') ?? 'Cancel' }}
                </button>

                <button 
                    type="button"
                    onclick="confirmRemoveFollower()"
                    class="px-6 py-2 bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20 text-white font-bold text-sm rounded-full transition-colors cursor-pointer"
                >
                    {{ __('buttons.remove') ?? 'Remove' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Unfollow Confirmation -->
    <div 
        id="unfollow-modal" 
        class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center overflow-auto"
        onclick="if (event.target.id === 'unfollow-modal') closeUnfollowModal()"
    >
        <div 
            class="bg-white dark:bg-custom-dark-bg rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-4 p-5 sm:p-6 transform transition-all max-h-[90vh] flex flex-col border border-transparent dark:border-custom-gray-border"
            onclick="event.stopPropagation()"
        >
            <div class="flex flex-col items-center my-2">
                <span class="material-symbols-outlined text-red-500 text-4xl mb-2">
                    person_remove
                </span>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center">
                    {{ __('users.unfollow_title') ?? 'Unfollow User' }}
                </h3>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed text-center">
                {{ __('users.unfollow_message') ?? 'Are you sure you want to unfollow this user?' }}
            </p>

            <div class="mb-2 flex gap-2 justify-center">
                <button 
                    type="button"
                    onclick="closeUnfollowModal()"
                    class="px-6 py-2 bg-gray-100 dark:bg-custom-dark-input text-gray-700 dark:text-gray-200 font-bold text-sm rounded-full hover:bg-gray-200 dark:hover:bg-custom-dark-button transition-colors cursor-pointer"
                >
                    {{ __('buttons.cancel') ?? 'Cancel' }}
                </button>

                <button 
                    type="button"
                    onclick="confirmUnfollow()"
                    class="px-6 py-2 bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20 text-white font-bold text-sm rounded-full transition-colors cursor-pointer"
                >
                    {{ __('buttons.unfollow') ?? 'Unfollow' }}
                </button>
            </div>
        </div>
    </div>

    <script>
        const currentUserId = {{ Auth::id() }};
        let followerToRemove = null;
        let userToUnfollow = null;
        let unfollowButton = null;

        // Show followers list
        function showFollowersList() {
            document.getElementById('modal-title').textContent = 'Followers';
            document.getElementById('users-modal').classList.remove('hidden');
            loadFollowers();
        }

        // Show following list
        function showFollowingList() {
            document.getElementById('modal-title').textContent = 'Following';
            document.getElementById('users-modal').classList.remove('hidden');
            loadFollowing();
        }

        // Load followers
        function loadFollowers() {
            const usersList = document.getElementById('users-list');
            usersList.innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 animate-spin">progress_activity</span></div>';

            fetch(`/users/${currentUserId}/followers`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.followers.length === 0) {
                        usersList.innerHTML = `
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">group</span>
                                <p class="text-slate-500 dark:text-slate-400">No followers yet</p>
                            </div>
                        `;
                    } else {
                        usersList.innerHTML = data.followers.map(user => {
                            const initial = user.name.charAt(0).toUpperCase();
                            const hasPhoto = user.has_photo && user.avatar;
                            const avatarHtml = hasPhoto
                                ? `<img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">`
                                : `<div class="${user.avatar_color || 'bg-primary'} size-10 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white"><span class="text-sm">${initial}</span></div>`;

                            return `
                            <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                    ${avatarHtml}
                                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">${user.name}</span>
                                </div>
                                <button 
                                    onclick="showRemoveFollowerModal(${user.id})" 
                                    class="px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors flex-shrink-0">
                                    Remove
                                </button>
                            </div>
                        `;
                        }).join('');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                usersList.innerHTML = '<div class="text-center py-8 text-red-500">Error al cargar seguidores</div>';
            });
        }

        // Load following
        function loadFollowing() {
            const usersList = document.getElementById('users-list');
            usersList.innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 animate-spin">progress_activity</span></div>';

            fetch(`/users/${currentUserId}/following`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.following.length === 0) {
                        usersList.innerHTML = `
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">group</span>
                                <p class="text-slate-500 dark:text-slate-400">Not following anyone yet</p>
                            </div>
                        `;
                    } else {
                        usersList.innerHTML = data.following.map(user => {
                            const isSelf = user.id === currentUserId;
                            const initial = user.name.charAt(0).toUpperCase();
                            const hasPhoto = user.has_photo && user.avatar;
                            const avatarHtml = hasPhoto
                                ? `<img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">`
                                : `<div class="${user.avatar_color || 'bg-primary'} size-10 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white"><span class="text-sm">${initial}</span></div>`;

                            let actionButton = '';

                            if (!isSelf && currentUserId) {
                                // Mostrar Follow/Unfollow basado en el estado actual
                                const isFollowing = user.is_following;
                                actionButton = `
                                    <button 
                                        onclick="toggleFollowInModal(${user.id}, this)" 
                                        class="follow-modal-btn px-3 py-1.5 text-xs font-bold rounded-full transition-colors flex-shrink-0 
                                        ${isFollowing 
                                            ? 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' 
                                            : 'bg-primary text-background-dark hover:brightness-105'}">
                                        <span class="follow-modal-text">${isFollowing ? 'Unfollow' : 'Follow'}</span>
                                    </button>
                                `;
                            }

                            return `
                            <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                    ${avatarHtml}
                                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">${user.name}</span>
                                </div>
                                ${actionButton}
                            </div>
                        `;
                        }).join('');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                usersList.innerHTML = '<div class="text-center py-8 text-red-500">Error al cargar seguidos</div>';
            });
        }

        // Show remove follower confirmation modal
        function showRemoveFollowerModal(followerId) {
            followerToRemove = followerId;
            document.getElementById('remove-follower-modal').classList.remove('hidden');
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.scrollbarGutter = 'stable';
        }

        // Close remove follower modal
        function closeRemoveFollowerModal() {
            followerToRemove = null;
            document.getElementById('remove-follower-modal').classList.add('hidden');
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.scrollbarGutter = 'auto';
        }

        // Confirm and remove follower
        function confirmRemoveFollower() {
            if (!followerToRemove) return;

            fetch(`/users/${followerToRemove}/remove-follower`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeRemoveFollowerModal();
                    document.getElementById('followers-count').textContent = data.followers_count;
                    loadFollowers();
                } else {
                    alert(data.message || 'Error al eliminar seguidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar seguidor. Por favor intenta de nuevo.');
            })
            .finally(() => {
                followerToRemove = null;
            });
        }

        // Show unfollow confirmation modal
        function showUnfollowModal(userId, button) {
            userToUnfollow = userId;
            unfollowButton = button;
            document.getElementById('unfollow-modal').classList.remove('hidden');
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.scrollbarGutter = 'stable';
        }

        // Close unfollow modal
        function closeUnfollowModal() {
            userToUnfollow = null;
            unfollowButton = null;
            document.getElementById('unfollow-modal').classList.add('hidden');
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.scrollbarGutter = 'auto';
        }

        // Confirm and execute unfollow
        function confirmUnfollow() {
            if (!userToUnfollow) return;

            fetch(`/users/${userToUnfollow}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeUnfollowModal();
                    // Update button by re-querying it (don't rely on stored reference)
                    if (unfollowButton) {
                        unfollowButton.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        unfollowButton.classList.add('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        const textSpan = unfollowButton.querySelector('.follow-modal-text');
                        if (textSpan) {
                            textSpan.textContent = 'Follow';
                        }
                    }
                    // Decrement the following count (since we just unfollowed)
                    const followingCountElement = document.getElementById('following-count');
                    if (followingCountElement) {
                        const currentCount = parseInt(followingCountElement.textContent, 10);
                        if (currentCount > 0) {
                            followingCountElement.textContent = currentCount - 1;
                        }
                    }
                    // Reload the modal list to reflect changes
                    if (!document.getElementById('users-modal').classList.contains('hidden')) {
                        const modalTitle = document.getElementById('modal-title').textContent;
                        if (modalTitle === 'Following') {
                            loadFollowing();
                        } else if (modalTitle === 'Followers') {
                            loadFollowers();
                        }
                    }
                    // Reload the page to update posts from this user (on /following page)
                    if (window.location.pathname === '/following') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                } else {
                    alert(data.message || 'Error al dejar de seguir');
                    closeUnfollowModal();
                }
            })
            .catch(error => {
                console.error('Error completo:', error);
                closeUnfollowModal();
                const errorMsg = error.message ? `Error: ${error.message}` : 'Error al procesar la solicitud. Por favor intenta de nuevo.';
                alert(errorMsg);
            })
            .finally(() => {
                userToUnfollow = null;
                unfollowButton = null;
            });
        }

        // Toggle follow in modal
        function toggleFollowInModal(userId, button) {
            const isFollowing = button.querySelector('.follow-modal-text').textContent.trim() === 'Unfollow';

            if (isFollowing) {
                // Si está siguiendo, mostrar modal de confirmación
                showUnfollowModal(userId, button);
            } else {
                // Si no está siguiendo, hacer follow directo
                fetch(`/users/${userId}/follow`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Cambiar a "Unfollow"
                        button.classList.remove('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        button.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        button.querySelector('.follow-modal-text').textContent = 'Unfollow';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud. Por favor intenta de nuevo.');
                });
            }
        }

        // Close modal
        function closeModal(event) {
            if (!event || event.target === event.currentTarget) {
                document.getElementById('users-modal').classList.add('hidden');
            }
        }
    </script>
@endsection
