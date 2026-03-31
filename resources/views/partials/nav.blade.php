<header class="flex items-center justify-between px-4 sm:px-6 lg:px-24 py-4 sm:py-6 lg:py-8 border-b border-primary/10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
    <!-- Logo Section (Always visible) -->
    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
        <span class="material-symbols-outlined text-primary text-2xl sm:text-3xl">eco</span>
        <span class="text-xl font-extrabold tracking-tight hover:text-primary transition-colors">GreenBoard</span>
    </a>

    <!-- Navigation Menu (Desktop Only - for authenticated users) -->
    @auth
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center">
            <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('dashboard') }}">
                Explore
            </a>
            <a class="{{ request()->routeIs('following') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('following') }}">
                Following
            </a>
            <a class="{{ request()->routeIs('saved') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('saved') }}">
                Saved
            </a>
            @if(Auth::user()->is_admin)
                <a class="{{ request()->routeIs('admin.*') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors flex items-center gap-1" href="{{ route('admin.reported-tips') }}">
                    <span class="material-symbols-outlined text-[18px]">shield_person</span>
                    Admin
                </a>
            @endif
        </nav>
    @endauth

    <!-- Actions Section -->
    <div class="flex items-center gap-2 sm:gap-3">
        @guest
            <!-- Guest Buttons (All Screens) -->
            <div class="flex items-center gap-1 sm:gap-2">
                <button id="theme-toggle" class="p-2 w-10 h-10 rounded-full bg-slate-100 dark:bg-custom-dark-button text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode">
                    <span class="material-symbols-outlined text-xl">dark_mode</span>
                </button>

                <button id="language-toggle" class="p-2 w-10 h-10 rounded-full bg-slate-100 dark:bg-custom-dark-button text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Change language">
                    <span class="material-symbols-outlined text-xl">translate</span>
                </button>
            </div>

            <a href="{{ route('login') }}" class="px-3 py-1.5 sm:px-5 sm:py-2 rounded-full font-bold text-xs sm:text-sm border-2 border-slate-300 dark:border-slate-700 hover:border-primary hover:text-primary transition-all">
                <span class="hidden sm:inline">Log In</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">login</span>
            </a>
            <a href="{{ route('register') }}" class="bg-primary text-background-dark px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-full font-bold text-xs sm:text-sm hover:brightness-105 transition-all shadow-lg shadow-primary/30">
                <span class="hidden sm:inline">Sign Up</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">person_add</span>
            </a>
        @else
            <!-- Authenticated Users -->

            <!-- Desktop: Theme, Language & Profile Dropdown -->
            <div class="hidden lg:flex items-center gap-1 sm:gap-2">
                <button id="theme-toggle" class="p-2 w-10 h-10 rounded-full bg-slate-100 dark:bg-custom-dark-button text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode">
                    <span class="material-symbols-outlined text-xl">dark_mode</span>
                </button>

                <button id="language-toggle" class="p-2 w-10 h-10 rounded-full bg-slate-100 dark:bg-custom-dark-button text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Change language">
                    <span class="material-symbols-outlined text-xl">translate</span>
                </button>
            </div>

            <div class="hidden lg:block relative">
                <!-- Desktop Profile Button with Dropdown -->
                <button onclick="toggleProfileMenu()" class="size-8 sm:size-9 rounded-full bg-primary/20 border-2 border-primary/20 flex items-center justify-center overflow-hidden hover:border-primary transition-all" title="{{ Auth::user()->name }}">
                    <span class="material-symbols-outlined text-primary text-[20px] sm:text-[24px]">account_circle</span>
                </button>

                <!-- Desktop Dropdown Menu -->
                <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50">
                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span>Mi Perfil</span>
                    </a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.reported-tips') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">shield_person</span>
                            <span>Admin Panel</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile: Hamburger Menu Button (Right Side) -->
            <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[28px]">menu</span>
            </button>
        @endguest
    </div>
</header>

<!-- Mobile Menu Drawer (Right Side - Only for authenticated users) -->
@auth
    <div id="mobile-menu" class="hidden lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50" onclick="closeMobileMenuOnBackdrop(event)">
        <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white dark:bg-background-dark shadow-2xl transform transition-transform duration-300 flex flex-col" onclick="event.stopPropagation()">

            <!-- Header with User Info -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-primary/20 border-2 border-primary/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[24px]">account_circle</span>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button onclick="toggleMobileMenu()" class="p-1.5 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto">
                <!-- Navigation Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">Navegación</p>
                    <nav class="flex flex-col gap-1">
                        <a class="{{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('dashboard') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">explore</span>
                            <span class="font-medium">Explore</span>
                        </a>
                        <a class="{{ request()->routeIs('following') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('following') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">favorite</span>
                            <span class="font-medium">Following</span>
                        </a>
                        <a class="{{ request()->routeIs('saved') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('saved') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">bookmark</span>
                            <span class="font-medium">Saved</span>
                        </a>
                        @if(Auth::user()->is_admin)
                            <a class="{{ request()->routeIs('admin.*') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('admin.reported-tips') }}" onclick="toggleMobileMenu()">
                                <span class="material-symbols-outlined text-[22px]">shield_person</span>
                                <span class="font-medium">Admin Panel</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>

                <!-- Settings Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">Configuración</p>
                    <div class="flex flex-col gap-1">
                        <!-- Theme Toggle -->
                        <button onclick="themeToggle(); event.stopPropagation();" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[22px]" id="mobile-theme-icon">dark_mode</span>
                                <span class="font-medium">Modo Oscuro</span>
                            </div>
                            <div class="w-12 h-6 bg-slate-300 dark:bg-primary rounded-full relative transition-colors">
                                <div class="absolute top-1 left-1 dark:left-6 w-4 h-4 bg-white rounded-full shadow-md transition-all"></div>
                            </div>
                        </button>

                        <!-- Language Toggle -->
                        <button onclick="languageToggle(); event.stopPropagation();" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[22px]">translate</span>
                                <span class="font-medium">Idioma</span>
                            </div>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">ES</span>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>

                <!-- Account Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">Cuenta</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-red-600 dark:text-red-400 text-left">
                            <span class="material-symbols-outlined text-[22px]">logout</span>
                            <span class="font-medium">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-center gap-2 text-slate-500 dark:text-slate-400">
                    <span class="material-symbols-outlined text-primary text-xl">eco</span>
                    <span class="text-sm font-bold">GreenBoard</span>
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
        const isDark = document.documentElement.classList.contains('dark');

        if (mobileThemeIcon) {
            mobileThemeIcon.textContent = isDark ? 'light_mode' : 'dark_mode';
        }
    });
</script>
