<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\EventRepositoryInterface;

class EventController extends Controller
{

    protected $event;

    public function __construct(EventRepositoryInterface $event)
    {
        $this->event = $event;
    }

    public function index(){
       return response()->json( $this->event->getAll());
    }

    public function show(){

    }

    public function store(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
