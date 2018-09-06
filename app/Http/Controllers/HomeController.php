<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use \App\Service\UserService;

class HomeController extends Controller
{
    private $messages;
    private $response;
    private $userService;

    public function __construct()
    {
        $this->messages     = \Config::get('messages');
        $this->response     = new Response();
        $this->userService  = new UserService();
    }

    public function index()
    {
        return response()->json($this->response->toString(true, $this->messages['api']['connect']));
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
            return response()->json($this->response->toString(true, $this->messages['api']['connect']));
        }
        else
        {
            return response()->json($this->response->toString(true, $this->messages['api']['sucess']));
        }
    }

    /**
     * Login user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = null;
        try 
        {
           if (!$token = JWTAuth::attempt($credentials)) 
           {
               return response()->json($this->response->toString(false, $this->messages['login']['credentials']));
           }

           $user = JWTAuth::toUser($token);
        
            $this->response->setDataSet("token", $token);           
            $this->response->setDataSet("user", $user);

            return response()->json($this->response->toString(true, $this->messages['login']['sucess']));

        } 
        catch (JWTAuthException $e) 
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
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

            $this->response->setDataSet("user", $user);
            return response()->json($this->response->toString(true, $this->messages['user']['show']));
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }
}