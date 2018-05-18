<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'local',
        'ticket_price',
        'duration',
        'event_date',
        'user_id',
    ];

    public function getEventsWithIdUser($id)
    {
        $events = DB::table('events')
            ->where('user_id', '=', $id)
            ->get();
        
        return $events;
    }
}
