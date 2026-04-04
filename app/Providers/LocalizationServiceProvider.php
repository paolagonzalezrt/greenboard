<?php

namespace App\Providers;

use App\Services\Translation\LocaleService;
use App\Services\Translation\TranslationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar LocaleService como singleton
        $this->app->singleton(LocaleService::class, function ($app) {
            return new LocaleService();
        });

        // Registrar TranslationService como singleton
        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService();
        });

        // Alias más cortos
        $this->app->alias(LocaleService::class, 'locale');
        $this->app->alias(TranslationService::class, 'translator.dynamic');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Directiva Blade para traducción dinámica
        Blade::directive('trans_dynamic', function ($expression) {
            return "<?php echo app(\App\Services\Translation\TranslationService::class)->translate($expression, app()->getLocale()); ?>";
        });

        // Directiva para selector de idiomas
        Blade::directive('localeSelector', function () {
            return "<?php echo view('components.locale-selector')->render(); ?>";
        });

        // Compartir datos de localización con todas las vistas
        view()->composer('*', function ($view) {
            $localeService = app(LocaleService::class);
            $view->with([
                'currentLocale' => $localeService->getCurrentLocale(),
                'availableLocales' => $localeService->getSupportedLocales(),
            ]);
        });
    }
}
