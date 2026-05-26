<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFollower extends Notification
{
    use Queueable;

    /**
     * El usuario que comenzó a seguir
     */
    protected User $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    /**
     * Canales por los que se enviará la notificación
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Datos que se guardarán en la base de datos
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'new_follower',
            'follower_id'  => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_avatar' => $this->follower->getAvatarUrl(),
            'follower_avatar_color' => $this->follower->getAvatarBgColor(),
            'message'      => $this->follower->name . ' comenzó a seguirte.',
            'url'          => route('users.show', $this->follower->id),
        ];
    }
}
