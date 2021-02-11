<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invitation extends Model
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'array'
    ];

    protected $with = ['invitable'];

    protected $appends = ['is_registered'];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function invitable()
    {
        return $this->morphTo();
    }

    public function getIsRegisteredAttribute()
    {
        return User::where('email', $this->email)->exists();
    }

    public function accept()
    {
        $profile = Profile::whereEmail($this->email)->first();
        if ($profile) {
            // RESPONSABLE ORGANISATION
            if ($this->role == 'responsable_organisation') {
                $this->invitable->addMember($profile, 'responsable');
            }
            // RESPONSABLE COLLECTIVITE
            if ($this->role == 'responsable_organisation') {
                $this->invitable->addMember($profile, 'responsable');
            }
            // REFERENT DEPARTEMENTAL
            if ($this->role == 'referent_departemental') {
                $profile->update(['referent_department' => $this->properties['referent_departemental']]);
            }
            // REFERENT REGIONAL
            if ($this->role == 'referent_regional') {
                $profile->update(['referent_region' => $this->properties['referent_regional']]);
            }
            // SUPERVISEUR
            if ($this->role == 'superviseur') {
                $profile->update(['reseau_id' => $this->invitable->id]);
            }
            // DATAS ANALYST
            if ($this->role == 'datas_analyst') {
                $profile->update(['is_analyste' => true]);
            }
        }
    }
}