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
        $this->user         = new User;
        $this->response     = new Response();
        $this->userService  = new UserService();
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
               $this->response->setType("N");
               $this->response->setMessages("invalid_username_or_password");
               return response()->json($this->response->toString(), 422);
           }
        } 
        catch (JWTAuthException $e) 
        {
            $this->response->setType("N");
            $this->response->setMessages("failed_to_create_token");
            return response()->json($this->response->toString(), 500);
        }
        
        $user = JWTAuth::toUser($token);
        
        $this->response->setType("S");
        $this->response->setMessages("Login successfully!");
        $this->response->setDataSet("token", $token);
        
        $this->response->setDataSet("user", $user);
        return response()->json($this->response->toString(), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        $this->response->setType("S");
        $this->response->setDataSet("user", $users);
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
        try
        {
            $returnUser = $this->userService->createUser($request);
            $this->response->setType("S");
            $this->response->setDataSet("user", $returnUser);            
            $this->response->setMessages("Created user successfully!");
            
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
            $this->response->setType("N");
            $this->response->setMessages("User not found!");
            return response()->json($this->response->toString(), 404);
        }
        else 
        {
            $this->response->setType("S");
            $this->response->setDataSet("user", $user);
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
        try
        {
            $user = User::find($id);
            
            if(!$user) 
            {
                $this->response->setType("N");
                $this->response->setMessages("Record not found!");
    
                return response()->json($this->response->toString(), 404);
            }
    
            $user->fill($request->all());
            $user->save();
            $this->response->setTypeS("S");
            $this->response->setDataSet("user", $user);
            $this->response->setMessages("User on event updated successfully !");
    
            return response()->json($this->response->toString());
        }
        catch (\Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $user = User::find($id);

            if(!$user) 
            {
                $this->response->setType("N");
                $this->response->setMessages("Record not found!");

                return response()->json($this->response->toString(), 404);
            }

            $user->delete();

        }
        catch (\Exception $e)
        {
            $this->response->setType("N");
            $this->response->setMessages($e->getMessage());

            return response()->json($this->response->toString(), 500);
        }
        
    }
}
