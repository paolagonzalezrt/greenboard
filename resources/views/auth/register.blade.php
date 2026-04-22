@extends('layouts.app')

@section('title', __('register.hero_title'))

@section('no-layout', true)

@section('content')
@php $currentLang = app()->getLocale(); @endphp

<div class="flex min-h-screen w-full flex-col lg:flex-row">

<!-- HERO SECTION (solo en pantallas grandes) -->

<div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary/10 h-screen lg:min-h-screen group">

<div class="absolute inset-0 bg-cover bg-center z-0 transition-transform duration-700 group-hover:scale-105"
style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80');"></div>

<div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent z-10"></div>

<div class="relative z-20 flex flex-col justify-end p-10 lg:p-16 w-full h-full">

<div class="flex items-center gap-2 mb-6">
<span class="material-symbols-outlined text-primary text-4xl">eco</span>
<span class="text-white text-3xl font-extrabold tracking-tight">GreenBoard</span>
</div>

<h1 class="text-white text-4xl lg:text-5xl font-black leading-tight mb-4">
{{ __('register.hero_title') }}
</h1>

<p class="text-slate-200 text-lg max-w-md mb-6 lg:mb-0">
{{ __('register.hero_desc') }}
</p>

</div>
</div>


<!-- FORM SECTION -->

<div id="form-section" class="flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-20 bg-background-light dark:bg-background-dark fade-in relative">

<!-- TOP CONTROLS -->

<div class="w-full max-w-[440px] flex items-center justify-between mb-8 absolute top-8 px-6 lg:px-0 lg:static lg:mb-12">

<a href="{{ route('home') }}" class="flex items-center gap-1.5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-xl">explore</span>
{{ __('register.explore') }}
</a>

<div class="flex items-center gap-1 sm:gap-2">

<!-- LANGUAGE -->

<div class="relative">

<button onclick="toggleLangDropdown()" class="flex items-center gap-1 text-xs font-black text-slate-600 dark:text-slate-400 hover:text-primary transition-colors p-2 flex items-center justify-center">
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

<!-- DARK MODE -->

<button onclick="toggleDarkMode()" class="p-2 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
</button>

</div>

</div>


<div class="w-full max-w-[440px] mt-20 lg:mt-0">

<div class="mb-10 text-center lg:text-left">

<h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">
{{ __('register.form_title') }}
</h2>

<p class="text-slate-600 dark:text-slate-400">
{{ __('register.form_desc') }}
</p>

</div>

<form method="POST" action="{{ route('register') }}" class="space-y-5">

@csrf

<!-- NAME -->

<div>

<label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
{{ __('register.name') }}
</label>

<div class="relative">

<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
person
</span>

<input
name="name"
value="{{ old('name') }}"
required
placeholder="{{ __('register.name_placeholder') }}"
class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-custom-dark-input border @error('name') border-red-500 dark:border-red-600 @else border-slate-200 dark:border-custom-dark-button @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">

</div>

@include('components.field-error', ['fieldName' => 'name'])

</div>


<!-- EMAIL -->

<div>

<label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
{{ __('register.email') }}
</label>

<div class="relative">

<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
mail
</span>

<input
name="email"
value="{{ old('email') }}"
required
type="email"
placeholder="{{ __('register.email_placeholder') }}"
class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-custom-dark-input border @error('email') border-red-500 dark:border-red-600 @else border-slate-200 dark:border-custom-dark-button @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">

</div>

@include('components.field-error', ['fieldName' => 'email'])

</div>


<!-- PASSWORD -->

<div>

<label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
{{ __('register.password') }}
</label>

<div class="relative">

<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
lock
</span>

<input
id="register-password"
name="password"
required
type="password"
placeholder="{{ __('register.password_placeholder') }}"
class="w-full pl-12 pr-14 py-3.5 bg-white dark:bg-custom-dark-input border @error('password') border-red-500 dark:border-red-600 @else border-slate-200 dark:border-custom-dark-button @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">

<button type="button" onclick="togglePasswordVisibility('register-password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
    <span class="material-symbols-outlined text-xl" id="register-password-icon" style="font-variation-settings: 'wght' 200">visibility_off</span>
</button>

</div>

@include('components.field-error', ['fieldName' => 'password'])

</div>


<!-- CONFIRM PASSWORD -->

<div>

<label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
{{ __('register.confirm_password') }}
</label>

<div class="relative">

<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
lock
</span>

<input
id="register-password-confirm"
name="password_confirmation"
required
type="password"
placeholder="{{ __('register.confirm_password_placeholder') }}"
class="w-full pl-12 pr-14 py-3.5 bg-white dark:bg-custom-dark-input border @error('password_confirmation') border-red-500 dark:border-red-600 @else border-slate-200 dark:border-custom-dark-button @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">

<button type="button" onclick="togglePasswordVisibility('register-password-confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
    <span class="material-symbols-outlined text-xl" id="register-password-confirm-icon" style="font-variation-settings: 'wght' 200">visibility_off</span>
</button>

</div>

@include('components.field-error', ['fieldName' => 'password_confirmation'])

</div>


<button class="block w-2/5 sm:w-1/3 mx-auto bg-primary text-slate-900 font-extrabold text-sm py-3 rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-[0.95] !mt-8" type="submit">
{{ __('register.signup_button') }}
</button>

</form>


<p class="flex items-center justify-center gap-1.5 text-center mt-8 text-slate-600 dark:text-slate-400">
    <span>
        {{ __('register.have_account') }}
    </span>

    <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">
        {{ __('register.login') }}
    </a>
</p>

</div>
</div>
</div>


<script>

function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerText = 'visibility';
    } else {
        input.type = 'password';
        icon.innerText = 'visibility_off';
    }
}

function toggleLangDropdown(){
const dropdown=document.getElementById('lang-dropdown');
dropdown.classList.toggle('hidden');
}

</script>
@endsection
