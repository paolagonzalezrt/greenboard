<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GreenBoard - Explore Community Tips')</title>

    {{-- Anti-flash: aplicar tema antes del primer render --}}
    @include('partials.theme-script')

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- <script id="tailwind-config">
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
                        "sort-border-dark": "#a6a6a6",
                        "sort-bg-light": "#d6d6d6",
                        "sort-text-light": "#585858",
                    },
                    fontFamily: { "display": ["Plus Jakarta Sans"] },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "3xl": "1.5rem", "full": "9999px"},
                },
            },
        }
    </script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    brown: {
                        50: '#fdf8f6',
                        100: '#f2e8e5',
                        200: '#eaddd7',
                        300: '#d2b4a8',
                        400: '#b88a7b',
                        500: '#9d6655',
                        600: '#7d4d3d',
                        700: '#633c30',
                        800: '#4d2e25',
                        900: '#3d241d',
                        950: '#1f120e',
                    },
                    slate: {
                        50: '#fafafa',
                        100: '#f5f5f5',
                        200: '#e5e5e5',
                        300: '#d4d4d4',
                        400: '#a3a3a3',
                        500: '#737373',
                        600: '#525252',
                        700: '#404040',
                        800: '#262626',
                        900: '#171717',
                        950: '#0a0a0a',
                    },
                    "primary": "#13ec5b",
                    "background-light": "#f6f6f6",
                    "background-dark": "#121212",
                    "custom-dark-bg": "#1c1c1c", // Fondo del modal
                    "custom-dark-input": "#262626", // Fondo de inputs
                    "custom-dark-button": "#333333", // Hover de botones
                    "custom-gray-border": "#2e2e2e", // Bordes modo oscuro
                    "sort-border-dark": "#a6a6a6",
                    "sort-bg-light": "#d6d6d6",
                    "sort-text-light": "#585858",
                },
                fontFamily: { "display": ["Plus Jakarta Sans"] },
                borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "3xl": "1.5rem", "full": "9999px"},
            },
        },
    }
