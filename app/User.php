<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'username',
        'email',
        'profile_picture',
        'password', 
        'user_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'updated_at',
        'deleted_at'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Table addresses relationship with user
     */
    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    /**
     * Table phones relationship with user
     */
    public function phones()
    {
        return $this->hasMany('App\Phone');
    }

    /**
     * Table artis_on_events relationship with user
     */
    public function artistOnEvents()
    {
        return $this->hasMany('App\ArtisOnEvent');
    }
    
}
