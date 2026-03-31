@extends('layouts.app')

@section('title', 'Following - GreenBoard')

@section('content')
    <!-- Hero Section -->
    <div class="text-center mb-8 sm:mb-10 lg:mb-12 w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4">Following</h1>
        <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
            Stay updated with posts from people and topics you follow
        </p>

        <!-- Followers/Following Stats -->
        <div class="flex justify-center gap-6 mt-6">
            <button onclick="showFollowersList()" class="flex flex-col items-center hover:opacity-80 transition-opacity">
                <span class="text-2xl font-bold text-slate-900 dark:text-slate-100" id="followers-count">{{ Auth::user()->followers()->count() }}</span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Followers</span>
            </button>
            <button onclick="showFollowingList()" class="flex flex-col items-center hover:opacity-80 transition-opacity">
                <span class="text-2xl font-bold text-slate-900 dark:text-slate-100" id="following-count">{{ Auth::user()->following()->count() }}</span>
                <span class="text-sm text-slate-600 dark:text-slate-400">Following</span>
            </button>
        </div>
    </div>

    <!-- Posts Section -->
    <section class="w-full mb-12 sm:mb-16">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-bold">Latest from Following</h2>
            <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-primary/20 text-primary text-[9px] sm:text-[10px] font-bold rounded-full uppercase tracking-wider">Updated</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 items-stretch">
            @forelse($tips as $tip)
                <x-tip-card 
                    :id="$tip['id']"
                    :userId="$tip['user_id']"
                    :category="$tip['category']"
                    :user="$tip['user']"
                    :title="$tip['title']"
                    :description="$tip['description']"
                    :likes="$tip['likes']"
                    :comments="$tip['comments']"
                    :image="$tip['image']"
                    :avatar="$tip['avatar']"
                    :published_at="$tip['published_at']"
                    :isLiked="$tip['is_liked']"
                    :isBookmarked="$tip['is_bookmarked']"
                />
            @empty
                <div class="col-span-full text-center py-16">
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-6xl mb-4 block">group</span>
                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">No posts yet</h3>
                    <p class="text-slate-500 dark:text-slate-500">Start following people to see their posts here</p>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-primary text-background-dark font-bold rounded-xl hover:brightness-105 transition-all">
                        Explore Posts
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(count($tips) > 0)
            <div class="mt-8 sm:mt-10 lg:mt-12">
                {{ $tips->links() }}
            </div>
        @endif
    </section>

    <!-- Floating Action Button (FAB) -->
    <a href="{{ route('tips.create') }}" class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 h-12 sm:h-14 px-4 sm:px-6 bg-primary text-background-dark font-bold rounded-xl shadow-2xl shadow-primary/40 hover:scale-110 active:scale-95 transition-all flex items-center justify-center gap-2 z-50">
        <span class="material-symbols-outlined text-xl sm:text-2xl">add</span>
        <span class="hidden sm:inline text-sm sm:text-base">Create Post</span>
    </a>

    <!-- Modal for Followers/Following List -->
    <div id="users-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4" onclick="closeModal(event)">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[80vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100" id="modal-title">Followers</h3>
                <button onclick="closeModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6" id="users-list">
                <!-- Users will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        const currentUserId = {{ Auth::id() }};

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
                        usersList.innerHTML = data.followers.map(user => `
                            <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                    <img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">${user.name}</span>
                                </div>
                                <button 
                                    onclick="removeFollower(${user.id})" 
                                    class="px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0">
                                    Remove
                                </button>
                            </div>
                        `).join('');
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
                        usersList.innerHTML = data.following.map(user => `
                            <div class="flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">
                                <div class="flex items-center gap-3 flex-1 min-w-0 cursor-pointer" onclick="window.location.href='/users/${user.id}'">
                                    <img src="${user.avatar}" alt="${user.name}" class="size-10 rounded-full object-cover flex-shrink-0">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">${user.name}</span>
                                </div>
                                <button 
                                    onclick="toggleFollowInModal(${user.id}, this)" 
                                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors flex-shrink-0 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600">
                                    Unfollow
                                </button>
                            </div>
                        `).join('');
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
                    loadFollowers(); // Reload the list
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
                    document.getElementById('following-count').textContent = data.following_count;
                    loadFollowing(); // Reload the list
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
@endsection
