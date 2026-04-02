@if ($errors->has($fieldName))
    <div class="text-xs font-medium text-red-600 dark:text-red-400 mt-1.5 flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">info</span>
        {{ $errors->first($fieldName) }}
    </div>
@endif
