<header class="flex items-center justify-between px-8 lg:px-24 py-8 border-b border-primary/10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <div class="bg-primary p-2 rounded-lg text-background-dark">
            <span class="material-symbols-outlined block text-2xl">eco</span>
        </div>
        <h2 class="text-xl font-extrabold tracking-tight">GreenBoard</h2>
    </div>
    <div class="flex items-center gap-6">
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold mr-4">
            <a class="text-primary transition-colors border-b-2 border-primary pb-1" href="{{ url('/') }}">Explore</a>
        </nav>
        <div class="flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="px-6 py-2 rounded-full font-bold text-sm border-2 border-slate-300 dark:border-slate-700 hover:border-primary hover:text-primary transition-all">
                    Log In
                </a>
                <a href="{{ route('register') }}" class="bg-primary text-background-dark px-6 py-2.5 rounded-full font-bold text-sm hover:brightness-105 transition-all shadow-lg shadow-primary/30">
                    Sign Up
                </a>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-slate-500 hover:text-red-500 transition-colors">
                        Log Out ({{ Auth::user()->name }})
                    </button>
                </form>
            @endguest
        </div>
    </div>
</header>