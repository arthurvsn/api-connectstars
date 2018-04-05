<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\Event;

class EventService extends Service
{
    private $event;

    public function __construct() 
    {
        $this->event = new Event();
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
}
?>