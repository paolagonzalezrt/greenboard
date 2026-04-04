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
        <div class="leaf-pattern top-[-5%] left-[-5%] w-64 h-64 bg-primary rounded-full blur-[100px]"></div>
        <div class="leaf-pattern bottom-[-5%] right-[-5%] w-96 h-96 bg-primary/40 rounded-full blur-[120px]"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            @include('partials.nav')

            <main class="flex-1 flex flex-col items-center pt-8 sm:pt-16 lg:pt-24 pb-12 sm:pb-16 lg:pb-24 px-4 sm:px-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>

        {{-- Modal de selector de idioma --}}
        @include('partials.language-modal')
    @else
        @yield('content')
    @endif

    {{-- Funciones globales de tema --}}
    @include('partials.theme-functions')

    <script>
        // Theme Toggle Function
        window.ThemeManager = {
            toggle: function() {
                const html = document.documentElement;
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;

            if (savedTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            // Update icon
            const themeIcon = document.querySelector('[data-theme-icon]');
            if (themeIcon) {
                themeIcon.textContent = html.classList.contains('dark') ? 'light_mode' : 'dark_mode';
            }
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

            // Set the tip ID in the modal
            document.getElementById('report-tip-id').value = tipId;

            // Show the report modal
            document.getElementById('report-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function deletePost(tipId) {
            // Close any open menus first
            const allMenus = document.querySelectorAll('[id^="menu-"]');
            allMenus.forEach(menu => menu.classList.add('hidden'));

            // Ask for confirmation
            if (!confirm('¿Estás seguro de que quieres eliminar este post? Esta acción no se puede deshacer.')) {
                return;
            }

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
                        const successMsg = document.createElement('div');
                        successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                        successMsg.textContent = 'Post eliminado exitosamente';
                        document.body.appendChild(successMsg);

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
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                    successMsg.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>Post eliminado exitosamente</span>
                        </div>
                    `;
                    document.body.appendChild(successMsg);

                    // Remove notification after 3 seconds
                    setTimeout(() => {
                        successMsg.style.transition = 'opacity 0.3s ease-out';
                        successMsg.style.opacity = '0';
                        setTimeout(() => successMsg.remove(), 300);
                    }, 3000);

                } else {
                    // Show error notification
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                    errorMsg.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">error</span>
                            <span>${data.message || 'Error al eliminar el post'}</span>
                        </div>
                    `;
                    document.body.appendChild(errorMsg);

                    setTimeout(() => {
                        errorMsg.style.transition = 'opacity 0.3s ease-out';
                        errorMsg.style.opacity = '0';
                        setTimeout(() => errorMsg.remove(), 300);
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Show error notification
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-semibold';
                errorMsg.innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <span>Error al eliminar el post. Por favor intenta de nuevo.</span>
                    </div>
                `;
                document.body.appendChild(errorMsg);

                setTimeout(() => {
                    errorMsg.style.transition = 'opacity 0.3s ease-out';
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.remove(), 300);
                }, 3000);
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

    <!-- Report Modal -->
    <div id="report-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeReportModal(event)">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-500 text-3xl">report</span>
                    <h3 class="text-xl sm:text-2xl font-bold">Reportar Tip</h3>
                </div>
                <button onclick="closeReportModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors">
                    <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">close</span>
                </button>
            </div>

            <!-- Description -->
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                Ayúdanos a mantener la comunidad segura. Selecciona la razón del reporte y proporciona detalles adicionales.
            </p>

            <!-- Form -->
            <form id="report-form" onsubmit="submitReport(event)">
                <input type="hidden" id="report-tip-id" name="tip_id">

                <!-- Reason Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                        Razón del reporte <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="reason" value="spam" required class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Spam</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Contenido repetitivo o promocional</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="reason" value="inappropriate" required class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Contenido inapropiado</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Contenido ofensivo o inapropiado</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="reason" value="misleading" required class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Información falsa</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Información incorrecta o engañosa</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="reason" value="harassment" required class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Acoso</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Acoso o intimidación</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                            <input type="radio" name="reason" value="other" required class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Otro</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Otra razón no listada</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="report-description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Descripción adicional (opcional)
                    </label>
                    <textarea 
                        id="report-description" 
                        name="description" 
                        rows="4" 
                        maxlength="500"
                        class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 resize-none"
                        placeholder="Proporciona más detalles sobre el reporte..."
                    ></textarea>
                    <div class="flex justify-end mt-1">
                        <span class="text-xs text-slate-500 dark:text-slate-400" id="char-count">0/500</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        type="button" 
                        onclick="closeReportModal()" 
                        class="flex-1 px-6 py-3 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center gap-2"
                        id="submit-report-btn"
                    >
                        <span class="material-symbols-outlined">send</span>
                        <span>Enviar Reporte</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Character counter for description
        const reportDescription = document.getElementById('report-description');
        const charCount = document.getElementById('char-count');

        if (reportDescription && charCount) {
            reportDescription.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = `${count}/500`;
            });
        }

        // Close report modal
        function closeReportModal(event) {
            // If event is provided and clicked target is not the backdrop, don't close
            if (event && event.target !== event.currentTarget) {
                return;
            }

            const modal = document.getElementById('report-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling

            // Reset form
            document.getElementById('report-form').reset();
            if (charCount) {
                charCount.textContent = '0/500';
            }
        }

        // Submit report
        function submitReport(event) {
            event.preventDefault();

            const tipId = document.getElementById('report-tip-id').value;
            const reason = document.querySelector('input[name="reason"]:checked')?.value;
            const description = document.getElementById('report-description').value;

            if (!reason) {
                showNotification('Por favor selecciona una razón para el reporte', 'error');
                return;
            }

            // Disable submit button
            const submitBtn = document.getElementById('submit-report-btn');
            const originalContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span><span>Enviando...</span>';

            // Send report to backend
            fetch(`/tips/${tipId}/report`, {
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
                    closeReportModal();
                    showNotification(data.message || 'Reporte enviado exitosamente', 'success');
                } else {
                    showNotification(data.message || 'Error al enviar el reporte', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al enviar el reporte. Por favor intenta de nuevo.', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'check_circle' : 'error';

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-[110] font-semibold max-w-md animate-slide-in`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-2xl">${icon}</span>
                    <span>${message}</span>
                </div>
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
                    showNotification('¡Tip compartido exitosamente!', 'success');
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
            const url = `${window.location.origin}/users/${userId}`;
            const text = `¡Mira el perfil de ${userName} en GreenBoard!`;

            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: `Perfil de ${userName}`,
                    text: text,
                    url: url
                })
                .then(() => {
                    showNotification('¡Perfil compartido exitosamente!', 'success');
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
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        showNotification('¡Enlace copiado al portapapeles!', 'success');
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
                    showNotification('¡Enlace copiado al portapapeles!', 'success');
                } else {
                    showNotification('No se pudo copiar el enlace', 'error');
                }
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
                showNotification('No se pudo copiar el enlace', 'error');
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
</body>
</html>
