@props([
    'id' => 'confirm-modal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'onConfirm' => '',
    'isDangerous' => false,
])

<div 
    id="{{ $id }}" 
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center overflow-auto"
    onclick="if (event.target.id === '{{ $id }}') closeConfirmModal(null, '{{ $id }}')"
>
    <div 
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-4 p-5 sm:p-6 transform transition-all max-h-[90vh] flex flex-col"
        onclick="event.stopPropagation()"
    >
        <!-- Header with Icon -->
        <div class="flex flex-col items-center mb-6">
            <span class="material-symbols-outlined {{ $isDangerous ? 'text-red-500' : 'text-primary' }} text-4xl mb-4">
                {{ $isDangerous ? 'delete_outline' : 'help_outline' }}
            </span>
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 text-center">
                {{ $title }}
            </h3>
        </div>

        <!-- Message -->
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 leading-relaxed text-center">
            {{ $message }}
        </p>

        <!-- Buttons -->
        <div class="flex gap-3 justify-center">
            <button 
                type="button"
                data-close-modal="true"
                class="px-6 py-2 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 font-bold text-sm rounded-full hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors cursor-pointer"
            >
                {{ $cancelText }}
            </button>
            <button 
                type="button"
                data-action="{{ $onConfirm }}"
                class="px-6 py-2 {{ $isDangerous ? 'bg-red-500 hover:bg-red-600' : 'bg-primary hover:brightness-105' }} text-white font-bold text-sm rounded-full transition-colors cursor-pointer"
            >
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

<script>
    function showConfirmModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            // Prevenir desplazamiento de la página sin cambiar layout
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.scrollbarGutter = 'stable';
        }
    }

    function closeConfirmModal(event, modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            // Permitir desplazamiento de la página nuevamente
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.scrollbarGutter = 'auto';
        }
    }

    function executeConfirmAction(modalId, actionFunction) {
        closeConfirmModal(null, modalId);
        if (actionFunction && typeof window[actionFunction] === 'function') {
            window[actionFunction]();
        }
    }

    // Agregar event listeners a los botones cuando el DOM esté listo
    function initializeConfirmModals() {
        const modals = document.querySelectorAll('[id$="-modal"]');
        modals.forEach(modal => {
            const modalId = modal.id;
            const buttons = modal.querySelectorAll('button');

            buttons.forEach(button => {
                if (button.getAttribute('data-close-modal') === 'true') {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeConfirmModal(null, modalId);
                    });
                } else if (button.getAttribute('data-action')) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const actionFunction = button.getAttribute('data-action');
                        executeConfirmAction(modalId, actionFunction);
                    });
                }
            });
        });
    }

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeConfirmModals);
    } else {
        initializeConfirmModals();
    }

    // Close modal when pressing Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModals = document.querySelectorAll('[id$="-modal"]:not(.hidden)');
            openModals.forEach(modal => {
                if (modal.id.includes('confirm')) {
                    closeConfirmModal(null, modal.id);
                }
            });
        }
    });
</script>
