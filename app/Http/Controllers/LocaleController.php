<?php

namespace App\Http\Controllers;

use App\Services\Translation\LocaleService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class LocaleController extends Controller
{
    public function __construct(
        private LocaleService $localeService
    ) {
    }

    /**
     * Cambiar el idioma de la aplicación.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validar que el locale es soportado
        if (!$this->localeService->isSupported($locale)) {
            return redirect()->back();
        }

        // Cambiar locale (guarda en sesión y BD si autenticado)
        $this->localeService->setLocale($locale);

        // Debug log
        \Illuminate\Support\Facades\Log::info('Locale switched', [
            'locale' => $locale,
            'session_locale' => session()->get('locale'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }

    /**
     * Cambiar idioma vía AJAX.
     */
    public function switchAjax(Request $request): JsonResponse
    {
        $locale = $request->input('locale');

        if (!$locale) {
            return response()->json([
                'success' => false,
                'message' => 'Locale is required',
            ], 400);
        }

        if ($this->localeService->setLocale($locale)) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => __('messages.success.saved'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid locale',
        ], 400);
    }

    /**
     * Obtener idiomas disponibles.
     */
    public function getAvailableLocales(): JsonResponse
    {
        return response()->json([
            'locales' => $this->localeService->getSupportedLocales(),
            'current' => $this->localeService->getCurrentLocale(),
        ]);
    }
}
