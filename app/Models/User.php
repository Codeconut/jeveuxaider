<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'context_role',
    ];

    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['profile'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public static function currentUser()
    {
        if (!Auth::guard('api')->user()) {
            return null;
        }
        $id = Auth::guard('api')->user()->id;
        $user = User::with(['profile.structures','profile.structures.collectivity', 'profile.participations'])->where('id', $id)->first();
        $user['profile']['roles'] = $user->profile->roles; // Hack pour éviter de le mettre append -> trop gourmand en queries
        $user['profile']['skills'] = $user->profile->skills; // Hack pour éviter de le mettre append -> trop gourmand en queries
        $user['profile']['domaines'] = $user->profile->domaines; // Hack pour éviter de le mettre append -> trop gourmand en queries
        $user['social_accounts'] = $user->socialAccounts; // Hack pour éviter de le mettre append -> trop gourmand en queries
        $user['nbUnreadConversations'] = self::getNbUnreadConversations($id);

        return $user;
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function socialAccounts()
    {
        return $this->hasMany('App\Models\SocialAccount');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function structures()
    {
        return $this->hasMany('App\Models\Structure');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'from_id');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Models\Conversation', 'conversations_users');
    }

    public function startConversation($user, $conversable)
    {
        $conversation = new Conversation;
        $conversation->conversable()->associate($conversable);
        $conversation->save();

        $conversation->users()->attach([$this->id, $user->id]);

        return $conversation;
    }

    public function markConversationAsRead($conversation)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'read_at' => Carbon::now()
        ]);
    }

    // TODO markConversationAsActive, markConversationAsArchived

    public function sendMessage($conversation_id, $content)
    {
        return $this->messages()->create([
            'content' => $content,
            'conversation_id' => $conversation_id,
            'type' => 'chat'
        ]);
    }

    public function getContextRoleAttribute()
    {
        if ($this->attributes['context_role'] == null || $this->attributes['context_role'] == 'volontaire') {
            $userRoles = array_filter($this->profile->roles, function ($role) {
                return $role === true;
            });
            if (count($userRoles) > 0) {
                $this->attributes['context_role'] = array_key_first($userRoles);
            }
        }

        return $this->attributes['context_role'];
    }

    public function anonymize()
    {
        $email = $this->id . '@anonymized.fr';
        $this->anonymous_at = Carbon::now();
        $this->name = $email;
        $this->email = $email;
        $this->profile->email = $email;
        $this->profile->first_name = 'Anonyme';
        $this->profile->last_name = 'Anonyme';
        $this->profile->phone = null;
        $this->profile->mobile = null;
        $this->profile->birthday = null;
        $this->save();
        $this->profile->save();

        return $this;
    }

    public static function getNbUnreadConversations($id)
    {
        return User::find($id)->conversations()
            ->whereHas('messages')
            ->where(function ($query) {
                $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                    ->orWhere('conversations_users.read_at', null);
            })->count();
    }
}
