<?php

namespace App\Http\Controllers;


use App\Models\Persona;
use Illuminate\Http\Request;


use Illuminate\Database\QueryException;


class PersonaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autosearch(Request $request)
    {


        $personas = Persona::where('documento','LIKE',$request->search.'%')->get();

        foreach($personas as $persona){
            $response[] = array("value"=>$persona->id,"label"=>$persona->getFullNameAttribute().'('.$persona->documento.')',"documento"=>$persona->documento,"tipoDocumento"=>$persona->tipoDocumento,"nombre"=>$persona->nombre,"apellido"=>$persona->apellido,"genero"=>$persona->genero,"email"=>$persona->email,"telefono"=>$persona->telefono,"domicilio"=>$persona->domicilio,"nacimiento"=>($persona->nacimiento)?date('Y-m-d', strtotime($persona->nacimiento)):'',"ingreso"=>($persona->ingreso)?date('Y-m-d', strtotime($persona->ingreso)):'',"baja"=>($persona->baja)?date('Y-m-d', strtotime($persona->baja)):'',"foto"=>$persona->foto);
        }

        return response()->json($response);

    }

}
