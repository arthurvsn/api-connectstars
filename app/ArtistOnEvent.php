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

    public function getArtistOnEvent($idEvent, $idArtist, $artist_confirmed)
    {
        $artistOnEvent = DB::table('artist_on_events')
            ->where([
                    ['event_id', $idEvent],
                    ['contractor_id', $idArtist],
                ])
            ->update(['artist_confirmed' => $artist_confirmed]);

        return $artistOnEvent;
    }
}
