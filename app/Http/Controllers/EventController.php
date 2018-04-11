<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use \App\Event;
use \App\ArtistOnEvent;
use \App\Response\Response;
use \App\Service\EventService;

class EventController extends Controller
{
    private $response;
    private $event;
    private $eventService;
    private $artistOnEvent;

    /**
     * Contructor of controller
     */
    public function __construct() 
    {
        $this->response = new Response();
        $this->event = new Event();
        $this->eventService = new EventService();
        $this->artistOnEvent = new ArtistOnEvent();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->event->get();
        
        $this->response->setType("S");
        $this->response->setMessages("Sucess");
        $this->response->setDataSet("events", $events);

        return resonse()->json($this->response->toString());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_logged = $this->eventService->getAuthUser($request);

        if($user_logged->type_user == "CONTRACTOR")
        {
            $returnEvent = $this->eventService->createEvent($request);
    
            if(!$event)
            {
                $this->response->setType("N");
                $this->response->setMessages("Event not created");
    
                return resonse()->json($this->response->toString(), 500);
            }
    
            $this->response->setType("S");
            $this->response->setMessages("Event created");
            $this->response->setDataSet("event", $returnEvent);
    
            return resonse()->json($this->response->toString());
        }
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("You don't have permission to create a event");

            return resonse()->json($this->response->toString(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $events = $this->event->find($id);

        if(!$events)
        {
            $this->response->setType("N");
            $this->response->setMessages("Event not found");
    
            return resonse()->json($this->response->toString(), 404);
        }

        $this->response->setType("S");
        $this->response->setMessages("Sucess!");
        $this->response->setDataSet("event", $events);
    
        return resonse()->json($this->response->toString());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $events = $this->event->find($id);
        
        if(!$events)
        {
            $this->response->setType("N");
            $this->response->setMessages("Event not found");
    
            return resonse()->json($this->response->toString(), 404);
        }

        $events->fill($request->all());
        $events->save();

        $this->response->setTypeS("S");
        $this->response->setDataSet("event", $events);
        $this->response->setMessages("Event updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $events = $this->event->find($id);
        
        if(!$events)
        {
            $this->response->setType("N");
            $this->response->setMessages("Event not found");

            return resonse()->json($this->response->toString(), 404);
        }

        $events->delete();
    }

    /**
     * Method to call service to add artist's to Event
     * @param int $idEvent
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addArtistToEvent($idEvent, Request $request)
    {
        $events = $this->event->find($idEvent);

        if(!$events) 
        {
            $this->response->setType("N");
            $this->response->setMessages("Event not found");

            return resonse()->json($this->response->toString(), 404);
        }
        else
        {
            $addArtistToEvent = $this->eventService->addArtistOnEvent($idEvento, $idArtist, $request);
    
            if(!$addArtistToEvent)
            {
            }
        }
    }
}
