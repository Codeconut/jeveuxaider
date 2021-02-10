<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'array'
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
