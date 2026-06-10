<header class="flex items-center justify-between px-4 sm:px-6 lg:px-24 py-4 sm:py-6 lg:py-8 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
    <!-- Logo Section (Always visible for AUTH, hidden mobile for GUEST) -->
    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="{{ auth()->check() ? 'flex' : 'hidden lg:flex' }} items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
        <span class="material-symbols-outlined text-primary text-2xl sm:text-3xl">eco</span>
        <span class="text-xl font-extrabold tracking-tight hover:text-primary transition-colors">GreenBoard</span>
    </a>

    <!-- Navigation Menu (Desktop Only - for authenticated users) -->
    @auth
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center">
            <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('dashboard') }}">
                {{ __('nav.explore') }}
            </a>
            <a class="{{ request()->routeIs('following') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('following') }}">
                {{ __('nav.following') }}
            </a>
            <a class="{{ request()->routeIs('saved') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('saved') }}">
                {{ __('nav.saved') }}
            </a>
            @if(Auth::user()->is_admin)
                <a class="{{ request()->routeIs('admin.*') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors flex items-center gap-1" href="{{ route('admin.reported-tips') }}">
                    {{ __('nav.administration') }}
                </a>
            @endif
        </nav>
    @endauth

    <!-- Actions Section -->
    <div class="flex flex-1 lg:flex-none items-center {{ auth()->check() ? 'justify-end' : 'justify-between' }} lg:justify-end gap-2 sm:gap-3 lg:order-4">
        @guest
            <!-- Configuration Section (Left on mobile, part of right group on desktop) -->
            <div class="flex items-center gap-1 sm:gap-2">
                @include('partials.language-modal')

                <button id="theme-toggle" class="p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode" onclick="window.ThemeManager.toggle()">
                    <span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
                </button>
            </div>

            <!-- Authentication Section (Right on mobile and desktop) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="px-3 py-2 sm:px-5 sm:py-2 rounded-full font-bold text-xs sm:text-sm border-2 border-slate-300 dark:border-slate-700 hover:border-primary hover:text-primary transition-all">
                    {{ __('nav.login') }}
                </a>
                <a href="{{ route('register') }}" class="bg-primary text-background-dark px-3 py-2 sm:px-6 sm:py-2.5 rounded-full font-bold text-xs sm:text-sm hover:brightness-105 transition-all shadow-lg shadow-primary/30">
                    {{ __('nav.register') }}
                </a>
            </div>
        @else
            <!-- Authenticated Users (Desktop: Theme, Language, Notifications & Profile) -->
            <div class="hidden lg:flex items-center gap-4 sm:gap-5">
                <!-- Icon group: Language + Dark mode + Notifications -->
                <div class="flex items-center gap-1">
                    @include('partials.language-modal')

                    <button id="theme-toggle" class="p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode" onclick="window.ThemeManager.toggle()">
                        <span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
                    </button>

                    <!-- Notification Bell (Desktop) -->
                    <div class="relative" id="notification-wrapper-desktop">
                        <button id="notification-bell-desktop" onclick="toggleNotifications('desktop')" class="relative p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Notificaciones">
                            <span class="material-symbols-outlined text-xl">notifications</span>
                            <span id="notif-badge-desktop" class="hidden absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">0</span>
                        </button>

                        <!-- Notification Dropdown (Desktop) -->
                        <div id="notification-dropdown-desktop" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden z-[60]">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Notificaciones</span>
                                <button onclick="markAllRead()" class="text-xs text-primary hover:underline font-medium">Marcar todo como leído</button>
                            </div>
                            <div id="notification-list-desktop" class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-6">Cargando...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative text-right">
                    <!-- Desktop Profile Button with Dropdown -->
                    <button onclick="toggleProfileMenu()" class="rounded-full hover:opacity-80 transition-opacity flex items-center" title="{{ Auth::user()->name }}">
                        <x-profile-avatar :user="Auth::user()" size="md" />
                    </button>

                    <!-- Desktop Dropdown Menu -->
                    <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50">
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                            <span>{{ __('nav.profile') }}</span>
                        </a>
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.reported-tips') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">shield_person</span>
                                <span>{{ __('nav.administration') }}</span>
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-left">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                <span>{{ __('nav.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile: right-side icon group -->
            <div class="lg:hidden flex items-center justify-center gap-1">
                <!-- Notification Bell (Mobile) -->
                <div class="relative flex items-center justify-center" id="notification-wrapper-mobile">
                    <button id="notification-bell-mobile" onclick="toggleNotifications('mobile')" class="relative p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Notificaciones">
                        <span class="material-symbols-outlined text-[26px]">notifications</span>
                        <span id="notif-badge-mobile" class="hidden absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">0</span>
                    </button>
                    <!-- Notification Dropdown (Mobile) - fixed position to avoid overflow -->
                    <div id="notification-dropdown-mobile" class="hidden fixed left-2 right-2 mt-2 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden z-[60]" style="top: 64px;">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-sm font-bold text-slate-800 dark:text-slate-200">Notificaciones</span>
                            <button onclick="markAllRead()" class="text-xs text-primary hover:underline font-medium">Marcar todo como leído</button>
                        </div>
                        <div id="notification-list-mobile" class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                            <p class="text-center text-slate-500 dark:text-slate-400 text-sm py-6">Cargando...</p>
                        </div>
                    </div>
                </div>

                <button onclick="toggleMobileMenu()" class="p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-[26px]">menu</span>
                </button>
            </div>
        @endguest
    </div>
