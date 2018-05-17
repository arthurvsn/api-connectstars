<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use \App\Event;
use \App\User;
use \App\ArtistOnEvent;
use \App\Response\Response;
use \App\Service\EventService;

class EventController extends Controller
{
    private $response;
    private $event;
    private $user;
    private $eventService;
    private $artistOnEvent;

    /**
     * Contructor of controller
     */
    public function __construct() 
    {
        $this->response = new Response();
        $this->event = new Event();
        $this->user = new User();
        $this->eventService = new EventService();
        $this->artistOnEvent = new ArtistOnEvent();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user_logged = $this->eventService->getAuthUser($request);

        if(!$user_logged || $user_logged->user_type == "ARTIST")
        {
            $this->response->setType("N");
            $this->response->setMessages("User not logged");
    
            return response()->json($this->response->toString(), 404);
        }

        $events = $this->event->getEventsWithIdUser($user_logged->id);
        
        $this->response->setType("S");
        $this->response->setMessages("Sucess");
        $this->response->setDataSet("events", $events);

        return response()->json($this->response->toString());
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
        try 
        {
            $user_logged = $this->eventService->getAuthUser($request);
    
            if($user_logged->user_type == "CONTRACTOR")
            {
                $returnEvent = $this->eventService->createEvent($request, $user_logged->id);
        
                $this->response->setType("S");
                $this->response->setMessages("Event created");
                $this->response->setDataSet("event", $returnEvent);
        
                return response()->json($this->response->toString());
            }
            else 
            {
                $this->response->setType("N");
                $this->response->setMessages("You don't have permission to create a event");
    
                return response()->json($this->response->toString(), 500);
            }
        }
        catch(Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
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
    
            return response()->json($this->response->toString(), 404);
        }

        $this->response->setType("S");
        $this->response->setMessages("Sucess!");
        $this->response->setDataSet("event", $events);
    
        return response()->json($this->response->toString());
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
        try
        {
            $events = $this->event->find($id);
            
            if(!$events)
            {
                $this->response->setType("N");
                $this->response->setMessages("Event not found");
        
                return response()->json($this->response->toString(), 404);
            }
    
            $events->fill($request->all());
            $events->save();
    
            $this->response->setType("S");
            $this->response->setDataSet("event", $events);
            $this->response->setMessages("Event updated successfully!");

            return response()->json($this->response->toString());
        }
        catch (Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $events = $this->event->find($id);
            
            if(!$events)
            {
                $this->response->setType("N");
                $this->response->setMessages("Event not found!");
    
                return response()->json($this->response->toString(), 404);
            }
    
            $events->delete();
        }
        catch (Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
    }

    /**
     * Method to call service to add artist's to Event
     * @param int $idEvent
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addArtistToEvent($idEvent, Request $request)
    {
        try
        {
            $events = $this->event->find($idEvent);
    
            if(!$events) 
            {
                $this->response->setType("N");
                $this->response->setMessages("Event not found");
    
                return response()->json($this->response->toString(), 404);
            }
            else
            {
                $addArtistToEvent = $this->eventService->addArtistOnEvent($idEvent, $request);
        
                if(!$addArtistToEvent)
                {
                    $this->response->setType("N");
                    $this->response->setMessages("Artist don't add at event!");
                    return response()->json($this->response->toString(), 500);
                }
                else
                {
                    $this->response->setType("S");
                    $this->response->setDataSet("Event", $addArtistToEvent);
                    $this->response->setMessages("Artist add a event!");
                    return response()->json($this->response->toString());
                }
            }
        }
        catch(Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
    }

    /**
     * Method to confirm artist on Event
     * @param int $idEvent
     * @param int $idArtist
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmArtistToEvent($idEvent, $idArtist, Request $request)
    {
        try
        {
            //Search a Event
            $events = $this->event->find($idEvent);
            
            //search Artist
            $artists = $this->user->find($idArtist);
    
            if(!$events || !$artists || $artist->type_user != "Artist") 
            {
                $this->response->setType("N");
                $this->response->setMessages("Incorrect data!");
    
                return response()->json($this->response->toString(), 404);
            }
            else
            {
                //search a id where artist is on event
                $artistOnEvents = $this->artistOnEvent->getArtistOnEvent($idEvent, $idArtist);
    
                if(!$artistOnEvents)
                {
                    $this->response->setType("N");
                    $this->response->setMessages("Request to change a cofirmed artist not completed");
    
                    return response()->json($this->response->toString(), 404);
                }
                else 
                {
                    $artistOnEvents->fill($request->all());
                    $artistOnEvents->save();
    
                    $this->response->setType("S");
                    $this->response->setMessages("Artist changed your status on event");
                    $this->response->setDataSet("Change Artist", $artistOnEvents);
    
                    return response()->json($this->response->toString());
                }
            }            
        }
        catch (Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
    }
}
