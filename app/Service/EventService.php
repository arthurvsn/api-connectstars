<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\Event;
use App\ArtistOnEvent;

class EventService extends Service
{
    private $event;
    private $artistOnEvent;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->event = new Event();
        $this->artistOnEvent = new ArtistOnEvent();
    }

    /**
     * Create event \App\Event
     * @param  \Illuminate\Http\Request  $request
     * @return object $returnEvent or false
     */
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

    /**
     * Add a artist to event
     * @param int $eventId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addArtistOnEvent($eventId, Request $request)
    {
        
        $arrayArtists = $request->get('artists');

        if(is_array($arrayArtists))
        {
            foreach($arrayArtists as $key => $value)
            {
                try
                {
                    $returnAdd = $this->artistOnEvent->create([
                        'amount_artist_receive' => $request->get('amount_artist_receive'),
                        'user_id' => $value->user_id,
                        'event_id' => $eventId
                    ]);
                }
                catch(Exception $e)
                {
                    return false;
                }
            }
            
            return $returnAdd;        
        }
        else
        {
            return false;
        }     
    }
}
?>