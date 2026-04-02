@extends('layouts.app')

@section('content')
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
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $postsCount }} {{ $postsCount == 1 ? 'Post' : 'Posts' }}</span>
                        </div>
                        <button onclick="showFollowersList()" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">person</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200"><span id="followers-count">{{ $user->followers()->count() }}</span> Followers</span>
                        </button>
                        <button onclick="showFollowingList()" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">person</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200"><span id="following-count">{{ $user->following()->count() }}</span> Following</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @auth
                <div class="flex gap-2 sm:gap-3 flex-shrink-0 items-start justify-center sm:justify-start">
                    @if(Auth::id() !== $user->id)
                        <button 
                            id="follow-btn-{{ $user->id }}" 
                            onclick="toggleFollow({{ $user->id }})" 
                            class="follow-btn flex items-center gap-2 rounded-full px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all 
                            {{ Auth::user()->isFollowing($user->id) ? 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600' : 'bg-primary text-background-dark hover:brightness-105' }}">
                            <span class="material-symbols-outlined text-base sm:text-lg">{{ Auth::user()->isFollowing($user->id) ? 'person_check' : 'person_add' }}</span>
                            <span class="follow-text">{{ Auth::user()->isFollowing($user->id) ? 'Following' : 'Follow' }}</span>
                        </button>
                    @endif
                    <button onclick="shareProfile({{ $user->id }}, '{{ addslashes($user->name) }}')" class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined text-base sm:text-lg">share</span>
                        <span>Share</span>
                    </button>
                </div>
            @endauth
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 sm:mb-8 w-full">
        <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
            <a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'posts']) }}" class="border-b-2 {{ $tab === 'posts' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">Posts</a>
            <a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'saved']) }}" class="border-b-2 {{ $tab === 'saved' ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }} px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">Saved</a>
        </div>
    </div>

    <!-- Posts Section Title -->
    <div class="mb-6 sm:mb-8 w-full">
        <h2 class="text-xl sm:text-2xl font-bold">{{ $tab === 'saved' ? 'Saved Posts' : 'Posts' }}</h2>
    </div>

    <!-- Posts Grid -->
    <div class="w-full">
        @if($tips->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4">{{ $tab === 'saved' ? 'bookmark' : 'article' }}</span>
                <p class="text-slate-500 dark:text-slate-400 text-lg">
                    @if($tab === 'saved')
                        No saved tips to display.
                    @else
                        This user hasn't published any tips yet.
                    @endif
                </p>
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

    <script>
        const profileUserId = {{ $user->id }};
        const currentUserId = {{ Auth::id() ?? 'null' }};

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
                            let actionButton = '';

                            if (!isSelf && currentUserId) {
                                if (isCurrentUser) {
                                    actionButton = `
                                        <button 
                                            onclick="removeFollower(${user.id})" 
                                            class="px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors flex-shrink-0">
                                            Remove
                                        </button>
                                    `;
                                } else if (user.is_following) {
                                    actionButton = `
                                        <button 
                                            onclick="toggleFollowInModal(${user.id}, this)" 
                                            class="px-3 py-1.5 text-xs font-bold rounded-full transition-colors flex-shrink-0 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600">
                                            Unfollow
                                        </button>
                                    `;
                                } else {
                                    actionButton = `
                                        <button 
                                            onclick="toggleFollowInModal(${user.id}, this)" 
                                            class="px-3 py-1.5 text-xs font-bold rounded-full transition-colors flex-shrink-0 bg-primary text-background-dark hover:brightness-105">
                                            Follow
                                        </button>
                                    `;
                                }
                            }

                            return `
                                <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                        <img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">
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
                            let actionButton = '';

                            if (!isSelf && currentUserId) {
                                if (user.is_following) {
                                    actionButton = `
                                        <button 
                                            onclick="toggleFollowInModal(${user.id}, this)" 
                                            class="px-3 py-1.5 text-xs font-bold rounded-full transition-colors flex-shrink-0 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600">
                                            Unfollow
                                        </button>
                                    `;
                                } else {
                                    actionButton = `
                                        <button 
                                            onclick="toggleFollowInModal(${user.id}, this)" 
                                            class="px-3 py-1.5 text-xs font-bold rounded-full transition-colors flex-shrink-0 bg-primary text-background-dark hover:brightness-105">
                                            Follow
                                        </button>
                                    `;
                                }
                            }

                            return `
                                <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                    <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                        <img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">
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

        // Remove follower
        function removeFollower(followerId) {
            if (!confirm('¿Estás seguro de que quieres eliminar este seguidor?')) {
                return;
            }

            fetch(`/users/${followerId}/remove-follower`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('followers-count').textContent = data.followers_count;
                    loadFollowers();
                } else {
                    alert(data.message || 'Error al eliminar seguidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar seguidor. Por favor intenta de nuevo.');
            });
        }

        // Toggle follow in modal
        function toggleFollowInModal(userId, button) {
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
                    // Reload the appropriate list
                    const modalTitle = document.getElementById('modal-title').textContent;
                    if (modalTitle === 'Followers') {
                        loadFollowers();
                    } else {
                        loadFollowing();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud. Por favor intenta de nuevo.');
            });
        }

        // Close modal
        function closeModal(event) {
            if (!event || event.target === event.currentTarget) {
                document.getElementById('users-modal').classList.add('hidden');
            }
        }
    </script>

    <script>
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
                    // Update followers count
                    document.getElementById('followers-count').textContent = data.followers_count;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud. Por favor intenta de nuevo.');
            });
        }
    </script>
@endsection
