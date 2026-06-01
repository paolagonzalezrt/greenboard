@extends('layouts.app')

@section('title', 'GreenBoard - ' . __('pages.terms_title'))

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6">
    <header class="mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-4">
            {{ __('pages.terms_title') }}
        </h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest">
            {{ __('pages.terms_updated') }}
        </p>
    </header>
    
    <div class="space-y-12 text-slate-600 dark:text-slate-300 leading-relaxed">
        <section>
            <p class="text-lg md:text-xl text-slate-500 dark:text-slate-400 italic text-justify">
                {{ __('pages.terms_intro') }}
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_1_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_1_content') }}</p>
        </section>
        
        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_2_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_2_content') }}</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_3_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_3_content') }}</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_4_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_4_content') }}</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_5_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_5_content') }}</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_section_6_title') }}</h2>
            <p class="text-justify">{{ __('pages.terms_section_6_content') }}</p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ __('pages.terms_contact_title') }}</h2>
            <p class="text-justify">
                {{ __('pages.terms_contact_content') }}
                <a href="mailto:{{ __('pages.contact_email') }}" class="text-primary font-semibold hover:underline">
                    {{ __('pages.contact_email') }}
                </a>
            </p>
        </section>
    </div>
</div>
@endsection
