<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ArtistOnEvent extends Model
{
    protected $fillable = [
        'amount_artist_receive',
        'artist_confirmed',
        'user_id',
        'event_id',
    ];

    public function getArtistOnEvent($idEvent, $idArtist)
    {
        $artistOnEvent = DB::table('artist_on_events')
            ->whereColumn([
                    ['event_id', '=', $idEvent],
                    ['artist_id', '=', $idArtist]
                ])
            ->first();

        return $artistOnEvent;
    }
}
