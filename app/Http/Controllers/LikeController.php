<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\Like;
use App\Notifications\PostLiked;
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
                'tip_id'  => $tip->id,
            ]);
            $liked = true;

            // Notificar al autor del tip (si no es el mismo usuario)
            if ($tip->user_id !== $user->id) {
                $tip->user->notify(new PostLiked($user, $tip));
            }
        }

        // Contar el total de likes del tip
        $likesCount = $tip->likes()->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    /**
     * Obtener la lista de usuarios que dieron like a un tip
     */
    public function getLikers(Tip $tip)
    {
        $likers = $tip->likes()
            ->with('user:id,name,photo')
            ->get()
            ->map(function($like) {
                return [
                    'id' => $like->user->id,
                    'name' => $like->user->name,
                    'photo' => $like->user->photo,
                    'avatar_url' => $like->user->getAvatarUrl(),
                    'avatar_bg_color' => $like->user->getAvatarBgColor(),
                ];
            });

        return response()->json([
            'success' => true,
            'likers' => $likers,
            'count' => $likers->count(),
        ]);
    }
}
