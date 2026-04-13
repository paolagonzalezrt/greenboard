<header class="flex items-center justify-between px-4 sm:px-6 lg:px-24 py-4 sm:py-6 lg:py-8 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
    <!-- Logo Section (Always visible) -->
    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
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
    <div class="flex items-center gap-2 sm:gap-3">
        @guest
            <!-- Guest Buttons (All Screens) -->
            <div class="flex items-center gap-1 sm:gap-2">
                @include('partials.language-modal')

                <button id="theme-toggle" class="p-2 w-10 h-10 rounded-full bg-white shadow-sm dark:bg-custom-dark-button dark:shadow-none text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode" onclick="window.ThemeManager.toggle()">
                    <span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
                </button>
            </div>

            <a href="{{ route('login') }}" class="flex items-center justify-center w-10 h-10 sm:w-auto sm:h-auto sm:px-5 sm:py-2 rounded-full font-bold text-xs sm:text-sm border-2 border-slate-300 dark:border-slate-700 hover:border-primary hover:text-primary transition-all">
                <span class="hidden sm:inline">{{ __('nav.login') }}</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">login</span>
            </a>
            <a href="{{ route('register') }}" class="bg-primary text-background-dark flex items-center justify-center w-10 h-10 sm:w-auto sm:h-auto sm:px-6 sm:py-2.5 rounded-full font-bold text-xs sm:text-sm hover:brightness-105 transition-all shadow-lg shadow-primary/30">
                <span class="hidden sm:inline">{{ __('nav.register') }}</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">person_add</span>
            </a>
        @else
            <!-- Authenticated Users -->

            <!-- Desktop: Theme, Language & Profile Dropdown -->
            <div class="hidden lg:flex items-center gap-1 sm:gap-2">
                @include('partials.language-modal')

                <button id="theme-toggle" class="p-2 w-10 h-10 rounded-full bg-white shadow-sm dark:bg-custom-dark-button dark:shadow-none text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center" title="Toggle dark mode" onclick="window.ThemeManager.toggle()">
                    <span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
                </button>
            </div>

            <div class="hidden lg:block relative">
                <!-- Desktop Profile Button with Dropdown -->
                <button onclick="toggleProfileMenu()" class="rounded-full hover:opacity-80 transition-opacity" title="{{ Auth::user()->name }}">
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
                    <x-profile-avatar :user="Auth::user()" size="sm" />
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
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">{{ __('nav.navigation') }}</p>
                    <nav class="flex flex-col gap-1">
                        <a class="{{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('dashboard') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">explore</span>
                            <span class="font-medium">{{ __('nav.explore') }}</span>
                        </a>
                        <a class="{{ request()->routeIs('following') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('following') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">favorite</span>
                            <span class="font-medium">{{ __('nav.following') }}</span>
                        </a>
                        <a class="{{ request()->routeIs('saved') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('saved') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">bookmark</span>
                            <span class="font-medium">{{ __('nav.saved') }}</span>
                        </a>
                        @if(Auth::user()->is_admin)
                            <a class="{{ request()->routeIs('admin.*') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('admin.reported-tips') }}" onclick="toggleMobileMenu()">
                                <span class="material-symbols-outlined text-[22px]">shield_person</span>
                                <span class="font-medium">{{ __('nav.administration') }}</span>
                            </a>
                        @endif
                        <a class="{{ request()->routeIs('profile') ? 'bg-primary/10 text-primary' : 'text-slate-700 dark:text-slate-300' }} flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('profile') }}" onclick="toggleMobileMenu()">
                            <span class="material-symbols-outlined text-[22px]">person</span>
                            <span class="font-medium">{{ __('nav.profile') }}</span>
                        </a>
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
                            <span class="material-symbols-outlined text-slate-400">chevron_right</span>
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

                        <!-- Theme Toggle -->
                        <button onclick="window.ThemeManager.toggle(); event.stopPropagation();" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-700 dark:text-slate-300">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[22px]" data-theme-icon>dark_mode</span>
                                <span class="font-medium">{{ __('nav.dark_mode') }}</span>
                            </div>
                            <div class="w-12 h-6 bg-slate-300 dark:bg-primary rounded-full relative transition-colors">
                                <div class="absolute top-1 left-1 dark:left-6 w-4 h-4 bg-white rounded-full shadow-md transition-all"></div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>

                <!-- Account Section -->
                <div class="p-4">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">{{ __('nav.account') }}</p>
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
</script>
