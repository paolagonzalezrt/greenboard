@extends('layouts.app')

@section('title', 'Editar Perfil - GreenBoard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100">{{ __('profile.edit_title') }}</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">{{ __('profile.edit_subtitle') }}</p>
        </div>

        {{-- Success message removed to avoid duplication with the global floating notifier in the layout --}}

        <!-- Update Profile Information -->
        <div class="bg-white dark:bg-custom-dark-button rounded-2xl shadow-lg p-6 sm:p-8 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('profile.profile_info_title') }}</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('profile.profile_info_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Profile Photo Preview -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">{{ __('profile.profile_photo') }}</label>
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col items-center">
                            <div id="photo-preview-container" class="hidden size-24 rounded-full shadow-lg overflow-hidden sm:border-4 sm:border-white sm:dark:border-slate-800">
                                <img id="photo-preview" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" />
                            </div>
                            <div id="avatar-preview">
                                <x-profile-avatar :user="Auth::user()" size="xl" />
                            </div>
                        </div>
                        <div>
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                            <label for="photo" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-custom-dark-input text-slate-900 dark:text-slate-100 rounded-lg font-semibold text-sm cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-600">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                {{ __('profile.change_photo') }}
                            </label>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('profile.photo_formats') }}</p>
                        </div>
                    </div>
                    @error('photo')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="mb-6">
                    <label for="bio" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.bio') }}</label>
                    <textarea name="bio" id="bio" rows="4" maxlength="500"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"
                        placeholder="{{ __('profile.bio_placeholder') }}">{{ old('bio', Auth::user()->bio) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><span id="bio-count">{{ strlen(Auth::user()->bio ?? '') }}</span>/{{ __('profile.characters_limit') }}</p>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-background-dark rounded-lg font-bold hover:brightness-105 transition-all shadow-md">
                        <span class="material-symbols-outlined">save</span>
                        {{ __('profile.save_changes') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-white dark:bg-custom-dark-button rounded-2xl shadow-lg p-6 sm:p-8 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ __('profile.update_password_title') }}</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('profile.update_password_subtitle') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.updatePassword') }}">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.current_password') }}</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.new_password') }}</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-custom-dark-input border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-background-dark rounded-lg font-bold hover:brightness-105 transition-all shadow-md">
                        <span class="material-symbols-outlined">lock</span>
                        {{ __('profile.update_password_button') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-white dark:bg-custom-dark-button rounded-2xl shadow-lg p-6 sm:p-8 border-2 border-red-200 dark:border-red-900">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-red-600 dark:text-red-400">{{ __('profile.delete_account_title') }}</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('profile.delete_account_warning') }}</p>
            </div>

            <button type="button" onclick="openDeleteModal()" class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all shadow-md">
                <span class="material-symbols-outlined">delete_forever</span>
                {{ __('profile.delete_account_button') }}
            </button>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-red-600 text-3xl">warning</span>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ __('profile.confirm_deletion') }}</h3>
                </div>
                <p class="text-slate-600 dark:text-slate-400">{{ __('profile.delete_confirmation') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="mb-6">
                    <label for="delete_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('profile.confirm_password_label') }}</label>
                    <input type="password" name="password" id="delete_password" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-lg font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-all">
                        {{ __('profile.cancel') }}
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all">
                        {{ __('profile.delete_account_confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview Photo
        function previewPhoto(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').style.display = 'none';
                    document.getElementById('photo-preview-container').classList.remove('hidden');
                    document.getElementById('photo-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Bio Character Counter
        const bioTextarea = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');
        
        if (bioTextarea && bioCount) {
            bioTextarea.addEventListener('input', function() {
                bioCount.textContent = this.value.length;
            });
        }

        // Delete Modal Functions
        function openDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on background click
        document.getElementById('delete-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
