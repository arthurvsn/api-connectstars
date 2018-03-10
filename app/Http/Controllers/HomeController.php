<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;

class HomeController extends Controller
{
    private $response;

    public function __construct() 
    {
        $this->response = new Response();
    }

    public function index()
    {
        $this->response->setTypeS();
        $this->response->setMessages("API connected!");

        return response()->json($this->response->toString());
    }
}
