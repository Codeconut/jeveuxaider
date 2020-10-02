<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'read_at',
        'participation_id',
    ];

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'conversations_users');
    }

    public function participation()
    {
        return $this->hasOne('App\Models\Participation');
    }
}
