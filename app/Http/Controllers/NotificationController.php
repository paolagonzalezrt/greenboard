<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Obtener las notificaciones del usuario autenticado (para el dropdown de la nav)
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->unreadNotifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;

                // Extraer actor según tipo
                $type = $data['type'] ?? 'unknown';

                if ($type === 'new_follower') {
                    $actorName   = $data['follower_name']        ?? '';
                    $actorAvatar = $data['follower_avatar']       ?? null;
                    $actorColor  = $data['follower_avatar_color'] ?? 'bg-green-500';
                } elseif ($type === 'post_commented') {
                    $actorName   = $data['commenter_name']        ?? '';
                    $actorAvatar = $data['commenter_avatar']       ?? null;
                    $actorColor  = $data['commenter_avatar_color'] ?? 'bg-blue-500';
                } elseif ($type === 'comment_liked') {
                    $actorName   = $data['liker_name']        ?? '';
                    $actorAvatar = $data['liker_avatar']       ?? null;
                    $actorColor  = $data['liker_avatar_color'] ?? 'bg-purple-500';
                } else {
                    // post_liked y otros
                    $actorName   = $data['liker_name']        ?? '';
                    $actorAvatar = $data['liker_avatar']       ?? null;
                    $actorColor  = $data['liker_avatar_color'] ?? 'bg-amber-500';
                }

                return [
                    'id'               => $notification->id,
                    'type'             => $type,
                    'message'          => $data['message'] ?? '',
                    'url'              => $data['url']     ?? '#',
                    'read'             => !is_null($notification->read_at),
                    'read_at'          => $notification->read_at?->toIso8601String(),
                    'created_at'       => $notification->created_at->diffForHumans(),
                    'actor_name'       => $actorName,
                    'actor_avatar'     => $actorAvatar,
                    'actor_avatar_color' => $actorColor,
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'success'       => true,
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Marcar una notificación específica como leída
     */
    public function markAsRead(string $id)
    {
        $user         = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar notificaciones leídas hace más de N segundos
     * Llamado desde el frontend cuando el usuario cierra el dropdown
     */
}
