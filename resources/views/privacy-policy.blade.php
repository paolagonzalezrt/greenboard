@extends('layouts.app')

@section('title', 'GreenBoard - Privacy Policy')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-8 bg-gradient-to-br from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
        {{ __('pages.privacy_title') }}
    </h1>
    
    <div class="prose dark:prose-invert max-w-none">
        <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">
            {{ __('pages.privacy_intro') }}
        </p>
        
        <h2 class="text-2xl font-bold mb-4 mt-8">{{ __('pages.data_collection') }}</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-4">
            Content about information collection...
        </p>
        
        <h2 class="text-2xl font-bold mb-4 mt-8">{{ __('pages.data_usage') }}</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-4">
            Content about how information is used...
        </p>
        
        <h2 class="text-2xl font-bold mb-4 mt-8">{{ __('pages.contact_us') }}</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-4">
            If you have any questions about this {{ __('pages.privacy_title') }}, please contact us at {{ __('pages.privacy_email') }}.
        </p>
    </div>
</div>
@endsection
