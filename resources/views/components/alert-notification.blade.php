@props([
    'message' => '',
    'type' => 'success', // 'success' o 'error'
    'icon' => 'check_circle', // 'check_circle' para éxito, 'error' para error
    'dismissible' => false,
])

@php
    // Ensure translation keys for the component exist; fallback to defaults if missing
    $defaultSuccess = __('components.success_title', [], app()->getLocale());
    $defaultError = __('components.error_title', [], app()->getLocale());
    // Not strictly used here but ensures translations file loading during rendering
@endphp

@php
    $bgColor = $type === 'success' 
        ? 'bg-green-100 dark:bg-green-900/30' 
        : 'bg-red-100 dark:bg-red-900/30';
    
    $borderColor = $type === 'success' 
        ? 'border-green-500' 
        : 'border-red-500';
    
    $textColor = $type === 'success' 
        ? 'text-green-700 dark:text-green-400' 
        : 'text-red-700 dark:text-red-400';
    
    $iconColor = $type === 'success' 
        ? 'text-green-700 dark:text-green-400' 
        : 'text-red-700 dark:text-red-400';
@endphp

<div {{ $attributes->merge(['class' => "p-4 border rounded-xl flex items-center gap-3 {$bgColor} {$borderColor} {$textColor}"]) }}>
    <span class="material-symbols-outlined {{ $iconColor }}">{{ $icon }}</span>
    <span class="font-semibold">{{ $message }}</span>
    @if($dismissible)
        <button type="button" class="ml-auto p-1 hover:opacity-75 transition-opacity" onclick="this.closest('div').remove()">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    @endif
</div>
