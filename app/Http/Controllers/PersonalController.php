<?php

namespace App\Http\Controllers;

use App\Models\Ocupacion;
use App\Models\Persona;
use Illuminate\Http\Request;

use App\Models\Personal;
use Illuminate\Database\QueryException;
use DB;


class PersonalController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:personal-listar|personal-crear|personal-editar|personal-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:personal-crear', ['only' => ['create','store']]);
        $this->middleware('permission:personal-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:personal-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $personals = Personal::all();

        return view ('personals.index',compact('personals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $ocupacions=Ocupacion::orderBy('nombre','ASC')->get();
        $ocupacions = $ocupacions->pluck('nombre', 'id')->prepend('','');

        return view('personals.create',compact('ocupacions'));
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

        DB::beginTransaction();
        $ok=1;
        try {
            $persona = Persona::create($input);
            $persona->personal()->create($input);
        }catch(QueryException $ex){

            try {
                $persona = Persona::where('documento','=',$input['documento'])->first();
                if (!empty($persona)){
                    $persona->update($input);
                    $persona->personal()->create($input);

                }
            }catch(QueryException $ex){

                $error=$ex->getMessage();
                $ok=0;

            }


        }

        if ($ok){
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Personal creado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        return redirect()->route('personals.index')
            ->with($respuestaID,$respuestaMSJ);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $personal = Personal::find($id);
        return view('personals.show',compact('personal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $personal = Personal::find($id);
        $ocupacions=Ocupacion::orderBy('nombre','ASC')->get();
        $ocupacions = $ocupacions->pluck('nombre', 'id')->prepend('','');

        return view('personals.edit',compact('personal','ocupacions'));
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

        $personal = Personal::find($id);

        DB::beginTransaction();
        $ok=1;
        try {
            $personal->update($input);

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


            $personal->persona()->update($update);
        }catch(QueryException $ex){

            $error=$ex->getMessage();
            $ok=0;

        }

        if ($ok){
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Personal modificado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        return redirect()->route('personals.index')
            ->with($respuestaID,$respuestaMSJ);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$personal=Personal::find($id);

        Personal::find($id)->delete();
        //Persona::find($personal->persona_id)->delete();
        return redirect()->route('personals.index')
            ->with('success','Personal eliminado con éxito');
    }
}
