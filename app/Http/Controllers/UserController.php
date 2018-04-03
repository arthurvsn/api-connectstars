<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use JWTAuthException;
use \App\Response\Response;
use \App\Service\UserService;

class UserController extends Controller
{
    private $response;
    private $user;
    private $userService;

    public function __construct(User $user) 
    {
        $this->user = $user;
        $this->response = new Response();
        $this->userService = new UserService();
    }

    /**
     * Metodo de login 
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try 
        {
           if (!$token = JWTAuth::attempt($credentials)) 
           {
                return response()->json(['invalid_email_or_password'], 422);
           }
        } 
        catch (JWTAuthException $e) 
        {
            return response()->json(['failed_to_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * Metodo que recupera o usuario logado
     */
    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        $this->response->setTypeS();
        $this->response->setDataSet($users);
        $this->response->setMessages("Sucess!");

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
        $user = $this->userService->create($request);
        
        $this->response->setTypeS();
        $this->response->setDataSet($returnUser);
        $this->response->setMessages("Created user successfully!");
        
        return response()->json($this->response->toString());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);

        if(!$user)
        {
            $this->response->setTypeN();
            $this->response->setMessages("User not found!");
            return response()->json($this->response->toString(), 404);
        }
        else 
        {
            $this->response->setTypeS();
            $this->response->setDataSet($user);
            $this->response->setMessages("Show user successfully!");
            
            return response()->json($this->response->toString());
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if(!$user) 
        {
            $this->response->setTypeN();
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $this->response->setTypeS();
        $this->response->setDataSet($user);
        $this->response->setMessages("Sucess!");

        return response()->json($this->response->toString());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user) 
        {
            $this->response->setTypeN();
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $user->delete();
    }
}
