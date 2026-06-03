<!-- Popover tooltip para mostrar likers en hover -->
<style>
    .material-symbols-outlined {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    .likers-tooltip {
        position: absolute;
        background: white;
        dark:background: #1e293b;
        border-radius: 8px;
        shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 40;
        min-width: 200px;
        max-width: 300px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) translateX(-50%);
        transition: all 0.2s ease-in-out;
        pointer-events: none;
        padding: 8px;
    }

    .likers-tooltip.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) translateX(-50%);
        pointer-events: auto;
    }

    .likers-trigger:hover .likers-tooltip {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) translateX(-50%);
        pointer-events: auto;
    }

    .likers-tooltip .liker-item {
        padding: 6px 8px;
        border-radius: 4px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .likers-tooltip .liker-item:hover {
        background: #f1f5f9;
    }

    .dark .likers-tooltip {
        background: #334155;
        border: 1px solid #475569;
    }

    .dark .likers-tooltip .liker-item:hover {
        background: #475569;
    }
</style>

<!-- Modal para mostrar todos los likers -->
<div id="likers-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" onclick="closeLikersModal()">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[80vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between px-8 pt-6 pb-2">
            <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('messages.likes') }}</h3>
            <button onclick="closeLikersModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">close</span>
            </button>
        </div>

        <!-- Content -->
        <div id="likers-list" class="overflow-y-auto flex-1 p-4 pt-0 pb-5">
            <!-- Likers will be loaded here by JavaScript -->
            <div class="flex justify-center items-center h-12">
                <div class="animate-spin">
                    <span class="material-symbols-outlined text-slate-400">progress_activity</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let likersRequestId = 0;

    function getLikersLoadingMarkup() {
        return `
            <div class="flex justify-center items-center h-12">
                <div class="animate-spin">
                    <span class="material-symbols-outlined text-slate-400">progress_activity</span>
                </div>
            </div>
        `;
    }

    function resetLikersModal(showLoading = true) {
        const modal = document.getElementById('likers-modal');
        const likersList = document.getElementById('likers-list');

        if (!modal || !likersList) {
            return { modal, likersList };
        }

        modal.classList.remove('hidden');
        likersList.innerHTML = showLoading ? getLikersLoadingMarkup() : '';

        return { modal, likersList };
    }

    function loadLikers(endpoint) {
        const requestId = ++likersRequestId;
        const { modal, likersList } = resetLikersModal(true);

        if (!modal || !likersList) {
            return;
        }

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                if (requestId !== likersRequestId || modal.classList.contains('hidden')) {
                    return;
                }

                if (data.success && data.likers.length > 0) {
                    likersList.innerHTML = data.likers.map(liker => `
                        <div class="flex items-center gap-3 p-3 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg cursor-pointer transition-colors mb-2" onclick="closeLikersModal(); window.location.href='${window.APP_URL}/users/${liker.id}'">
                            <div class="flex-shrink-0">
                                ${liker.avatar_url
                                    ? `<img src="${liker.avatar_url}" alt="${liker.name}" class="w-10 h-10 rounded-full object-cover">`
                                    : `<div class="w-10 h-10 rounded-full ${liker.avatar_bg_color} flex items-center justify-center text-white font-bold text-sm">${liker.name.charAt(0)}</div>`
                                }
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">${liker.name}</p>
                            </div>
                        </div>
                    `).join('');
                } else {
                    likersList.innerHTML = '<p class="text-center text-slate-500 dark:text-slate-400 py-8">{{ __("messages.no_likers") }}</p>';
                }
            })
            .catch(error => {
                if (requestId !== likersRequestId) {
                    return;
                }

                console.error('Error loading likers:', error);
                likersList.innerHTML = '<p class="text-center text-red-500 py-8">{{ __("messages.error.generic") }}</p>';
            });
    }

    function openLikersModal(tipId) {
        loadLikers(`${window.APP_URL}/tips/${tipId}/likers`);
    }

    function openCommentLikersModal(commentId) {
        loadLikers(`${window.APP_URL}/comments/${commentId}/likers`);
    }

    function closeLikersModal() {
        likersRequestId++;
        const modal = document.getElementById('likers-modal');
        const likersList = document.getElementById('likers-list');

        if (modal) {
            modal.classList.add('hidden');
        }

        if (likersList) {
            likersList.innerHTML = '';
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLikersModal();
        }
    });
</script>
