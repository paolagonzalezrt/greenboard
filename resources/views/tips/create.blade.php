@extends('layouts.app')

@section('title', 'Create Post - GreenBoard')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
                <span class="font-semibold">Back</span>
            </a>
        </div>
        <!-- Header -->
        <div class="mb-8 sm:mb-10 text-center">
            <div class="flex justify-center items-center gap-3 mb-4">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Create New Post</h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg">
                Share your sustainable living tips with the community
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('tips.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg p-6 sm:p-8">
            @csrf

            <!-- Category Selection -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">
                    Category <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $categories = [
                            ['name' => 'Home', 'icon' => 'home', 'color' => 'blue'],
                            ['name' => 'Energy', 'icon' => 'bolt', 'color' => 'amber'],
                            ['name' => 'Consumption', 'icon' => 'shopping_bag', 'color' => 'purple'],
                            ['name' => 'Transport', 'icon' => 'directions_bike', 'color' => 'emerald'],
                            ['name' => 'Food', 'icon' => 'restaurant', 'color' => 'orange'],
                            ['name' => 'Zero Waste', 'icon' => 'recycling', 'color' => 'green'],
                        ];
                    @endphp

                    @foreach($categories as $cat)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="category" value="{{ $cat['name'] }}" class="peer sr-only" {{ old('category') == $cat['name'] ? 'checked' : '' }} required>
                            <div class="p-4 border-2 border-slate-200 dark:border-slate-600 rounded-xl transition-all peer-checked:border-{{ $cat['color'] }}-500 peer-checked:bg-{{ $cat['color'] }}-50 dark:peer-checked:bg-{{ $cat['color'] }}-900/20 hover:border-{{ $cat['color'] }}-300 flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-2xl text-slate-600 dark:text-slate-400 peer-checked:text-{{ $cat['color'] }}-600">{{ $cat['icon'] }}</span>
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $cat['name'] }}</span>
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
                <label for="title" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base font-medium outline-none @error('title') border-red-500 @enderror"
                    placeholder="Give your tip a catchy title..."
                    maxlength="255"
                    required
                >
                @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="6"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-base resize-none outline-none @error('description') border-red-500 @enderror"
                    placeholder="Describe your sustainable tip in detail..."
                    maxlength="1000"
                    required
                >{{ old('description') }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    @error('description')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">Share practical tips that can help others</p>
                    @enderror
                    <span class="text-xs text-slate-400" id="charCount">0 / 1000</span>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-8">
                <label for="image" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Image (optional)
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
                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer hover:border-primary transition-all bg-slate-50 dark:bg-slate-900/50"
                        id="imageLabel"
                    >
                        <div class="flex flex-col items-center" id="uploadPrompt">
                            <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">cloud_upload</span>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Click to upload an image</p>
                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">PNG, JPG, WEBP up to 5MB</p>
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
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
             <a 
                    href="{{ route('dashboard') }}"
                    class="px-6 py-4 border-2 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-2"
                >
                    
                    <span>Cancel</span>
                    
                </a>    
            <button 
                    type="submit"
                    class="flex-1 px-6 py-4 bg-primary text-background-dark font-bold rounded-full hover:brightness-105 shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2"
                >
                    
                    <span>Publish Post</span>
                    
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
