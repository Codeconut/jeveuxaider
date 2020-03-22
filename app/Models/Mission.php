<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Mission extends Model
{
    use CrudTrait, SoftDeletes, Searchable;

    protected $table = 'missions';

    protected $fillable = [
        'name',
        'description',
        'address',
        'zip',
        'city',
        'department',
        'country',
        'latitude',
        'longitude',
        'user_id',
        'structure_id',
        'start_date',
        'end_date',
        'format',
        'domaines',
        'state',
        'participations_max',
        'dates_infos',
        'periodes',
        'frequence',
        'planning',
        'actions',
        'justifications',
        'contraintes',
        'handicap',
        'tuteur_id',
        'periodicite',
        'publics_beneficiaires',
        'publics_volontaires',
        'type'
    ];

    protected $casts = [
        'domaines' => 'array',
        'periodes' => 'array',
        'planning' => 'array',
        'publics_beneficiaires' => 'array',
        'publics_volontaires' => 'array'
    ];

    protected $attributes = [
        'state' => 'Validée',
        'country' => 'France'
    ];

    protected $appends = ['full_address', 'places_left', 'has_places_left'];

    protected $with = [
        'structure:id,name,city,address,zip',
        'structure.members:id,first_name,last_name,mobile,email',
        'tuteur:id,email,mobile,phone,first_name,last_name'
    ];

    protected $withCount = ['participations'];

    public function shouldBeSearchable()
    {
        return $this->isAvailable();
    }

    public function searchableAs()
    {
        return config('scout.prefix').'_covid_missions';
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'department' => $this->department,
            'periodicite' => $this->periodicite,
            'has_places_left' => $this->has_places_left,
            'places_left' => $this->places_left,
            'participations_max' => $this->participations_max,
            "_geoloc" => [
                "lat" => $this->latitude,
                "lng" => $this->longitude
            ],
            'structure' => $this->structure ? [
                'id' => $this->structure->id,
                'name' => $this->structure->name,
            ] : null
        ];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Utils::ucfirst($value);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function tuteur()
    {
        return $this->belongsTo('App\Models\Profile')->without('structures');
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation', 'mission_id')->without('mission');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address} {$this->zip} {$this->city}";
    }

    public function getHasPlacesLeftAttribute()
    {
        return $this->participations_count < $this->participations_max ? true : false;
    }

    public function getPlacesLeftAttribute()
    {
        return $this->participations_max - $this->participations_count;
    }

    public function scopeHasPlacesLeft($query)
    {
        return $query->has('participations', '<', DB::raw('participations_max'));
    }

    public function scopeComplete($query)
    {
        return $query->has('participations', '=', DB::raw('participations_max'));
    }

    public function scopeAvailable($query)
    {
        return $query
            ->where('state', 'Validée');
    }

    public function isAvailable()
    {
        return $this->state == 'Validée' && $this->has_places_left > 0 ? true : false;
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
            break;
            case 'responsable':
                // Missions des structures dont je suis responsable
                return $query
                    ->whereIn('structure_id', Auth::guard('api')->user()->profile->structures->pluck('id'));
            break;
            case 'tuteur':
                // Missions dont je suis le tuteur
                return $query
                    ->where('tuteur_id', Auth::guard('api')->user()->profile->id);
            break;
            case 'referent':
                // Missions qui sont dans mon département
                return $query
                    ->whereNotNull('department')
                    ->where('department', Auth::guard('api')->user()->profile->referent_department);
            break;
            case 'superviseur':
                // Missions qui sont dans une structure rattachée à mon réseau
                return $query
                    ->whereHas('structure', function (Builder $query) {
                        $query->where('reseau_id', Auth::guard('api')->user()->profile->reseau->id);
                    });
            break;
        }
    }

    public function scopeDistance($query, $latitude, $longitude)
    {
        $latName = 'latitude';
        $lonName = 'longitude';
        $query->select($this->getTable() . '.*');
        $sql = "((ACOS(SIN(? * PI() / 180) * SIN(" . $latName . " * PI() / 180) + COS(? * PI() / 180) * COS(" .
        $latName . " * PI() / 180) * COS((? - " . $lonName . ") * PI() / 180)) * 180 / PI()) * 60 * ?) as distance";

        $query->selectRaw($sql, [$latitude, $latitude, $longitude, 1.1515 * 1.609344]);

        return $query;
    }

    public function clone()
    {
        $mission = $this->replicate();
        // $mission->state = 'Brouillon';
        $mission->save();

        return $mission;
    }
}
