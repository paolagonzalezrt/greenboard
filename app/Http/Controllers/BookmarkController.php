<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Toggle bookmark for a tip
     */
    public function toggle(Tip $tip)
    {
        $user = Auth::user();
        
        // Check if the user has already bookmarked this tip
        $bookmark = $user->bookmarks()->where('tip_id', $tip->id)->first();
        
        if ($bookmark) {
            // Remove bookmark
            $bookmark->delete();
            $bookmarked = false;
        } else {
            // Add bookmark
            $user->bookmarks()->create([
                'tip_id' => $tip->id
            ]);
            $bookmarked = true;
        }
        
        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked
        ]);
    }
}
