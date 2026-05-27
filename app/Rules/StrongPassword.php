<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        // Longitud
        if (mb_strlen($password) < 8 || mb_strlen($password) > 128) {
            $fail(__('register.error_password_length'));
            return;
        }

        // Unicode uppercase
        $hasUppercase = preg_match('/\p{Lu}/u', $password);

        // Unicode lowercase
        $hasLowercase = preg_match('/\p{Ll}/u', $password);

        // Unicode number
        $hasNumber = preg_match('/\p{N}/u', $password);

        // Unicode symbols/punctuation
        $hasSpecial = preg_match('/[\p{P}\p{S}]/u', $password);

        if (
            !$hasUppercase ||
            !$hasLowercase ||
            !$hasNumber ||
            !$hasSpecial
        ) {
            $fail(__('register.error_password_requirements'));
        }
    }
}