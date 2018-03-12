<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeUser;
use \App\Response\Response;

class TypeUserController extends Controller
{
    private $response;

    public function __construct() 
    {
        $this->response = new Response();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typeUsers = TypeUser::get();
        $this->response->setTypeS();
        $this->response->setDataSet($typeUsers);
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
        $typeUser = new TypeUser();
        
        $typeUser->fill($request->all());
        $typeUser->save();

        $this->response->setTypeS();
        $this->response->setDataSet($typeUser);
        $this->response->setMessages("Created type user successfully!");
        
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
        $typeUser = TypeUser::find($id);
        
        if(!$typeUser) 
        {
            $this->response->setTypeN();
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $this->response->setTypeS();
        $this->response->setDataSet($typeUser);
        $this->response->setMessages("Sucess!");

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
         $typeUser = TypeUser::find($id);
        
        if(!$typeUser) 
        {
            $this->response->setTypeN();
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $this->response->setTypeS();
        $this->response->setDataSet($typeUser);
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
        $typeUser = TypeUser::find($id);

        if(!$typeUser) 
        {
            $this->response->setTypeN();
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $typeUser->delete();
    }
}
