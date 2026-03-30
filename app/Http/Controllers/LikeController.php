<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Dar o quitar like a un tip
     */
    public function toggle(Tip $tip)
    {
        $user = Auth::user();

        // Verificar si el usuario ya dio like a este tip
        $existingLike = Like::where('user_id', $user->id)
            ->where('tip_id', $tip->id)
            ->first();

        if ($existingLike) {
            // Si ya existe, eliminar el like
            $existingLike->delete();
            $liked = false;
        } else {
            // Si no existe, crear el like
            Like::create([
                'user_id' => $user->id,
                'tip_id' => $tip->id,
            ]);
            $liked = true;
        }

        // Contar el total de likes del tip
        $likesCount = $tip->likes()->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
