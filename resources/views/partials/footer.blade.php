<footer class="py-8 sm:py-12 lg:py-16 text-center text-slate-400 text-xs sm:text-sm mt-8 sm:mt-12">
    <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6 px-4">
        <a class="hover:text-primary transition-colors" href="{{ route('privacy-policy') }}">{{ __('pages.privacy_title') }}</a>
        <a class="hover:text-primary transition-colors" href="{{ route('about-us') }}">{{ __('pages.about_title') }}</a>
    </div>
    <p class="px-4">{{ __('components.footer_copyright') }}</p>
</footer>
