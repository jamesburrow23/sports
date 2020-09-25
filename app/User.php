<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $casts = [
        'can_play_goalie' => 'boolean',
    ];

    public function scopeUserType($query, $value)
    {
        return $query->where('user_type', $value);
    }

    public function scopeCanPlayGoalie($query)
    {
        return $query->where('can_play_goalie', 1);
    }

    public function scopeCannotPlayGoalie($query)
    {
        return $query->where('can_play_goalie', 0);
    }
}
