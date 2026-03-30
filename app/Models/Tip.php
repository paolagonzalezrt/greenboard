<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'description',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que publicó el tip
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los comentarios
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relación con los likes
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relación con los bookmarks
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Relación con los reportes
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Obtener el número de likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Obtener el número de comentarios
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * Verificar si un usuario le ha dado like
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Verificar si un usuario lo ha guardado
     */
    public function isBookmarkedBy($user)
    {
        if (!$user) return false;
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }
}
