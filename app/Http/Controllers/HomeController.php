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
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();

        if (!is_null($check))
        {
            $user = User::find($check->user_id);

            if ($user->is_verified == 1)
            {
                return response()->json($this->response->toString(true, $this->messages['login']['verified']));
            }

            $user->update(['is_verified' => 1]);

            DB::table('user_verifications')->where('token', $verification_code)->delete();

            return response()->json($this->response->toString(true, $this->messages['login']['verified_true']));
        }
        
        return response()->json($this->response->toString(false, $this->messages['login']['error']));
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

            if ($user->is_verified == 0)
            {
                return response()->json($this->response->toString(false, $this->messages['login']['not_verified']));
            }

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