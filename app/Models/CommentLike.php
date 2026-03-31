<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que dio like
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el comentario que recibió el like
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
