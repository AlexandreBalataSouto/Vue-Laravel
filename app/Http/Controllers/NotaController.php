<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nota;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        return Nota::orderBy('updated_at','DESC')->paginate(3);
    }

    public function store(Request $request)
    {
       
        $nota = Nota::create($request->all());
        return $nota; 
    }

    public function update(Request $request,$id)
    {   
 
        $nota = Nota::find($id);
        $nota->update($request->all());
        return $nota;
    }

    public function destroy($id)
    {
       $nota = Nota::find($id);
       $nota->delete();
    }
}
