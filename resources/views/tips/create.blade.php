@extends('layouts.app')

@section('title', 'Create Post - GreenBoard')

@section('content')
    <div class="w-full max-w-3xl mx-auto px-4 sm:px-0">
       
        <!-- Header -->
        <div class="mb-8 sm:mb-10 lg:mb-12 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-2">{{ __('tips.create_title') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg max-w-2xl mx-auto">
                {{ __('tips.create_subtitle') }}
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('tips.store') }}" method="POST" enctype="multipart/form-data" class="px-0 py-6 sm:p-8">
            @csrf

            <!-- Category Selection -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-bold text-slate-700 dark:text-background-light mb-3">
                    {{ __('tips.category') }} <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                    @php
                        $categoryKeys = [
                            ['key' => 'alimentacion', 'icon' => 'restaurant', 'color' => 'orange'],
                            ['key' => 'energia', 'icon' => 'bolt', 'color' => 'amber'],
                            ['key' => 'naturaleza', 'icon' => 'eco', 'color' => 'green'],
                            ['key' => 'transporte', 'icon' => 'directions_bike', 'color' => 'teal'],
                            ['key' => 'hogar', 'icon' => 'home', 'color' => 'blue'],
                            ['key' => 'consumo', 'icon' => 'shopping_bag', 'color' => 'purple'],
                            ['key' => 'educacion', 'icon' => 'school', 'color' => 'rose'],
                            ['key' => 'residuos', 'icon' => 'recycling', 'color' => 'brown'],
                        ];
                    @endphp

                    @foreach($categoryKeys as $cat)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="category" value="{{ $cat['key'] }}" class="peer sr-only" {{ old('category') == $cat['key'] ? 'checked' : '' }} required>
                            <div class="p-4 border-2 border-slate-200 dark:border-custom-gray-border bg-white dark:bg-custom-dark-input rounded-xl transition-all peer-checked:border-{{ $cat['color'] }}-500 peer-checked:bg-{{ $cat['color'] }}-50 dark:peer-checked:bg-{{ $cat['color'] }}-900/20 hover:border-{{ $cat['color'] }}-300 flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-2xl text-slate-600 dark:text-sort-border-dark peer-checked:text-{{ $cat['color'] }}-600">{{ $cat['icon'] }}</span>
                                <span class="text-sm font-semibold text-slate-700 dark:text-background-light">{{ __('categories.' . $cat['key']) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('category')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-bold text-slate-700 dark:text-background-light mb-2">
                    {{ __('tips.title') }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
                    class="w-full px-4 py-3 bg-white dark:bg-custom-dark-input border-2 border-slate-200 dark:border-custom-gray-border rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base font-medium outline-none dark:text-white @error('title') border-red-500 @enderror"
                    placeholder="{{ __('tips.title_placeholder') }}"
                    maxlength="255"
                    required
                >
                @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-background-light mb-2">
                    {{ __('tips.description') }} <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="6"
                    class="w-full px-4 py-3 bg-white dark:bg-custom-dark-input border-2 border-slate-200 dark:border-custom-gray-border rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base resize-none outline-none dark:text-white @error('description') border-red-500 @enderror"
                    placeholder="{{ __('tips.description_placeholder') }}"
                    maxlength="1000"
                    required
                >{{ old('description') }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    @error('description')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    
                    @enderror
                    <span class="text-xs text-slate-400 dark:text-sort-border-dark" id="charCount">0 / 1000</span>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-8">
                <label for="image" class="block text-sm font-bold text-slate-700 dark:text-background-light mb-2">
                    {{ __('tips.image') }}
                </label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="image" 
                        id="image" 
                        accept="image/*"
                        class="hidden"
                        onchange="previewImage(event)"
                    >
                    <label 
                        for="image" 
                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-300 dark:border-custom-gray-border rounded-xl cursor-pointer hover:border-primary transition-all bg-slate-50 dark:bg-custom-dark-input"
                        id="imageLabel"
                    >
                        <div class="flex flex-col items-center" id="uploadPrompt">
                            <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-sort-border-dark mb-2">cloud_upload</span>
                            <p class="text-sm font-semibold text-slate-600 dark:text-sort-border-dark">{{ __('tips.choose_image') }}</p>
                            <p class="text-xs text-slate-500 dark:text-sort-text-light mt-1">{{ __('tips.image_hint') }}</p>
                        </div>
                        <div class="hidden w-full h-full" id="imagePreviewContainer">
                            <img id="imagePreview" class="w-full h-full object-cover rounded-xl" alt="Preview">
                        </div>
                    </label>
                    <button 
                        type="button" 
                        onclick="removeImage()" 
                        class="hidden absolute top-2 right-2 p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                        id="removeImageBtn"
                    >
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                @error('image')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-row gap-3 sm:gap-4 justify-end items-center w-full">
                <a 
                    href="{{ route('dashboard') }}"
                    class="flex-1 sm:flex-none sm:w-auto px-6 sm:px-10 py-3 border-2 border-slate-300 dark:border-custom-gray-border text-slate-700 dark:text-background-light font-bold rounded-full hover:bg-slate-100 dark:hover:bg-custom-dark-button transition-all flex items-center justify-center gap-2 text-sm"
                >
                    <span>{{ __('tips.cancel') }}</span>
                </a>    
                <button 
                    type="submit"
                    class="flex-1 sm:flex-none sm:w-auto px-8 sm:px-12 py-3 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 text-sm"
                >
                    <span>{{ __('tips.publish') }}</span>
                </button>
                
            </div>
        </form>
    </div>

    <!-- JavaScript for character count and image preview -->
    <script>
        // Character counter
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        
        descriptionTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = `${currentLength} / 1000`;
            
            if (currentLength > 900) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // Image preview
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('uploadPrompt').classList.add('hidden');
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                    document.getElementById('removeImageBtn').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').src = '';
            document.getElementById('uploadPrompt').classList.remove('hidden');
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('removeImageBtn').classList.add('hidden');
        }
    </script>
@endsection
