<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'local',
        'ticket_price',
        'duration',
        'event_date',
    ];
}
