<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class PaginationHelper
{
    /**
     * Get the per page value from request or default
     * 
     * @param Request $request
     * @param int $default
     * @return int
     */
    public static function getPerPage(Request $request, int $default = 20): int
    {
        $perPage = $request->input('per_page', $default);
        $allowedValues = [10, 20, 50, 100];
        
        // Ensure the value is in the allowed list
        if (!in_array($perPage, $allowedValues)) {
            return $default;
        }
        
        return (int) $perPage;
    }

    /**
     * Get the available per page options
     * 
     * @return array
     */
    public static function getPerPageOptions(): array
    {
        return [10, 20, 50, 100];
    }
}
