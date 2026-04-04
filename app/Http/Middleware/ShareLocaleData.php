<?php

namespace App\Http\Middleware;

use App\Services\Translation\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareLocaleData
{
    public function __construct(
        private LocaleService $localeService
    ) {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Compartir datos de localización con todas las vistas
        View::share([
            'availableLocales' => $this->localeService->getSupportedLocales(),
            'currentLocale' => $this->localeService->getCurrentLocale(),
        ]);

        return $next($request);
    }
}
