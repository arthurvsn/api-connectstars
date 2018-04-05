<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\Event;
use App\ArtistOnEvent;

class EventService extends Service
{
    private $event;
    private $artistOnEvent;

    public function __construct() 
    {
        $this->event = new Event();
        $this->artistOnEvent = new ArtistOnEvent();
    }

    public function createEvent(Request $request)
    {
        try
        {
            $returnEvent = $this->event->create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'local' => $request->get('local'),
                'ticket_price' => $request->get('ticket_price'),
                'duration' => $request->get('duration'),
                'event_date' => $request->get('event_date'),
            ]);
            
            return $returnEvent;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function addArtistOnEvent($eventId, $artistId, Request $request)
    {
        try
        {
            $returnAdd = $this->artistOnEvent->create([
                'amount_artist_receive' => $request->get('amount_artist_receive'),
                'user_id' => $artistId,
                'event_id' => $eventId
            ]);

            return $returnAdd;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}
?>