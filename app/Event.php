<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

use DB;

class Event extends Model
{
    use HasUuid;

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
     * references to uuid
     */
    public function getUuidColumnName()
    {
        return 'id';
    }
    
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
        return $this->hasMany('App\ArtistOnEvent');
    }

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
     * 
     */
    public function getEventArtist($userID)
    {
        $events = DB::table('users')
            ->select('events.*')
            ->join('artist_on_events', 'users.id', '=', 'artist_on_events.artist_id')
            ->join('events', 'artist_on_events.event_id', '=', 'events.id')
            ->where('users.id', $userID)
            ->get();

        return $events;
    }
}
