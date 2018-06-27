<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use \App\Service\UserService;

class HomeController extends Controller
{
    private $response;
    private $userService;

    public function __construct() 
    {
        $this->response = new Response();
        $this->userService = new UserService();
    }

    public function index()
    {
        $this->response->setMessages("API connected!");
        $this->response->setType("S");

        return response()->json($this->response->toString());
    }

    /**
     * Ping to test if user is authenticate on API
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ping(Request $request) 
    {
        $user_logged = $this->eventService->getAuthUser($request);

        if(!$user_logged) 
        {
            $this->response->setType("N");
            $this->response->setMessages("Sucess!");
        } 
        else 
        {
            $this->response->setType("S");
            $this->response->setMessages("Error!");
        }

        return response()->json($this->response->toString());
    }

    /**
     * Get user Logged on API 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserLogged(Request $resquest)
    {
        try
        {
            $user = $this->userService->getAuthUser($resquest);

            $this->response->setType("S");
            $this->response->setMessages("Show user successfully!");
            $this->response->setDataSet("user", $user);

        }
        catch (\Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
        
        return response()->json($this->response->toString());
    }
}
