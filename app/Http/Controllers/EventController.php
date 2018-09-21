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
    private $artistOnEvent;
    private $event;
    private $eventService;
    private $messages;
    private $response;
    private $user;

    /**
     * Contructor of controller
     */
    public function __construct() 
    {
        $this->artistOnEvent    = new ArtistOnEvent();
        $this->event            = new Event();
        $this->eventService     = new EventService();
        $this->messages         = \Config::get('messages');
        $this->response         = new Response();
        $this->user             = new User();
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
            return response()->json($this->response->toString(false, $this->messages['event']['validate_error']));
        }

        $user = $this->user->find($user_logged->id);
        $events = $user->events()->get();

        $this->response->setDataSet("events", $events);
        return response()->json($this->response->toString(true, $this->messages['event']['show']));
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

                $this->response->setDataSet("event", $returnEvent);        
                return response()->json($this->response->toString(true, $this->messages['event']['save']));
            }
            else 
            {
                return response()->json($this->response->toString(false, $this->messages['event']['permision']));
            }
        }
        catch(\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
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
            return response()->json($this->response->toString(false, $this->messages['error']));
        }

        $this->response->setDataSet("event", $events);
        return response()->json($this->response->toString(true, $this->messages['event']['save']));
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
                return response()->json($this->response->toString(false, $this->messages['error']));
            }
    
            $events->fill($request->all());
            $events->save();
    
            $this->response->setDataSet("event", $events);
            return response()->json($this->response->toString(true, $this->messages['event']['save']));
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
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
            $user_logged = $this->userService->getAuthUserNoRequest();

            if(!$events || $events->contractor_id != $user_logged->id)
            {
                return response()->json($this->response->toString(false, $this->messages['error']));
            }
    
            $events->delete();

            return response()->json($this->response->toString(true, $this->messages['event']['delete']));
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
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
        
        $events = $this->event->find($idEvent);

        if(!$events) 
        {
            return response()->json($this->response->toString(false, $this->messages['error']));
        }
        else
        {
            try
            {
                \DB::beginTransaction();
                $addArtistToEvent = $this->eventService->addArtistOnEvent($idEvent, $request);

                if($addArtistToEvent)
                {
                    \DB::commit();
                    $this->response->setDataSet("Event", $addArtistToEvent);
                    return response()->json($this->response->toString(true, $this->messages['event']['save']));
                }
                else
                {
                    \DB::rollBack();
                    return response()->json($this->response->toString(false, $this->messages['event']['error_add']));
                    
                }
            }
            catch(\Exception $e)
            {
                \DB::rollBack();
                return response()->json($this->response->toString(false, $e->getMessage()));
            }
        }
    }

    /**
     * Method to confirm artist on Event
     * @param int $idEvent
     * @param int $idArtist
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmArtistToEvent($idEvent, Request $request)
    {
        try
        {
            //Search a Event
            $events = $this->event->find($idEvent);
            
            //search Artist
            $artists = $this->eventService->getAuthUser($request);
            
            if(!$events || !$artists || $artists->user_type != "ARTIST") 
            {
                return response()->json($this->response->toString(false, $this->messages['event']['error_data']));
            }
            else
            {
                //search a id where artist is on event
                $artistOnEvents = $this->artistOnEvent->confirmArtistOnEvent($idEvent, $artists->id, $request->get('artist_confirmed'));
                
                if(!$artistOnEvents)
                {
                    return response()->json($this->response->toString(false, $this->messages['error_request']));
                }
                else 
                {
                    $this->response->setDataSet("ChangeArtist", $artistOnEvents);                    
                    return response()->json($this->response->toString(true, $this->messages['event']['save']));
                }
            }
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }

    /**
     * Method to search a events to contractor
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function eventContractor(Request $request)
    {
        $contractor = $this->eventService->getAuthUser($request);

        if (!$contractor || $artists->user_type != "CONTRACTOR")
        {
            return response()->json($this->response->toString(false, $this->messages['event']['error_data']));
        }

        $users = $this->user->find($contractor->id);

        $events = $users->events()->get();

        $this->response->setDataSet('events', $events);
        
        return response()->json($this->response->toString(true, $this->messages['event']['show']));
    }
}
