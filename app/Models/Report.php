<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'tip_id',
        'comment_id',
        'reason',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que reportó
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el tip reportado
     */
    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }

    /**
     * Relación con el comentario reportado
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
