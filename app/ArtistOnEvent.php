<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArtistOnEvent extends Model
{
    protected $fillable = [
        'amount_artist_receive',
        'user_id',
        'event_id',
    ];
}
