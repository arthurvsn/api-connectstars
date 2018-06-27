<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = [
        'name',
        'description',
        'local',
        'ticket_price',
        'duration',
        'event_date',
        'contractor_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'contractor_id',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get event artistOnEvent id user
     * @param $userId
     * @return object
     */
    public function getEventsWithIdUser($userId)
    {
        $events = DB::table('events')
            ->where('contractor_id', '=', $userId)
            ->get();
        
        return $events;
    }

    /**
     * Table event relationship with user
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Table artis_on_events relationship with event
     */
    public function artistOnEvents()
    {
        return $this->hasMany('App\ArtisOnEvent');
    }
}
