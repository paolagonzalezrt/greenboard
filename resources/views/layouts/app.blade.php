<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'GreenBoard - Explore Community Tips')</title>

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
                        "background-light": "#f6f6f6",
                        "background-dark": "#212121",
                        "custom-dark-input": "#1c1c1c",
                        "custom-dark-button": "#2d2d2d",
                    },
                    fontFamily: { "display": ["Plus Jakarta Sans"] },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "3xl": "1.5rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        .leaf-pattern { @apply absolute z-0 opacity-10 pointer-events-none; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen @yield('body-class', 'p-0 m-0') overflow-x-hidden @yield('body-position', 'relative')">

    @if(!View::hasSection('no-layout'))
        <div class="leaf-pattern top-[-5%] left-[-5%] w-64 h-64 bg-primary rounded-full blur-[100px]"></div>
        <div class="leaf-pattern bottom-[-5%] right-[-5%] w-96 h-96 bg-primary/40 rounded-full blur-[120px]"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            @include('partials.nav')

            <main class="flex-1 flex flex-col items-center pt-24 pb-24 px-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    @else
        @yield('content')
    @endif
</body>
</html>
