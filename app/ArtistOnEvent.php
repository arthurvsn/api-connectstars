<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

use DB;

class ArtistOnEvent extends Model
{
    use HasUuid;

    protected $fillable = [
        'amount_artist_receive',
        'artist_confirmed',
        'artist_id',
        'event_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * references to uuid
     */
    public function getUuidColumnName()
    {
        return 'id';
    }

    /**
     * Table user relationship with artist_on_events
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'artist_id');
    }

    /**
     * Table event relationship with artist_on_events
     */
    public function events()
    {
        return $this->belongsTo('App\Event');
    }

    /**
     * Get artist on Event
     * @param int $eventId
     * @param int $artistId
     * @param string $artist_confirmed
     * @return object $artistOnEvent
     */
    public function confirmArtistOnEvent($eventId, $artistId, $artist_confirmed)
    {
        $artistOnEvent = DB::table('artist_on_events')
            ->where([
                    ['event_id', $eventId],
                    ['artist_id', $artistId],
                ])
            ->update(['artist_confirmed' => $artist_confirmed]);

        return $artistOnEvent;
    }
}