</script>

    <style type="text/tailwindcss">
        button, a.bg-primary { border-radius: 9999px !important; }
        .leaf-pattern { @apply absolute z-0 opacity-10 pointer-events-none; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-float-slow { animation: floatSlow 4s ease-in-out infinite; }
        .animate-float-delayed { animation: floatSlow 5s ease-in-out infinite 1s; }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-20px) translateX(10px); }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen @yield('body-class', 'p-0 m-0') overflow-x-hidden @yield('body-position', 'relative')">

    @if(!View::hasSection('no-layout'))
        <!-- <div class="leaf-pattern top-[-5%] left-[-5%] w-64 h-64 bg-primary rounded-full blur-[100px]"></div> -->
        <div class="leaf-pattern bottom-[-5%] right-[-5%] w-96 h-96 bg-primary/40 rounded-full blur-[120px]"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            @include('partials.nav')

            <main class="flex-1 flex flex-col items-center pt-6 sm:pt-14 lg:pt-20 pb-12 sm:pb-16 lg:pb-24 px-4 sm:px-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    @else
        @yield('content')
    @endif

    {{-- Funciones globales de tema --}}
    @include('partials.theme-functions')

    {{-- Use the JS showNotification helper for session flashes so placement/animation match other notifications --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showNotification(@json(session('success')), 'success');
            @endif

            @if(session('error'))
                showNotification(@json(session('error')), 'error');
            @endif
        });
    </script>

    <script>
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

        function openReportModal(id, type = 'post') {
            document.getElementById('report-tip-id').value = id;
            const modal = document.getElementById('report-modal');
            modal.classList.remove('hidden');
            // Use scrollbarGutter to prevent layout shift
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.scrollbarGutter = 'stable';
            // Store the type for later use in submitReport
            modal.dataset.reportType = type;
        }

        function reportPost(cardId) {
            // Close the menu
            const menu = document.getElementById('menu-' + cardId);
            if (menu) {
                menu.classList.add('hidden');
            }

            // Extract tip ID from the card
            const cardElement = document.querySelector(`#menu-${cardId}`);
            if (!cardElement) return;

            // Find the closest parent card element
            const card = cardElement.closest('[onclick*="/tips/"]');
            if (!card) return;

            // Extract tip ID from onclick attribute
            const onclickAttr = card.getAttribute('onclick');
            const tipIdMatch = onclickAttr.match(/\/tips\/(\d+)/);

            if (!tipIdMatch) return;

            const tipId = tipIdMatch[1];

            // Use the new simple function
            openReportModal(tipId);
        }

        let pendingDeletePostId = null;

        function deletePost(tipId) {
            // Close any open menus first
            const allMenus = document.querySelectorAll('[id^="menu-"]');
            allMenus.forEach(menu => menu.classList.add('hidden'));

            // Store the tip ID for later confirmation
            pendingDeletePostId = tipId;

            // Show the confirm modal
            showConfirmModal('delete-post-modal');
        }

        function performDeletePost() {
            if (!pendingDeletePostId) return;
            const tipId = pendingDeletePostId;
            pendingDeletePostId = null;

            // Send delete request to backend
            fetch(`/tips/${tipId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If we're on the post detail page, redirect to dashboard
                    if (window.location.pathname.includes(`/tips/${tipId}`)) {
                        // Show success message briefly before redirect
                        showNotification(@json(__('messages.success.post_deleted')), 'success');

                        setTimeout(() => {
                            window.location.href = '{{ route('dashboard') }}';
                        }, 1000);
                        return;
                    }

                    // Find and remove the specific card
                    // The onclick attribute contains: window.location.href='/tips/ID'
                    let cardRemoved = false;
                    const allElements = document.querySelectorAll('[onclick]');

                    allElements.forEach(element => {
                        const onclickAttr = element.getAttribute('onclick');

                        // Check if onclick contains the URL to this specific tip
                        // Format: window.location.href='/tips/123' or similar
                        if (onclickAttr && onclickAttr.includes(`/tips/${tipId}`)) {
                            cardRemoved = true;

                            // Animate removal
                            element.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                            element.style.opacity = '0';
                            element.style.transform = 'scale(0.95)';

                            setTimeout(() => {
                                element.remove();
                            }, 300);
                        }
                    });

                    // Show success notification
                    showNotification(@json(__('messages.success.post_deleted')), 'success');

                } else {
                    // Show error notification
                    showNotification(data.message || @json(__('messages.error.delete_post')), 'error');
                }
            })
                .catch(error => {
                console.error('Error:', error);
                showNotification(@json(__('messages.error.delete_post')), 'error');
            });
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

                            // Pop animation and floating hearts
                            if (typeof gsap !== 'undefined') {
                                gsap.fromTo(heartIcon, 
                                    { scale: 1 }, 
                                    { scale: 1.5, duration: 0.4, ease: 'back.out(4)', clearProps: 'transform' }
                                );

                                const rect = heartIcon.getBoundingClientRect();
                                for (let i = 0; i < 5; i++) {
                                    const particle = document.createElement('span');
                                    particle.className = 'material-symbols-outlined absolute pointer-events-none text-red-500 z-[100]';
                                    particle.style.fontVariationSettings = "'FILL' 1";
                                    particle.innerText = 'favorite';
                                    particle.style.left = (rect.left + window.scrollX + rect.width / 2) + 'px';
                                    particle.style.top = (rect.top + window.scrollY + rect.height / 2) + 'px';
                                    particle.style.transform = 'translate(-50%, -50%)';
                                    document.body.appendChild(particle);

                                    const angle = Math.random() * Math.PI * 2;
                                    const distance = 25 + Math.random() * 35; 

                                    gsap.fromTo(particle, 
                                        { scale: 0.2, opacity: 1 },
                                        {
                                            x: Math.cos(angle) * distance,
                                            y: Math.sin(angle) * distance - 45,
                                            opacity: 0,
                                            scale: Math.random() * 1.5 + 0.5,
                                            duration: 1.5 + Math.random() * 1.0,
                                            ease: 'power1.out',
                                            onComplete: () => particle.remove()
                                        }
                                    );
                                }
                            }
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
                    showNotification(@json(__('messages.error.like')), 'error');
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
                    showNotification(@json(__('messages.error.bookmark')), 'error');
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

    <!-- Report Modal -->
    <!-- Report Modal Component -->
    <x-report-modal
        type="post"
        modalId="report-modal"
        title="{{ __('tips.report_title') }}"
        description="{{ __('tips.report_description') }}"
        submitFunction="submitReport"
        idFieldName="report-tip-id"
    />

    <!-- Confirm Delete Post Modal Component -->
    <x-confirm-modal 
        id="delete-post-modal" 
        title="{{ __('tips.confirm_delete_title') }}"
        message="{{ __('tips.confirm_delete_message') }}"
        confirmText="{{ __('tips.confirm_delete_button') }}"
        cancelText="{{ __('tips.cancel') }}"
        onConfirm="performDeletePost"
        isDangerous="true"
    />

    <script>

        // Close report modal
        function closeReportModal(event, modalId = 'report-modal') {
            // If event is provided and clicked target is not the backdrop, don't close
            if (event && event.target !== event.currentTarget) {
                return;
            }

            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.scrollbarGutter = 'auto';

            // Reset form - find it within the modal
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
                // Reset character count
                const charCounts = modal.querySelectorAll('[id^="char-count-"]');
                charCounts.forEach(el => el.textContent = '0/500');
            }
        }

        // Submit report
        function submitReport(event, type = 'post') {
            event.preventDefault();

            // Get the form that was submitted
            const form = event.target;
            const modal = form.closest('[id^="report-modal"]');
            
            // Use type from dataset if available (passed via openReportModal)
            const reportType = modal?.dataset?.reportType || type;
            
            const tipId = form.querySelector('[id$="-id"]').value || form.querySelector('input[type="hidden"]').value;
            const reason = form.querySelector('input[name="reason"]:checked')?.value;
            const description = form.querySelector('textarea[name="description"]')?.value || '';

            if (!reason) {
                showNotification(@json(__('tips.report_select_reason')), 'error');
                return;
            }

            // Disable submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> <span>' + @json(__('messages.info.processing')) + '</span>';

            // Send report to backend - use appropriate endpoint based on type
            const endpoint = reportType === 'comment' ? `/comments/${tipId}/report` : `/tips/${tipId}/report`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    reason: reason,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Find and close the modal
                    const modal = form.closest('[id^="report-modal"]');
                    closeReportModal(null, modal.id);
                    showNotification(data.message || @json(__('tips.report_sent_success')), 'success');
                } else {
                    showNotification(data.message || @json(__('messages.error.send_report')), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(@json(__('messages.error.send_report')), 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' 
                ? 'bg-green-100 dark:bg-green-900/30' 
                : 'bg-red-100 dark:bg-red-900/30';
            
            const borderColor = type === 'success' 
                ? 'border-green-500' 
                : 'border-red-500';
            
            const textColor = type === 'success' 
                ? 'text-green-700 dark:text-green-400' 
                : 'text-red-700 dark:text-red-400';
            
            const icon = type === 'success' ? 'check_circle' : 'error';

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 border rounded-xl flex items-center gap-3 ${bgColor} ${borderColor} ${textColor} shadow-lg z-[110] max-w-md animate-slide-in`;
            notification.innerHTML = `
                <span class="material-symbols-outlined">${icon}</span>
                <span class="font-semibold">${message}</span>
            `;
            document.body.appendChild(notification);

            // Remove notification after 4 seconds
            setTimeout(() => {
                notification.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Close modal when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('report-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeReportModal();
                }
            }
        });

        // Share Tip Function
        function shareTip(tipId, tipTitle) {
            const url = `${window.location.origin}/tips/${tipId}`;
            const text = `¡Mira este tip sobre sostenibilidad! ${tipTitle}`;

            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: tipTitle,
                    text: text,
                    url: url
                })
                .then(() => {
                    showNotification(@json(__('tips.share_success')), 'success');
                })
                .catch((error) => {
                    // User cancelled or error occurred
                    if (error.name !== 'AbortError') {
                        console.error('Error sharing:', error);
                        // Fallback to copy link
                        copyToClipboard(url);
                    }
                });
            } else {
                // Fallback: Copy to clipboard
                copyToClipboard(url);
            }
        }

        // Share Profile Function
        function shareProfile(userId, userName) {
            const currentLocale = @json(app()->getLocale());
            const url = `${window.location.origin}/${currentLocale}/users/${userId}`;
            
            // Get translations from data attributes or use defaults
            const titleTemplate = @json(__('users.share_profile_title'));
            const textTemplate = @json(__('users.share_profile_text'));
            const successMsg = @json(__('users.share_success'));
            
            const title = titleTemplate.replace(':name', userName);
            const text = textTemplate.replace(':name', userName);

            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url
                })
                .then(() => {
                    showNotification(successMsg, 'success');
                })
                .catch((error) => {
                    // User cancelled or error occurred
                    if (error.name !== 'AbortError') {
                        console.error('Error sharing:', error);
                        // Fallback to copy link
                        copyToClipboard(url);
                    }
                });
            } else {
                // Fallback: Copy to clipboard
                copyToClipboard(url);
            }
        }

        // Copy to Clipboard Function
        function copyToClipboard(text) {
            const copySuccessMsg = @json(__('users.copy_link_success'));
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        showNotification(copySuccessMsg, 'success');
                    })
                    .catch(err => {
                        console.error('Error copying to clipboard:', err);
                        fallbackCopyToClipboard(text);
                    });
            } else {
                fallbackCopyToClipboard(text);
            }
        }

        // Fallback copy method for older browsers
        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showNotification(@json(__('users.copy_link_success')), 'success');
                } else {
                    showNotification(@json(__('messages.error.copy')), 'error');
                }
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
                showNotification(@json(__('messages.error.copy')), 'error');
            }

            document.body.removeChild(textArea);
        }
    </script>

    <style>
        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Entry animations for main content elements
            if (!sessionStorage.getItem('hasSeenEntryAnimation')) {
                gsap.from('main > *', {
                    y: 30,
                    opacity: 0,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: 'power3.out',
                    clearProps: 'all' // prevents conflict with tailwind hover/opacity
                });
                sessionStorage.setItem('hasSeenEntryAnimation', 'true');
            }

            // Typing effect for search placeholder
            const searchInputs = document.querySelectorAll('.search-typing-input');
            const phrases = @json(__('content.search_phrases') ?? []);
            if (searchInputs.length > 0 && phrases.length > 0) {
                let phraseIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                let lastInteractionTime = 0;
                let isFocused = false;

                // Track user interaction to pause animation
                searchInputs.forEach(input => {
                    const updateInteraction = () => { lastInteractionTime = Date.now(); };
                    input.addEventListener('input', updateInteraction);
                    input.addEventListener('focus', () => { isFocused = true; updateInteraction(); });
                    input.addEventListener('blur', () => { isFocused = false; updateInteraction(); });
                });

                function shouldPause() {
                    const hasText = Array.from(searchInputs).some(i => i.value !== "");
                    return hasText || isFocused;
                }

                // State logic: updates which part of the text to show
                function type() {
                    if (shouldPause()) {
                        charIndex = 0;
                        isDeleting = false;
                        setTimeout(type, 500);
                        return;
                    }

                    const currentPhrase = phrases[phraseIndex];
                    let typingSpeed = isDeleting ? 50 : 100;

                    if (!isDeleting && charIndex === currentPhrase.length) {
                        isDeleting = true;
                        typingSpeed = 2500;
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        phraseIndex = (phraseIndex + 1) % phrases.length;
                        typingSpeed = 500;
                    } else {
                        charIndex = isDeleting ? charIndex - 1 : charIndex + 1;
                    }

                    setTimeout(type, typingSpeed);
                }

                // Rendering logic: updates the placeholder and handles the blinking cursor
                setInterval(() => {
                    if (shouldPause()) {
                        searchInputs.forEach(input => {
                            if (input.value === "") {
                                input.setAttribute('placeholder', '');
                            }
                        });
                        return;
                    }

                    const currentPhrase = phrases[phraseIndex];
                    const displayedText = currentPhrase.substring(0, charIndex);
                    const cursor = (Math.floor(Date.now() / 500) % 2 === 0) ? '|' : ' ';
                    
                    searchInputs.forEach(input => {
                        input.setAttribute('placeholder', displayedText + cursor);
                    });
                }, 100);
                
                setTimeout(type, 1000);
            }
        });
    </script>
</body>
</html>
