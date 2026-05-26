<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    /**
     * El usuario que comentó
     */
    protected User $commenter;

    /**
     * El comentario realizado
     */
    protected Comment $comment;

    public function __construct(User $commenter, Comment $comment)
    {
        $this->commenter = $commenter;
        $this->comment   = $comment;
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
            'type'                  => 'post_commented',
            'commenter_id'          => $this->commenter->id,
            'commenter_name'        => $this->commenter->name,
            'commenter_avatar'      => $this->commenter->getAvatarUrl(),
            'commenter_avatar_color'=> $this->commenter->getAvatarBgColor(),
            'tip_id'                => $this->comment->tip_id,
            'tip_title'             => $this->comment->tip->title,
            'comment_preview'       => mb_substr($this->comment->content, 0, 80),
            'message'               => $this->commenter->name . ' comentó tu publicación "' . $this->comment->tip->title . '".',
            'url'                   => route('tips.show', $this->comment->tip_id),
        ];
    }
}
