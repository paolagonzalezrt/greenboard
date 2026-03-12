<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GreenBoard - {{ __('login.hero_title') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec5b",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102216",
                    },
                    fontFamily: { "display": ["Plus Jakarta Sans"] },
                    borderRadius: { "xl": "0.75rem" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s ease; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen p-0 m-0 overflow-x-hidden">

@php $currentLang = app()->getLocale(); @endphp

<nav class="absolute top-0 right-0 z-50 p-6 flex items-center gap-4">
    <a href="{{ url('/explore') }}" class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-sm font-bold text-white lg:text-slate-700 lg:dark:text-slate-200 hover:bg-primary hover:text-slate-900 transition-all">
        <span class="material-symbols-outlined text-lg">explore</span>
        <span class="hidden sm:inline">{{ __('login.explore') }}</span>
    </a>

    {{-- Dropdown idioma --}}
    <div class="relative">
        <button onclick="toggleLangDropdown()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white lg:text-slate-700 lg:dark:text-slate-200 hover:border-primary">
            {{ strtoupper($currentLang) }}
        </button>
        <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-24 bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden z-50">
            <a href="{{ url('/lang/en') }}" class="block px-4 py-2 hover:bg-primary hover:text-white">EN</a>
            <a href="{{ url('/lang/es') }}" class="block px-4 py-2 hover:bg-primary hover:text-white">ES</a>
            <a href="{{ url('/lang/de') }}" class="block px-4 py-2 hover:bg-primary hover:text-white">DE</a>
        </div>
    </div>

    {{-- Dark Mode --}}
    <button onclick="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white lg:text-slate-700 lg:dark:text-slate-200 hover:border-primary">
        <span class="material-symbols-outlined text-xl" id="dark-icon">dark_mode</span>
    </button>
</nav>

<div class="flex min-h-screen w-full flex-col lg:flex-row">
    <div id="hero-section" class="flex lg:w-1/2 relative overflow-hidden bg-primary/10 h-screen lg:min-h-screen cursor-pointer lg:cursor-default group" onclick="showForm()">
        <div class="absolute inset-0 bg-cover bg-center z-0 transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent z-10"></div>
        <div class="relative z-20 flex flex-col justify-end p-10 lg:p-16 w-full h-full">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">eco</span>
                <span class="text-white text-3xl font-extrabold tracking-tight">GreenBoard</span>
            </div>
            <h1 class="text-white text-4xl lg:text-5xl font-black leading-tight mb-4">{{ __('login.hero_title') }}</h1>
            <p class="text-slate-200 text-lg max-w-md mb-6 lg:mb-0">{{ __('login.hero_desc') }}</p>
            <div class="lg:hidden flex items-center text-primary font-bold animate-bounce mt-4">
                <span class="material-symbols-outlined mr-2">touch_app</span> {{ __('login.tap_login') }}
            </div>
        </div>
    </div>

    <div id="form-section" class="hidden lg:flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-20 bg-background-light dark:bg-background-dark fade-in">
        <div class="w-full max-w-[440px]">
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">{{ __('login.form_title') }}</h2>
                <p class="text-slate-600 dark:text-slate-400">{{ __('login.form_desc') }}</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('login.email') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input name="email" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="john@example.com" type="email"/>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('login.password') }}</label>
                        <a href="#" class="text-xs text-primary font-bold hover:underline">{{ __('login.forgot') }}</a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                        <input name="password" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="••••••••" type="password"/>
                    </div>
                </div>

                <button class="w-full bg-primary text-slate-900 font-extrabold text-base py-4 rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-[0.95]" type="submit">
                    {{ __('login.login_button') }}
                </button>
            </form>

            <p class="text-center mt-8 text-slate-600 dark:text-slate-400">
                <span>{{ __('login.no_account') }}</span>
                <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">{{ __('login.sign_up') }}</a>
            </p>
        </div>
    </div>
</div>

<script>
function showForm() {
    if (window.innerWidth < 1024) {
        document.getElementById('hero-section').classList.add('hidden');
        const form = document.getElementById('form-section');
        form.classList.remove('hidden');
        form.classList.add('flex');
    }
}

function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('dark-icon');
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        icon.innerText = 'dark_mode';
    } else {
        html.classList.add('dark');
        icon.innerText = 'light_mode';
    }
}

function toggleLangDropdown() {
    const dropdown = document.getElementById('lang-dropdown');
    dropdown.classList.toggle('hidden');
}

function changeLanguage(locale) {
    window.location.href = `/lang/${locale}`;
}
</script>
</body>
</html>
