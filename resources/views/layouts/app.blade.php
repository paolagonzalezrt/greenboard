<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <main class="flex-1 flex flex-col items-center pt-8 sm:pt-16 lg:pt-24 pb-12 sm:pb-16 lg:pb-24 px-4 sm:px-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    @else
        @yield('content')
    @endif

    <script>
        // Theme Toggle (Dark Mode)
        const themeToggle = () => {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = themeBtn?.querySelector('.material-symbols-outlined');
            const mobileThemeIcon = document.getElementById('mobile-theme-icon');

            if (isDark) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
                if (themeIcon) themeIcon.textContent = 'dark_mode';
                if (mobileThemeIcon) mobileThemeIcon.textContent = 'dark_mode';
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (themeIcon) themeIcon.textContent = 'lightbulb_2';
                if (mobileThemeIcon) mobileThemeIcon.textContent = 'lightbulb_2';
            }
        };

        // Language Toggle
        const languageToggle = () => {
            const currentLang = localStorage.getItem('language') || 'en';
            const newLang = currentLang === 'en' ? 'es' : 'en';
            localStorage.setItem('language', newLang);

            // Mostrar notificación visual (opcional)
            const langBtn = document.getElementById('language-toggle');
            const originalText = langBtn?.querySelector('.material-symbols-outlined')?.textContent;

            if (langBtn) {
                const icon = langBtn.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.textContent = 'check_circle';
                    setTimeout(() => {
                        icon.textContent = 'language';
                    }, 1000);
                }
            }

            // Puedes redirigir para cambiar el idioma del lado del servidor
            // window.location.href = `${window.location.pathname}?lang=${newLang}`;

            console.log('Language changed to:', newLang === 'en' ? 'English' : 'Español');
        };

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const savedLang = localStorage.getItem('language') || 'en';
            const html = document.documentElement;

            // Apply saved theme
            html.classList.remove('light', 'dark');
            html.classList.add(savedTheme);

            // Update theme icons (desktop and mobile)
            const themeBtn = document.getElementById('theme-toggle');
            const themeIcon = themeBtn?.querySelector('.material-symbols-outlined');
            const mobileThemeIcon = document.getElementById('mobile-theme-icon');

            if (themeIcon) {
                themeIcon.textContent = savedTheme === 'dark' ? 'lightbulb_2' : 'dark_mode';
            }
            if (mobileThemeIcon) {
                mobileThemeIcon.textContent = savedTheme === 'dark' ? 'lightbulb_2' : 'dark_mode';
            }

            // Add event listeners to buttons
            const langBtn = document.getElementById('language-toggle');

            if (themeBtn) themeBtn.addEventListener('click', themeToggle);
            if (langBtn) langBtn.addEventListener('click', languageToggle);

            // Update HTML lang attribute
            html.setAttribute('lang', savedLang);
        });

        // Card Menu Functions
        function toggleCardMenu(cardId) {
            const menu = document.getElementById('menu-' + cardId);
            const allMenus = document.querySelectorAll('[id^="menu-"]');

            // Close all other menus
            allMenus.forEach(m => {
                if (m.id !== 'menu-' + cardId) {
                    m.classList.add('hidden');
                }
            });

            // Toggle current menu
            menu.classList.toggle('hidden');

            // Prevent event from bubbling
            event.stopPropagation();
        }

        function reportPost(cardId) {
            // Close the menu
            const menu = document.getElementById('menu-' + cardId);
            menu.classList.add('hidden');

            // Show confirmation (you can replace this with a modal)
            alert('Post reportado. Gracias por ayudarnos a mantener la comunidad segura.');

            // Here you would typically send a request to your backend
            // Example: fetch('/report-post', { method: 'POST', body: JSON.stringify({ cardId }) })
        }

        // Toggle like functionality
        function toggleLike(tipId, element) {
            @auth
                // Send request to backend
                fetch(`/tips/${tipId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the heart icon
                        const heartIcon = element.querySelector('.material-symbols-outlined');
                        const likeCount = element.querySelector('.like-count');

                        if (data.liked) {
                            // Add filled style
                            heartIcon.classList.add('filled', 'text-red-500');
                            heartIcon.style.fontVariationSettings = "'FILL' 1";
                        } else {
                            // Remove filled style
                            heartIcon.classList.remove('filled', 'text-red-500');
                            heartIcon.style.fontVariationSettings = "'FILL' 0";
                        }

                        // Update count
                        likeCount.textContent = data.likes_count;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar el like. Por favor intenta de nuevo.');
                });
            @else
                // Redirect to login if not authenticated
                window.location.href = '{{ route('login') }}';
            @endauth
        }

        // Toggle bookmark functionality
        function toggleBookmark(tipId, element) {
            @auth
                // Send request to backend
                fetch(`/tips/${tipId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the bookmark icon
                        const bookmarkIcon = element.querySelector('.material-symbols-outlined');

                        if (data.bookmarked) {
                            // Add filled style
                            bookmarkIcon.classList.add('filled', 'text-primary');
                            bookmarkIcon.style.fontVariationSettings = "'FILL' 1";
                        } else {
                            // Remove filled style
                            bookmarkIcon.classList.remove('filled', 'text-primary');
                            bookmarkIcon.style.fontVariationSettings = "'FILL' 0";

                            // If we're on the saved page or saved tab in profile, remove the card
                            if (window.location.pathname === '/saved' || (window.location.pathname === '/profile' && window.location.search.includes('tab=saved'))) {
                                // Find the card container (it's the parent with bg-white or dark:bg-slate-800 classes)
                                const card = element.closest('div[class*="bg-white"]');
                                if (card) {
                                    card.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                                    card.style.opacity = '0';
                                    card.style.transform = 'scale(0.9)';

                                    setTimeout(() => {
                                        card.remove();

                                        // Check if there are no more cards
                                        const grid = document.querySelector('.grid');
                                        const remainingCards = grid.querySelectorAll('div[class*="bg-white"]').length;

                                        if (remainingCards === 0) {
                                            // Show empty state
                                            grid.innerHTML = `
                                                <div class="col-span-full text-center py-16">
                                                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-700 text-6xl mb-4 block">bookmark</span>
                                                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">No saved posts yet</h3>
                                                    <p class="text-slate-500 dark:text-slate-500">Start saving posts to build your personal collection</p>
                                                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-primary text-background-dark font-bold rounded-xl hover:brightness-105 transition-all">
                                                        Explore Posts
                                                    </a>
                                                </div>
                                            `;

                                            // Update counter
                                            const counterSpan = document.querySelector('span.bg-primary\\/20');
                                            if (counterSpan) {
                                                counterSpan.textContent = '0 Saved';
                                            }
                                        } else {
                                            // Update counter
                                            const counterSpan = document.querySelector('span.bg-primary\\/20');
                                            if (counterSpan) {
                                                counterSpan.textContent = `${remainingCards} Saved`;
                                            }
                                        }
                                    }, 300);
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar el bookmark. Por favor intenta de nuevo.');
                });
            @else
                // Redirect to login if not authenticated
                window.location.href = '{{ route('login') }}';
            @endauth
        }

        // Close all card menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick^="toggleCardMenu"]')) {
                const allMenus = document.querySelectorAll('[id^="menu-"]');
                allMenus.forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
