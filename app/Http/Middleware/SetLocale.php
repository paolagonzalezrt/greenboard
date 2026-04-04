<?php

namespace App\Http\Middleware;

use App\Services\Translation\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
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
        // Prioridad de locale:
        // 1. URL parameter (si viene en la ruta)
        // 2. Query parameter: ?locale=es
        // 3. Session (guardado del cambio anterior)
        // 4. User preference (BD si autenticado)
        // 5. Default locale

        $locale = null;

        // Verificar route parameter (ej: /locale/es)
        if ($request->route() && $request->route()->parameter('locale')) {
            $locale = $request->route()->parameter('locale');
        }
        // Verificar query parameter
        elseif ($request->has('locale')) {
            $locale = $request->input('locale');
        }
        // Usar preferencia guardada (sesión o BD)
        else {
            $locale = $this->localeService->getUserPreferredLocale();
        }

        // Aplicar locale
        if ($locale && $this->localeService->isSupported($locale)) {
            $this->localeService->setLocale($locale, false, false); // No guardar de nuevo en sesión
        } else {
            // Inicializar con preferencia del usuario
            $this->localeService->initializeLocale();
        }

        return $next($request);
    }
}