</header>

<!-- Mobile Menu Drawer (Right Side - Only for authenticated users) -->
@auth
    <div id="mobile-menu" class="hidden lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50" onclick="closeMobileMenuOnBackdrop(event)">
        <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white dark:bg-background-dark shadow-2xl transform transition-transform duration-300 flex flex-col" onclick="event.stopPropagation()">

            <!-- Simplified Content -->
            <div class="flex-1 overflow-y-auto pt-4">
                <!-- Navigation Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">{{ __('nav.navigation') }}</p>
                    <nav class="flex flex-col gap-1">
                        <a class="{{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('dashboard') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">explore</span>
                            <span class="font-medium">{{ __('nav.explore') }}</span>
                        </a>
                        <a class="{{ request()->routeIs('following') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('following') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">favorite</span>
                            <span class="font-medium">{{ __('nav.following') }}</span>
                        </a>
                        <a class="{{ request()->routeIs('saved') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('saved') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">bookmark</span>
                            <span class="font-medium">{{ __('nav.saved') }}</span>
                        </a>
                        @if(Auth::user()->is_admin)
                            <a class="{{ request()->routeIs('admin.*') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('admin.reported-tips') }}" onclick="toggleMobileMenu()">
                                <span class="material-symbols-outlined text-[22px]">shield_person</span>
                                <span class="font-medium">{{ __('nav.administration') }}</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>

                <!-- Settings Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">{{ __('nav.settings') }}</p>
                    <div class="flex flex-col gap-1">
                        <!-- Language Toggle -->
                        <button onclick="toggleMobileLanguageDropdown(event)" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[22px]">translate</span>
                                <span class="font-medium">{{ __('nav.language') }}</span>
                            </div>
                        </button>

                        <!-- Language Dropdown -->
                        <div id="mobile-lang-dropdown" class="hidden bg-slate-50 dark:bg-slate-800/50 rounded-lg overflow-hidden mx-2 mb-2">
                            <div class="flex flex-col">
                                @foreach($availableLocales as $code => $locale)
                                    <a 
                                        href="{{ route('locale.switch', $code) }}" 
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors {{ $currentLocale === $code ? 'bg-primary/10 text-primary font-bold' : '' }}"
                                        onclick="closeMobileLanguageDropdown()"
                                    >
                                        <span class="text-lg">{{ $locale['flag'] }}</span>
                                        <span>{{ $locale['native'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Theme Toggle (Icon/Text only) -->
                        <button onclick="window.ThemeManager.toggle(); event.stopPropagation();" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[22px]" data-theme-icon>dark_mode</span>
                                <span class="font-medium">{{ __('nav.dark_mode') }}</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>

                <!-- Account Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">{{ __('nav.account') }}</p>
                    <div class="flex flex-col gap-1">
                        <a class="{{ request()->routeIs('profile') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('profile') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">person</span>
                            <span class="font-medium">{{ __('nav.profile') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-red-600 dark:text-red-400 text-left">
                                <span class="material-symbols-outlined text-[22px]">logout</span>
                                <span class="font-medium">{{ __('nav.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth

<script>
    // Toggle Profile Menu (Desktop)
    function toggleProfileMenu() {
        const menu = document.getElementById('profile-menu');
        menu.classList.toggle('hidden');
    }

    // Toggle Mobile Language Dropdown
    function toggleMobileLanguageDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('mobile-lang-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close Mobile Language Dropdown
    function closeMobileLanguageDropdown() {
        const dropdown = document.getElementById('mobile-lang-dropdown');
        if (dropdown) {
            dropdown.classList.add('hidden');
        }
    }

    // Close language dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('mobile-lang-dropdown');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (dropdown && mobileMenu && !mobileMenu.classList.contains('hidden')) {
            if (!event.target.closest('#mobile-lang-dropdown') && !event.target.closest('button[onclick*="toggleMobileLanguageDropdown"]')) {
                dropdown.classList.add('hidden');
            }
        }
    });

    // Close profile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('profile-menu');
        const profileButton = event.target.closest('button[onclick="toggleProfileMenu()"]');

        if (!profileButton && menu && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    // Toggle Mobile Menu
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');

        // Prevent body scroll when menu is open
        if (!mobileMenu.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Close mobile menu when clicking on backdrop
    function closeMobileMenuOnBackdrop(event) {
        if (event.target.id === 'mobile-menu') {
            toggleMobileMenu();
        }
    }

    // Update mobile theme icon on page load
    document.addEventListener('DOMContentLoaded', function() {
        const mobileThemeIcon = document.getElementById('mobile-theme-icon');

        if (mobileThemeIcon) {
            mobileThemeIcon.textContent = 'dark_mode';
        }
    });

    // ──────────────────────────────────────────────────────────
    // NOTIFICATION SYSTEM
    // ──────────────────────────────────────────────────────────
    @auth
    (function () {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        // Tracks which dropdown is open and when it was opened
        let openScope       = null;
        let openedAt        = null;
        let dismissTimer    = null;
        const GRACE_SECONDS = 5; // seconds after viewing before deletion

        // ── Icon per notification type ──────────────────────────
        function typeIcon(type) {
            if (type === 'new_follower') {
                return '<span class="material-symbols-outlined text-primary text-[18px]">person_add</span>';
            } else if (type === 'post_commented') {
                return '<span class="material-symbols-outlined text-blue-500 text-[18px]">chat_bubble</span>';
            } else if (type === 'comment_liked') {
                return '<span class="material-symbols-outlined text-purple-500 text-[18px]">favorite</span>';
            } else {
                return '<span class="material-symbols-outlined text-amber-500 text-[18px]">favorite</span>';
            }
        }

        // ── Avatar HTML ─────────────────────────────────────────
        function buildAvatar(name, avatarUrl, avatarColor) {
            if (avatarUrl) {
                return `<img src="${avatarUrl}" alt="${name}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">`;
            }
            const initial = name ? name.charAt(0).toUpperCase() : '?';
            return `<div class="w-9 h-9 rounded-full ${avatarColor} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">${initial}</div>`;
        }

        // ── Render list ─────────────────────────────────────────
        function renderNotifications(notifications, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!notifications || notifications.length === 0) {
                container.innerHTML = `<p class="text-center text-slate-500 dark:text-slate-400 text-sm py-8 px-4">No tienes notificaciones.</p>`;
                return;
            }

            container.innerHTML = notifications.map(n => {
                const unreadDot = !n.read
                    ? '<span class="w-2 h-2 rounded-full bg-primary flex-shrink-0 mt-1.5"></span>'
                    : '<span class="w-2 h-2 flex-shrink-0 mt-1.5"></span>';
                const bgClass = !n.read ? 'bg-primary/5 dark:bg-primary/10' : '';

                return `
                    <a href="${n.url}" onclick="handleNotifClick('${n.id}', event)"
                       class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer ${bgClass}">
                        ${buildAvatar(n.actor_name, n.actor_avatar, n.actor_avatar_color)}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-snug">${n.message}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">${n.created_at}</p>
                        </div>
                        ${unreadDot}
                    </a>`;
            }).join('');
        }

        // ── Badge ───────────────────────────────────────────────
        function updateBadge(count) {
            ['desktop', 'mobile'].forEach(scope => {
                const badge = document.getElementById(`notif-badge-${scope}`);
                if (!badge) return;
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        }

        // ── Fetch from server ───────────────────────────────────
        async function fetchNotifications() {
            try {
                const res  = await fetch('/notifications', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success) {
                    detectNew(data.notifications);
                    updateBadge(data.unread_count);
                    renderNotifications(data.notifications, 'notification-list-desktop');
                    renderNotifications(data.notifications, 'notification-list-mobile');
                    lastUnreadCount = data.unread_count;
                }
            } catch (e) {
                console.error('Error fetching notifications:', e);
            }
        }

        // ── Toggle open/close ───────────────────────────────────
        window.toggleNotifications = function(scope) {
            const dropdown = document.getElementById(`notification-dropdown-${scope}`);
            if (!dropdown) return;

            const isHidden = dropdown.classList.contains('hidden');

            // Close any open dropdown first
            closeAllDropdowns();

            if (isHidden) {
                dropdown.classList.remove('hidden');
                openScope  = scope;
                openedAt   = Date.now();
            }
        };

        // ── Close all dropdowns ─────────────────────────────────
        function closeAllDropdowns() {
            ['desktop', 'mobile'].forEach(s => {
                const d = document.getElementById(`notification-dropdown-${s}`);
                if (d && !d.classList.contains('hidden')) {
                    d.classList.add('hidden');
                    // Refresh list when closing to remove the read notifications
                    fetchNotifications();
                }
            });
            openScope = null;
        }

        // ── Click a notification → mark read + navigate ─────────
        window.handleNotifClick = function(id, event) {
            event.preventDefault();
            const url = event.currentTarget.href;
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).finally(() => {
                window.location.href = url;
            });
        };

        // ── Mark all read (header button) ───────────────────────
        window.markAllRead = function() {
            // Update UI immediately — don't wait for the server round-trip
            updateBadge(0);
            renderNotifications([], 'notification-list-desktop');
            renderNotifications([], 'notification-list-mobile');

            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).then(() => {
                // Confirm with server state after marking
                fetchNotifications();
            });
        };

        // ── Close on outside click ──────────────────────────────
        document.addEventListener('click', function(event) {
            if (!openScope) return;
            const wrapper = document.getElementById(`notification-wrapper-${openScope}`);
            // For mobile the dropdown is fixed (not inside wrapper), check by id too
            const dropdown = document.getElementById(`notification-dropdown-${openScope}`);
            const clickedInside = (wrapper && wrapper.contains(event.target))
                               || (dropdown && dropdown.contains(event.target));
            if (!clickedInside) {
                closeAllDropdowns();
            }
        });

        // ── Initial fetch + poll + page visibility ─────────────
        let lastUnreadCount = 0;
        let lastNotifIds    = new Set();

        function requestNotifPermission() {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        }

        function showBrowserNotif(title, body, url) {
            if (!('Notification' in window) || Notification.permission !== 'granted') return;
            const n = new Notification(title, {
                body:  body,
                icon:  '/favicon.svg',
                badge: '/favicon.svg',
                tag:   url,
            });
            n.onclick = function () { window.focus(); window.location.href = url; n.close(); };
            setTimeout(() => n.close(), 7000);
        }

        function detectNew(notifications) {
            if (lastNotifIds.size === 0) {
                notifications.forEach(n => lastNotifIds.add(n.id));
                return;
            }
            notifications.forEach(n => {
                if (!lastNotifIds.has(n.id)) {
                    lastNotifIds.add(n.id);
                    showBrowserNotif('GreenBoard', n.message, n.url);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            ['desktop', 'mobile'].forEach(scope => {
                const bell = document.getElementById(`notification-bell-${scope}`);
                if (bell) bell.addEventListener('click', requestNotifPermission, { once: true });
            });

            fetchNotifications();
            setInterval(fetchNotifications, 15000);

            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') fetchNotifications();
            });
        });
    })();
    @endauth
</script>

