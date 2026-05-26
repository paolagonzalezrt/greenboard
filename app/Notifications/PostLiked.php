<?php

namespace App\Notifications;

use App\Models\Tip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    /**
     * El usuario que dio like
     */
    protected User $liker;

    /**
     * El tip que recibió el like
     */
    protected Tip $tip;

    public function __construct(User $liker, Tip $tip)
    {
        $this->liker = $liker;
        $this->tip   = $tip;
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
            'type'         => 'post_liked',
            'liker_id'     => $this->liker->id,
            'liker_name'   => $this->liker->name,
            'liker_avatar' => $this->liker->getAvatarUrl(),
            'liker_avatar_color' => $this->liker->getAvatarBgColor(),
            'tip_id'       => $this->tip->id,
            'tip_title'    => $this->tip->title,
            'message'      => $this->liker->name . ' reaccionó a tu publicación "' . $this->tip->title . '".',
            'url'          => route('tips.show', $this->tip->id),
        ];
    }
}
