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
        'contractor_id',
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
}
