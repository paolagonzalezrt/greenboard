<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'photo',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Relación con los tips publicados
     */
    public function tips()
    {
        return $this->hasMany(Tip::class);
    }

    /**
     * Relación con los comentarios
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relación con los likes dados
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relación con los tips guardados
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Relación con los reportes realizados
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Tips que le gustan al usuario
     */
    public function likedTips()
    {
        return $this->belongsToMany(Tip::class, 'likes')->withTimestamps();
    }

    /**
     * Tips guardados por el usuario
     */
    public function bookmarkedTips()
    {
        return $this->belongsToMany(Tip::class, 'bookmarks')->withTimestamps();
    }

    /**
     * Usuarios que este usuario está siguiendo
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Usuarios que siguen a este usuario
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Verificar si este usuario sigue a otro usuario
     */
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    /**
     * Verificar si este usuario es seguido por otro usuario
     */
    public function isFollowedBy($userId)
    {
        return $this->followers()->where('follower_id', $userId)->exists();
    }

    /**
     * Verificar si el usuario ha dado like a un tip
     */
    public function hasLiked($tip)
    {
        $tipId = is_object($tip) ? $tip->id : $tip;
        return $this->likedTips()->where('tip_id', $tipId)->exists();
    }

    /**
     * Verificar si el usuario ha guardado un tip
     */
    public function hasBookmarked($tip)
    {
        $tipId = is_object($tip) ? $tip->id : $tip;
        return $this->bookmarkedTips()->where('tip_id', $tipId)->exists();
    }

    /**
     * Relación con los likes de comentarios dados
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Comentarios que le gustan al usuario
     */
    public function likedComments()
    {
        return $this->belongsToMany(Comment::class, 'comment_likes')->withTimestamps();
    }

    /**
     * Verificar si el usuario ha dado like a un comentario
     */
    public function hasLikedComment($comment)
    {
        $commentId = is_object($comment) ? $comment->id : $comment;
        return $this->likedComments()->where('comment_id', $commentId)->exists();
    }
}
