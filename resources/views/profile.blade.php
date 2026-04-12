@extends('layouts.app')

@section('content')
    {{-- Determinar si es el perfil propio o de otro usuario --}}
    @php
        $isOwnProfile = Auth::id() === $user->id;
        $profileUserId = $user->id;
    @endphp

    <!-- Unified Profile Header Section -->
    <div class="mb-8 sm:mb-10 lg:mb-12 w-full">
        <div class="flex flex-col lg:flex-row justify-between gap-6 sm:gap-8">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 lg:gap-8">
                <!-- Profile Image -->
                <div class="flex-shrink-0 flex justify-center sm:justify-start">
                    <div class="rounded-full shadow-lg overflow-hidden sm:border-4 sm:border-white sm:dark:border-slate-800">
                        <x-profile-avatar :user="$user" size="profile" />
                    </div>
                </div>

                <!-- Info Column -->
                <div class="flex-1 flex flex-col gap-3 sm:gap-4 text-center sm:text-left">
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</h1>
                    </div>
                    @if($user->bio)
                    <div class="max-w-2xl">
                        <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ $user->bio }}
                        </p>
                    </div>
                    @endif
                    <div class="flex flex-wrap justify-center sm:justify-start gap-x-4 sm:gap-x-8 gap-y-2">

                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">article</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $postsCount }} {{ $postsCount == 1 ? __('users.post') : __('users.posts') }}</span>
                        </div>
                        <button onclick="showFollowersList()" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">person</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200"><span id="followers-count">{{ $user->followers()->count() }}</span> {{ __('users.followers') }}</span>
                        </button>
                        <button onclick="showFollowingList()" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">person</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200"><span id="following-count">{{ $user->following()->count() }}</span> {{ __('users.following') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 sm:gap-3 flex-shrink-0 items-start justify-center sm:justify-start">
                @if($isOwnProfile)
                    {{-- Botones para el perfil propio --}}
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-full bg-primary px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-background-dark shadow-md transition-all hover:brightness-105">
                        <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                        <span class="hidden sm:inline">{{ __('users.edit_profile') }}</span>
                        <span class="sm:hidden">{{ __('buttons.edit') ?? 'Edit' }}</span>
                    </a>
                @else
                    {{-- Botón follow/unfollow para perfiles ajenos --}}
                    @auth
                        <button 
                            id="follow-btn-{{ $user->id }}" 
                            onclick="toggleFollow({{ $user->id }})" 
                            class="follow-btn flex items-center gap-2 rounded-full px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all 
                            {{ Auth::user()->isFollowing($user->id) ? 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' : 'bg-primary text-background-dark hover:brightness-105' }}">
                            <span class="material-symbols-outlined text-base sm:text-lg">{{ Auth::user()->isFollowing($user->id) ? 'person_check' : 'person_add' }}</span>
                            <span class="follow-text">{{ Auth::user()->isFollowing($user->id) ? __('users.unfollow') : __('users.follow') }}</span>
                        </button>
                    @endauth
                @endif
                <button onclick="shareProfile({{ $user->id }}, '{{ addslashes($user->name) }}')" class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-base sm:text-lg">share</span>
                    <span>{{ __('users.share') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 sm:mb-8 w-full">
        <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
            @if($isOwnProfile)
                {{-- Tabs para perfil propio --}}
                <a href="{{ route('profile', ['tab' => 'my-tips']) }}" class="border-b-2 {{ $tab === 'my-tips' || $tab === 'posts' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">{{ __('users.posts_tab') ?? 'Posts' }}</a>
                <a href="{{ route('profile', ['tab' => 'saved']) }}" class="border-b-2 {{ $tab === 'saved' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">{{ __('content.saved_title') ?? 'Saved' }}</a>
            @else
                {{-- Tabs para perfil de otros usuarios --}}
                <a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'posts']) }}" class="border-b-2 {{ $tab === 'posts' || $tab === 'my-tips' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">{{ __('users.posts_tab') }}</a>
                <a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'saved']) }}" class="border-b-2 {{ $tab === 'saved' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">{{ __('users.saved_tab') }}</a>
            @endif
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="w-full">
        @if($tips->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4">{{ $tab === 'saved' ? 'bookmark' : 'article' }}</span>
                <p class="text-slate-500 dark:text-slate-400 text-lg">
                    @if($tab === 'saved')
                        {{ __('content.no_saved') ?? __('users.no_saved') }}
                    @else
                        {{ __('content.no_posts_yet') ?? __('users.no_posts') }}
                    @endif
                </p>
                @if($tab !== 'saved' && $isOwnProfile)
                    <a href="{{ route('tips.create') }}" class="inline-flex items-center gap-2 mt-4 rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-background-dark shadow-md transition-all hover:brightness-105">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Create Your First Tip
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
                @foreach($tips as $tip)
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
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if(!$tips->isEmpty())
            <div class="mt-8 sm:mt-10 lg:mt-12">
                <x-pagination-per-page />
                {{ $tips->appends(['tab' => $tab])->links() }}
            </div>
        @endif
    </div>

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
        const profileUserId = {{ $profileUserId }};
        const currentUserId = {{ Auth::id() ?? 'null' }};
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

            fetch(`/users/${profileUserId}/followers`, {
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
                            const isCurrentUser = currentUserId === profileUserId;
                            const isSelf = user.id === currentUserId;
                            const initial = user.name.charAt(0).toUpperCase();
                            const hasPhoto = user.has_photo && user.avatar;
                            const avatarHtml = hasPhoto
                                ? `<img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">`
                                : `<div class="${user.avatar_color || 'bg-primary'} size-10 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white"><span class="text-sm">${initial}</span></div>`;

                            let actionButton = '';

                            if (!isSelf && currentUserId) {
                                if (isCurrentUser) {
                                    actionButton = `
                                        <button 
                                            onclick="showRemoveFollowerModal(${user.id})" 
                                            class="px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors flex-shrink-0">
                                            Remove
                                        </button>
                                    `;
                                }
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
                usersList.innerHTML = '<div class="text-center py-8 text-red-500">Error al cargar seguidores</div>';
            });
        }

        // Load following
        function loadFollowing() {
            const usersList = document.getElementById('users-list');
            usersList.innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 animate-spin">progress_activity</span></div>';

            fetch(`/users/${profileUserId}/following`, {
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
                    // Only update counter if viewing own profile
                    if (currentUserId === profileUserId) {
                        document.getElementById('followers-count').textContent = data.followers_count;
                    }
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
                    // Decrement the following count only if we're on our own profile
                    if (currentUserId === profileUserId) {
                        const followingCountElement = document.getElementById('following-count');
                        if (followingCountElement) {
                            const currentCount = parseInt(followingCountElement.textContent, 10);
                            if (currentCount > 0) {
                                followingCountElement.textContent = currentCount - 1;
                            }
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

        // Toggle follow button
        function toggleFollow(userId) {
            const btn = document.getElementById(`follow-btn-${userId}`);

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
                    // Actualizar el contador de seguidores
                    document.getElementById('followers-count').textContent = data.followers_count;

                    if (data.following) {
                        // Cambiar a "Unfollow" (seguido)
                        btn.classList.remove('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        btn.querySelector('.follow-text').textContent = '{{ __("users.unfollow") }}';
                        btn.querySelector('.material-symbols-outlined').textContent = 'person_check';
                    } else {
                        // Cambiar a "Follow" (no seguido)
                        btn.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300', 'hover:bg-slate-300', 'dark:hover:bg-slate-600');
                        btn.classList.add('bg-primary', 'text-background-dark', 'hover:brightness-105');
                        btn.querySelector('.follow-text').textContent = '{{ __("users.follow") }}';
                        btn.querySelector('.material-symbols-outlined').textContent = 'person_add';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Close modal
        function closeModal(event) {
            if (!event || event.target === event.currentTarget) {
                document.getElementById('users-modal').classList.add('hidden');
            }
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
