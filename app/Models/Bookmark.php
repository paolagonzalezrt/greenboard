<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = [
        'user_id',
        'tip_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que guardó el tip
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el tip guardado
     */
    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }
}
