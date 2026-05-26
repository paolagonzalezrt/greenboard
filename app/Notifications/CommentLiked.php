<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentLiked extends Notification
{
    use Queueable;

    /**
     * El usuario que dio like al comentario
     */
    protected User $liker;

    /**
     * El comentario que recibió el like
     */
    protected Comment $comment;

    public function __construct(User $liker, Comment $comment)
    {
        $this->liker = $liker;
        $this->comment = $comment;
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
            'type'               => 'comment_liked',
            'liker_id'           => $this->liker->id,
            'liker_name'         => $this->liker->name,
            'liker_avatar'       => $this->liker->getAvatarUrl(),
            'liker_avatar_color' => $this->liker->getAvatarBgColor(),
            'comment_id'         => $this->comment->id,
            'tip_id'             => $this->comment->tip_id,
            'comment_preview'    => mb_substr($this->comment->content, 0, 80),
            'message'            => $this->liker->name . ' reaccionó a tu comentario.',
            'url'                => route('tips.show', $this->comment->tip_id), // Redirige al tip donde está el comentario
        ];
    }
}
