<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;

class EventoController extends Controller
{
    public function index(Request $request){
        $events = Evento::all();
        return $events;
    }

    public function store(Request $request)
    {
        Evento::create($request->all());
    }

    public function update(Request $request, $id){
        $event = Evento::find($id);
        $event->update($request->all());
    }

    public function destroy($id){
        Evento::find($id)->delete();
    }
}
