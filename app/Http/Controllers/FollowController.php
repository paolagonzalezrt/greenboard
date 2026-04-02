<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow a user
     */
    public function toggle(User $user)
    {
        $currentUser = Auth::user();

        // No puedes seguirte a ti mismo
        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes seguirte a ti mismo.'
            ], 400);
        }

        // Verificar si ya sigue al usuario
        $isFollowing = $currentUser->isFollowing($user->id);

        if ($isFollowing) {
            // Dejar de seguir
            $currentUser->following()->detach($user->id);
            $message = 'Has dejado de seguir a ' . $user->name;
            $following = false;
        } else {
            // Seguir
            $currentUser->following()->attach($user->id);
            $message = 'Ahora sigues a ' . $user->name;
            $following = true;
        }

        // Contar seguidores y seguidos
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'following' => $following,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
        ]);
    }

    /**
     * Get followers list
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->select('users.id', 'users.name', 'users.email', 'users.photo', 'users.created_at')
            ->get()
            ->map(function ($follower) {
                $currentUser = Auth::user();
                return [
                    'id' => $follower->id,
                    'name' => $follower->name,
                    'avatar' => $follower->getAvatarUrl(),
                    'has_photo' => $follower->hasProfilePhoto(),
                    'avatar_color' => $follower->getAvatarBgColor(),
                    'is_following' => $currentUser ? $currentUser->isFollowing($follower->id) : false,
                ];
            });

        return response()->json([
            'success' => true,
            'followers' => $followers,
        ]);
    }

    /**
     * Get following list
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->select('users.id', 'users.name', 'users.email', 'users.photo', 'users.created_at')
            ->get()
            ->map(function ($followedUser) {
                $currentUser = Auth::user();
                return [
                    'id' => $followedUser->id,
                    'name' => $followedUser->name,
                    'avatar' => $followedUser->getAvatarUrl(),
                    'has_photo' => $followedUser->hasProfilePhoto(),
                    'avatar_color' => $followedUser->getAvatarBgColor(),
                    'is_following' => $currentUser ? $currentUser->isFollowing($followedUser->id) : false,
                ];
            });

        return response()->json([
            'success' => true,
            'following' => $following,
        ]);
    }

    /**
     * Remove a follower
     */
    public function removeFollower(User $follower)
    {
        $currentUser = Auth::user();

        // Verificar si el follower realmente sigue al usuario actual
        if (!$follower->isFollowing($currentUser->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Este usuario no te sigue.'
            ], 400);
        }

        // Remover el follow
        $follower->following()->detach($currentUser->id);

        return response()->json([
            'success' => true,
            'message' => 'Seguidor eliminado exitosamente.',
            'followers_count' => $currentUser->followers()->count(),
        ]);
    }
}
