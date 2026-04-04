@props([
    'type' => 'post', // 'post' o 'comment'
    'modalId' => 'report-modal',
    'title' => 'Report',
    'description' => 'Help us keep the community safe.',
    'submitFunction' => 'submitReport',
    'idFieldName' => 'report-id'
])

<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" onclick="closeReportModal(event, '{{ $modalId }}')">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-500 text-3xl">report</span>
                <h3 class="text-xl sm:text-2xl font-bold">{{ $title }}</h3>
            </div>
            <button onclick="closeReportModal(null, '{{ $modalId }}')" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">close</span>
            </button>
        </div>

        <!-- Description -->
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
            {{ $description }}
        </p>

        <!-- Form -->
        <form id="report-form-{{ $type }}" onsubmit="{{ $submitFunction }}(event, '{{ $type }}')">
            <input type="hidden" id="{{ $idFieldName }}" name="{{ str_replace('-', '_', $idFieldName) }}">

            <!-- Reason Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                    {{ __('tips.report_reason') }} <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <!-- Spam -->
                    <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="reason" value="spam" required class="mt-1 text-primary focus:ring-primary" id="reason-spam-{{ $type }}">
                        <span class="text-slate-800 dark:text-slate-200">{{ __('tips.report_reason_spam') }}</span>
                    </label>

                    <!-- Inappropriate -->
                    <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="reason" value="inappropriate" required class="mt-1 text-primary focus:ring-primary" id="reason-inappropriate-{{ $type }}">
                        <span class="text-slate-800 dark:text-slate-200">{{ __('tips.report_reason_inappropriate') }}</span>
                    </label>

                    <!-- Misleading -->
                    <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="reason" value="misleading" required class="mt-1 text-primary focus:ring-primary" id="reason-misleading-{{ $type }}">
                        <span class="text-slate-800 dark:text-slate-200">{{ __('tips.report_reason_misinformation') }}</span>
                    </label>

                    <!-- Harassment -->
                    <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="reason" value="harassment" required class="mt-1 text-primary focus:ring-primary" id="reason-harassment-{{ $type }}">
                        <span class="text-slate-800 dark:text-slate-200">{{ __('tips.report_reason_harassment') }}</span>
                    </label>

                    <!-- Other -->
                    <label class="flex items-start gap-3 p-3 border-2 border-slate-200 dark:border-slate-700 rounded-lg hover:border-primary cursor-pointer transition-colors">
                        <input type="radio" name="reason" value="other" required class="mt-1 text-primary focus:ring-primary" id="reason-other-{{ $type }}">
                        <span class="text-slate-800 dark:text-slate-200">{{ __('tips.report_reason_other') }}</span>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="report-description-{{ $type }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                    {{ __('tips.additional_description') }}
                </label>
                <textarea 
                    id="report-description-{{ $type }}"
                    name="description" 
                    rows="4" 
                    maxlength="500"
                    class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 resize-none"
                    placeholder="{{ __('tips.additional_description_placeholder') }}"
                    oninput="updateCharCount('report-description-{{ $type }}', 'char-count-{{ $type }}')"
                ></textarea>
                <div class="flex justify-end mt-1">
                    <span class="text-xs text-slate-500 dark:text-slate-400" id="char-count-{{ $type }}">0/500</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button 
                    type="button"
                    onclick="closeReportModal(null, '{{ $modalId }}')" 
                    class="flex-1 px-4 py-2 sm:py-3 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition-all"
                >
                    {{ __('buttons.cancel') }}
                </button>
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 sm:py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined text-lg">send</span>
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
        countSpan.textContent = `${textarea.value.length}/500`;
    }
</script>
