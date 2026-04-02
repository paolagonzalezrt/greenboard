<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tip_id',
        'parent_id',
        'content',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que comentó
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el tip comentado
     */
    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }

    /**
     * Relación con el comentario padre (para respuestas)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relación con las respuestas (comentarios hijos)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user')->orderBy('created_at', 'asc');
    }

    /**
     * Verificar si es una respuesta
     */
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Relación con los likes del comentario
     */
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Usuarios que han dado like a este comentario
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'comment_likes')->withTimestamps();
    }

    /**
     * Relación con los reportes del comentario
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
