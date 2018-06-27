<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ArtistOnEvent extends Model
{
    protected $fillable = [
        'amount_artist_receive',
        'artist_confirmed',
        'artist_id',
        'event_id',
    ];

    /**
     * Table user relationship with artist_on_events
     */
    public function users()
    {
        return $this->belongsTo('App\User');
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
                    ['contractor_id', $artistId],
                ])
            ->update(['artist_confirmed' => $artist_confirmed]);

        return $artistOnEvent;
    }
}
