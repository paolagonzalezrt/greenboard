<?php

namespace App\Services\Translation;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LocaleService
{
    /**
     * Obtener los idiomas soportados.
     */
    public function getSupportedLocales(): array
    {
        return config('localization.supported_locales', []);
    }

    /**
     * Obtener el idioma actual.
     */
    public function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Obtener información del idioma actual.
     */
    public function getCurrentLocaleInfo(): array
    {
        $locale = $this->getCurrentLocale();
        $locales = $this->getSupportedLocales();

        return $locales[$locale] ?? [
            'name' => $locale,
            'native' => $locale,
            'flag' => '',
        ];
    }

    /**
     * Verificar si un idioma está soportado.
     */
    public function isSupported(string $locale): bool
    {
        return array_key_exists($locale, $this->getSupportedLocales());
    }

    /**
     * Cambiar el idioma de la aplicación.
     */
    public function setLocale(string $locale, bool $persistInSession = true, bool $persistInDatabase = true): bool
    {
        if (!$this->isSupported($locale)) {
            return false;
        }

        // Cambiar idioma en la aplicación
        App::setLocale($locale);

        // Persistir en sesión
        if ($persistInSession) {
            Session::put('locale', $locale);
        }

        // Persistir en base de datos si el usuario está autenticado
        if ($persistInDatabase && Auth::check()) {
            Auth::user()->update(['preferred_locale' => $locale]);
        }

        return true;
    }

    /**
     * Obtener el idioma preferido del usuario.
     */
    public function getUserPreferredLocale(): string
    {
        // 1. Primero verificar sesión
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            if ($this->isSupported($sessionLocale)) {
                return $sessionLocale;
            }
        }

        // 2. Luego verificar preferencia del usuario en BD
        if (Auth::check() && Auth::user()->preferred_locale) {
            $userLocale = Auth::user()->preferred_locale;
            if ($this->isSupported($userLocale)) {
                return $userLocale;
            }
        }

        // 3. Finalmente, usar el idioma por defecto
        return config('localization.default_locale', 'es');
    }

    /**
     * Detectar idioma del navegador.
     */
    public function detectBrowserLocale(): ?string
    {
        $acceptLanguage = request()->header('Accept-Language');

        if (!$acceptLanguage) {
            return null;
        }

        // Parsear el header Accept-Language
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $part) {
            $part = trim($part);
            $q = 1.0;

            if (str_contains($part, ';q=')) {
                [$lang, $quality] = explode(';q=', $part);
                $q = (float) $quality;
                $part = $lang;
            }

            // Obtener solo el código de idioma principal (ej: "es-MX" -> "es")
            $locale = substr($part, 0, 2);
            $languages[$locale] = $q;
        }

        // Ordenar por calidad (q value)
        arsort($languages);

        // Buscar el primer idioma soportado
        foreach (array_keys($languages) as $locale) {
            if ($this->isSupported($locale)) {
                return $locale;
            }
        }

        return null;
    }

    /**
     * Inicializar el idioma para la petición actual.
     */
    public function initializeLocale(): string
    {
        $locale = $this->getUserPreferredLocale();
        App::setLocale($locale);

        return $locale;
    }
}
