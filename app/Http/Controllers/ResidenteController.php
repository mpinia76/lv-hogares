<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

use App\Models\Residente;
use Illuminate\Database\QueryException;


class ResidenteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:residente-listar|residente-crear|residente-editar|residente-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:residente-crear', ['only' => ['create','store']]);
        $this->middleware('permission:residente-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:residente-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $residentes = Residente::all();
        return view ('residentes.index',compact('residentes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('residentes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'nullable|email',
            'documento' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        $input = $request->all();

        $input['foto'] ='';
        if ($files = $request->file('foto')) {
            $image = $request->file('foto');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $input['foto'] = "$name";
        }


        try {
            $persona = Persona::create($input);
            $persona->residente()->create($input);
        }catch(QueryException $ex){

            try {
                $persona = Persona::where('documento','=',$input['documento'])->first();
                if (!empty($persona)){
                    $persona->update($input);
                    $persona->residente()->create($input);

                }
            }catch(QueryException $ex){

                $respuestaID='error';
                $respuestaMSJ=$ex->getMessage();

            }


        }

        return redirect()->route('residentes.index')
            ->with('success','Residente creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $residente = Residente::find($id);
        return view('residentes.show',compact('residente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $residente = Residente::find($id);


        return view('residentes.edit',compact('residente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'apellido' => 'required',
            'documento' => 'required',
            'email' => 'nullable|email',

            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $input = $request->all();

        $input['foto'] ='';
        if ($files = $request->file('foto')) {
            $image = $request->file('foto');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $input['foto'] = "$name";
        }

        $residente = Residente::find($id);
        $residente->update($input);

        $update['nombre'] = $request->get('nombre');
        $update['apellido'] = $request->get('apellido');
        $update['email'] = $request->get('email');
        $update['telefono'] = $request->get('telefono');
        $update['domicilio'] = $request->get('domicilio');
        $update['genero'] = $request->get('genero');
        $update['foto'] = $input['foto'];
        $update['observaciones'] = $request->get('observaciones');
        $update['tipoDocumento'] = $request->get('tipoDocumento');
        $update['documento'] = $request->get('documento');
        $update['nacimiento'] = $request->get('nacimiento');
        $update['fallecimiento'] = $request->get('fallecimiento');


        $residente->persona()->update($update);

        return redirect()->route('residentes.index')
            ->with('success','Residente modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$residente=Residente::find($id);

        Residente::find($id)->delete();
        //Persona::find($residente->persona_id)->delete();
        return redirect()->route('residentes.index')
            ->with('success','Residente eliminado con éxito');
    }
}
