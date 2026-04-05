@props([
    'type' => 'post',
    'modalId' => 'report-modal',
    'title' => 'Report',
    'description' => 'Help us keep the community safe.',
    'submitFunction' => 'submitReport',
    'idFieldName' => 'report-id'
])

<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[100] flex items-center justify-center overflow-auto" onclick="if (event.target.id === '{{ $modalId }}') closeReportModal(null, '{{ $modalId }}')">
    <div class="bg-white dark:bg-custom-dark-bg rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-md mx-4 p-5 sm:p-6 max-h-[90vh] overflow-y-auto transform transition-all flex flex-col" onclick="event.stopPropagation()">
        
        <div class="flex flex-col items-center my-2">
            <span class="material-symbols-outlined text-red-500 text-4xl mb-2">report</span>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 text-center">{{ $title }}</h3>
        </div>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 text-center">
            {{ $description }}
        </p>

        <form id="report-form-{{ $type }}" onsubmit="{{ $submitFunction }}(event, '{{ $type }}')">
            <input type="hidden" id="{{ $idFieldName }}" name="{{ str_replace('-', '_', $idFieldName) }}">

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('tips.report_reason') }} <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    @foreach(['spam', 'inappropriate', 'misleading', 'harassment', 'other'] as $reason)
                        <label class="flex items-start gap-3 p-3 border border-gray-200 dark:border-custom-gray-border rounded-lg hover:border-primary cursor-pointer transition-colors group">
                            <input type="radio" name="reason" value="{{ $reason }}" required class="mt-1 text-primary focus:ring-primary bg-transparent border-gray-300 dark:border-gray-500" id="reason-{{ $reason }}-{{ $type }}">
                            <span class="text-gray-700 dark:text-gray-200 text-sm">
                                {{ __("tips.report_reason_" . ($reason === 'misleading' ? 'misinformation' : $reason)) }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="report-description-{{ $type }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('tips.additional_description') }}
                </label>
                <textarea 
                    id="report-description-{{ $type }}"
                    name="description" 
                    rows="3" 
                    maxlength="500"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-custom-gray-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-custom-dark-input text-gray-800 dark:text-gray-200 resize-none text-sm"
                    placeholder="{{ __('tips.additional_description_placeholder') }}"
                    oninput="updateCharCount('report-description-{{ $type }}', 'char-count-{{ $type }}')"
                ></textarea>
                <div class="flex justify-end mt-1">
                    <span class="text-xs text-gray-400 dark:text-gray-500" id="char-count-{{ $type }}">0/500</span>
                </div>
            </div>

            <div class="pb-2 flex gap-2 justify-center">
                <button 
                    type="button"
                    onclick="closeReportModal(null, '{{ $modalId }}')" 
                    class="px-6 py-2 bg-gray-100 dark:bg-custom-dark-input text-gray-700 dark:text-gray-200 font-bold text-sm rounded-full hover:bg-gray-200 dark:hover:bg-custom-dark-button transition-colors"
                >
                    {{ __('buttons.cancel') }}
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-red-500 text-white font-bold text-sm rounded-full hover:bg-red-600 transition-colors shadow-lg shadow-red-500/20"
                >
                    {{ __('buttons.send') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCharCount(textareaId, countId) {
        const textarea = document.getElementById(textareaId);
        const countSpan = document.getElementById(countId);
        if (textarea && countSpan) {
            countSpan.textContent = `${textarea.value.length}/500`;
        }
    }

    function closeReportModal(event, modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.documentElement.style.overflow = 'auto';
            document.documentElement.style.scrollbarGutter = 'auto';
        }
    }

    function showReportModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.scrollbarGutter = 'stable';
        }
    }
</script>