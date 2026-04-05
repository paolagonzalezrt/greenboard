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
    class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center overflow-auto"
    onclick="if (event.target.id === '{{ $id }}') closeConfirmModal(null, '{{ $id }}')"
>
    <div 
        class="bg-white dark:bg-custom-dark-bg rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-4 p-5 sm:p-6 transform transition-all max-h-[90vh] flex flex-col border border-transparent dark:border-custom-gray-border"
        onclick="event.stopPropagation()"
    >
        <div class="flex flex-col items-center my-2">
            <span class="material-symbols-outlined {{ $isDangerous ? 'text-red-500' : 'text-primary' }} text-4xl mb-2">
                {{ $isDangerous ? 'delete_outline' : 'help_outline' }}
            </span>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center">
                {{ $title }}
            </h3>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed text-center">
            {{ $message }}
        </p>

        <div class="mb-2 flex gap-2 justify-center">
            <button 
                type="button"
                data-close-modal="true"
                class="px-6 py-2 bg-gray-100 dark:bg-custom-dark-input text-gray-700 dark:text-gray-200 font-bold text-sm rounded-full hover:bg-gray-200 dark:hover:bg-custom-dark-button transition-colors cursor-pointer"
            >
                {{ $cancelText }}
            </button>
            
            <button 
                type="button"
                data-action="{{ $onConfirm }}"
                class="px-6 py-2 {{ $isDangerous ? 'bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20' : 'bg-primary hover:brightness-105 shadow-lg shadow-primary/20' }} text-white font-bold text-sm rounded-full transition-colors cursor-pointer"
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
