<footer class="py-6 sm:py-8 text-slate-400 text-xs sm:text-sm mt-8 sm:mt-12">
    <div class="flex flex-col items-center gap-3 text-center">
        <a class="hover:text-primary transition-colors whitespace-nowrap" href="{{ route('privacy-policy') }}">{{ __('pages.privacy_title') }}</a>
        <a class="hover:text-primary transition-colors whitespace-nowrap" href="{{ route('terms') }}">{{ __('pages.terms_title') }}</a>
        <a class="hover:text-primary transition-colors whitespace-nowrap" href="{{ route('about-us') }}">{{ __('pages.about_title') }}</a>
        <p class="text-[11px] tracking-widest uppercase text-slate-400/60 dark:text-slate-500/60 mt-1">
            {{ __('components.footer_copyright') }}
        </p>
    </div>
</footer>
