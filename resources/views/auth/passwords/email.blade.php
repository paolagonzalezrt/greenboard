@extends('layouts.app')

@section('title', __('passwords.form_title'))

@section('no-layout', true)

@section('content')
@php $currentLang = app()->getLocale(); @endphp

<div class="flex min-h-screen w-full flex-col lg:flex-row">

    <!-- FORM SECTION -->
    <div id="form-section" class="flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-20 bg-background-light dark:bg-background-dark fade-in relative">

        <!-- TOP CONTROLS -->
         <div class="w-full max-w-[440px] flex items-center justify-end mb-8 absolute top-8 px-6 lg:px-0 lg:static lg:mb-12">
                <div class="flex items-center gap-1 sm:gap-2">
                    <div class="relative">
                        <button onclick="toggleLangDropdown()" class="flex items-center gap-1 text-xs font-black text-slate-600 dark:text-slate-400 hover:text-primary transition-colors p-2">
                            <span class="material-symbols-outlined text-xl">translate</span>
                        </button>
                        <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-custom-dark-input border border-slate-200 dark:border-custom-dark-button rounded-xl shadow-xl overflow-hidden z-50">
                            @foreach($availableLocales as $code => $locale)
                                <a href="{{ route('locale.switch', $code) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-primary hover:text-white transition-colors {{ $currentLocale === $code ? 'bg-primary/10 text-primary' : '' }}">
                                    <span class="text-lg">{{ $locale['flag'] }}</span>
                                    <span>{{ $locale['native'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <button onclick="toggleDarkMode()" class="p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
                    </button>
                </div>
            </div>

        <div class="w-full max-w-[440px] mt-20 lg:mt-0">
            <div class="mb-8 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                    {{ __('passwords.form_title') }}
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm">
                    {{ __('passwords.form_desc') }}
                </p>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl flex-shrink-0">check_circle</span>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">{{ __('passwords.sent') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('passwords.email') }}
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="{{ __('passwords.email_placeholder') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-custom-dark-input border @error('email') border-red-500 dark:border-red-600 @else border-slate-200 dark:border-custom-dark-button @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
                    </div>
                    @include('components.field-error', ['fieldName' => 'email'])
                </div>

               
                 <button type="submit" class="block mx-auto w-fit px-8 bg-primary text-slate-900 font-extrabold text-sm py-3 rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-[0.95] !mt-8">
                {{ __('passwords.send_button') }}
                </button>
            </form>

            <p class="flex items-center justify-center gap-1.5 text-center mt-8 mb-8 text-slate-600 dark:text-slate-400">
                <!-- <span class="material-symbols-outlined text-base">arrow_back</span> -->
                <a class="text-primary font-bold hover:underline text-sm" href="{{ route('login') }}">
                    {{ __('passwords.back_to_login') }}
                </a>
            </p>
        </div>
    </div>
</div>

<script>
function toggleLangDropdown() {
    const dropdown = document.getElementById('lang-dropdown');
    dropdown.classList.toggle('hidden');
}
</script>
@endsection
