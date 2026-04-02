@props([
    'user' => auth()->user(),
    'size' => 'md', // xs, sm, md, lg, xl, profile
])

@php
    $sizeClasses = [
        'xs' => 'size-6',
        'sm' => 'size-8',
        'md' => 'size-9',
        'lg' => 'size-12',
        'xl' => 'size-16',
        'profile' => 'size-20 sm:size-24 md:size-32',
    ];
    
    $textSizeClasses = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-sm',
        'lg' => 'text-lg',
        'xl' => 'text-2xl',
        'profile' => 'text-2xl md:text-4xl',
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $textSizeClass = $textSizeClasses[$size] ?? $textSizeClasses['md'];
    
    // Obtener la inicial del nombre
    $initial = strtoupper(substr($user->name, 0, 1));
@endphp

@if($user->hasProfilePhoto())
    <img 
        src="{{ $user->getAvatarUrl() }}" 
        alt="{{ $user->name }}" 
        class="{{ $sizeClass }} rounded-full object-cover flex-shrink-0"
        {{ $attributes }}
    />
@else
    <div class="{{ $sizeClass }} {{ $user->getAvatarBgColor() }} rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white {{ $attributes }}">
        <span class="{{ $textSizeClass }}">{{ $initial }}</span>
    </div>
@endif
