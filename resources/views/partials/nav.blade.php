<header class="flex items-center justify-between px-4 sm:px-6 lg:px-24 py-4 sm:py-6 lg:py-8 border-b border-primary/10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
    <!-- Logo Section -->
    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
        <div class="bg-primary p-1.5 sm:p-2 rounded-lg text-background-dark">
            <span class="material-symbols-outlined block text-xl sm:text-2xl">eco</span>
        </div>
        <span class="hidden md:block text-xl font-extrabold tracking-tight hover:text-primary transition-colors">GreenBoard</span>
    </a>

    <!-- Navigation Menu (Only for authenticated users) -->
    @auth
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center">
            <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('dashboard') }}">
                Explore
            </a>
            <a class="text-slate-600 dark:text-slate-400 font-medium text-sm hover:text-primary transition-colors" href="#">
                Community
            </a>
            <a class="text-slate-600 dark:text-slate-400 font-medium text-sm hover:text-primary transition-colors" href="#">
                Marketplace
            </a>
            <a class="{{ request()->routeIs('profile') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('profile') }}">
                Profile
            </a>
            <a class="{{ request()->routeIs('about-us') ? 'text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium' }} text-sm hover:text-primary transition-colors" href="{{ route('about-us') }}">
                About
            </a>
        </nav>
    @endauth

    <!-- Actions Section -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Theme & Language Buttons -->
        <div class="flex items-center gap-1 sm:gap-2">
            <button id="theme-toggle" class="p-1.5 sm:p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Toggle dark mode">
                <span class="material-symbols-outlined block text-[20px] sm:text-[24px]">dark_mode</span>
            </button>

            <button id="language-toggle" class="p-1.5 sm:p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Change language">
                <span class="material-symbols-outlined block text-[20px] sm:text-[24px]">language</span>
            </button>
        </div>

        <!-- Auth Buttons -->
        @guest
            <a href="{{ route('login') }}" class="px-3 py-1.5 sm:px-5 sm:py-2 rounded-full font-bold text-xs sm:text-sm border-2 border-slate-300 dark:border-slate-700 hover:border-primary hover:text-primary transition-all">
                <span class="hidden sm:inline">Log In</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">login</span>
            </a>
            <a href="{{ route('register') }}" class="bg-primary text-background-dark px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-full font-bold text-xs sm:text-sm hover:brightness-105 transition-all shadow-lg shadow-primary/30">
                <span class="hidden sm:inline">Sign Up</span>
                <span class="sm:hidden material-symbols-outlined text-[18px]">person_add</span>
            </a>
        @else
            <div class="flex items-center gap-2">
                <!-- User Avatar/Profile Button -->
                <a href="{{ route('profile') }}" class="size-8 sm:size-9 rounded-full bg-primary/20 border-2 border-primary/20 flex items-center justify-center overflow-hidden hover:border-primary transition-all" title="{{ Auth::user()->name }}">
                    <span class="material-symbols-outlined text-primary text-[20px] sm:text-[24px]">account_circle</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 sm:p-2 text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Log out">
                        <span class="material-symbols-outlined block text-[20px] sm:text-[24px]">logout</span>
                    </button>
                </form>
            </div>
        @endguest
    </div>
</header>
