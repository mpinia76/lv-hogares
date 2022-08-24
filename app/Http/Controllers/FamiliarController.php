<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Residente;
use App\Models\ResidenteFamiliar;
use Illuminate\Http\Request;

use App\Models\Familiar;
use Illuminate\Database\QueryException;
use DB;

class FamiliarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:familiar-listar|familiar-crear|familiar-editar|familiar-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:familiar-crear', ['only' => ['create','store']]);
        $this->middleware('permission:familiar-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:familiar-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $idResidente = $request->get('residenteId');
        $residente = Residente::find($idResidente);

        //$residente = Residente::with('familiars')->where('id', '=', $idResidente)->get();

        return view ('familiars.index',compact('residente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $idResidente = $request->get('residenteId');
        $residente = Residente::find($idResidente);
        return view('familiars.create',compact('residente'));
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
            'documento' => 'required'
        ]);


        $input = $request->all();

        $idResidente = $request->get('idResidente');
        $residente = Residente::find($idResidente);
        DB::beginTransaction();
        $ok=1;

            try {
                $persona = Persona::create($input);

            }catch(QueryException $ex){

                try {
                    $persona = Persona::where('documento','=',$input['documento'])->first();
                    if (!empty($persona)){
                        $persona->update($input);


                    }
                }catch(QueryException $ex){

                    $error=$ex->getMessage();
                    $ok=0;
                }


            }
            try {
                $familiar=$persona->familiars()->create($input);



            }catch(QueryException $ex){
                try {
                    $familiar = Familiar::where('persona_id','=',$persona->id)->first();

                }catch(QueryException $ex){

                    $errorCode = $ex->errorInfo[1];
                    if($errorCode == 1062){
                        $error='El familiar ya está cargado al residente';
                    }
                    else{
                        $error=$ex->getMessage();
                    }

                    $ok=0;
                }



            }




        if ($ok){
            $principal = $request->get('principal')?1:0;
            $residente->familiars()->attach($familiar, ['parentesco'=> $request->get('parentesco'),'principal'=> $principal]);
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Familiar creado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }


        return redirect()->route('familiars.index',array('residenteId' =>$residente->id))
            ->with($respuestaID,$respuestaMSJ);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //print_r($request);
        $idResidente = $id;
        $idFamiliar = $request->get('idFamiliar');
        $residente = Residente::find($idResidente);
        $familiar = Familiar::find($idFamiliar);
        $residenteFamiliar = $residente->familiars()->find($idFamiliar);
        //$parentesco = $residenteFamiliar->pivot->parentesco;

        return view('familiars.edit',compact('residente','familiar','residenteFamiliar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'nullable|email',
            'documento' => 'required'
        ]);


        $input = $request->all();


        DB::beginTransaction();
        $ok=1;
        try {
            $familiar = Familiar::find($request->get('idFamiliar'));
            $familiar->update($input);

            $update['nombre'] = $request->get('nombre');
            $update['apellido'] = $request->get('apellido');
            $update['email'] = $request->get('email');
            $update['telefono'] = $request->get('telefono');
            $update['domicilio'] = $request->get('domicilio');
            $update['genero'] = $request->get('genero');


            $update['tipoDocumento'] = $request->get('tipoDocumento');
            $update['documento'] = $request->get('documento');
            $update['nacimiento'] = $request->get('nacimiento');



            $familiar->persona()->update($update);

            $idResidente = $request->get('idResidente');
            $residente = Residente::find($idResidente);
            $principal = $request->get('principal')?1:0;
            $residente->familiars()->updateExistingPivot($id, ['parentesco'=> $request->get('parentesco'),'principal'=> $principal]);

        } catch (\Exception $e) {
            $error=$e->getMessage();
            $ok=0;
        }

        if ($ok){
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Familiar modificado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        return redirect()->route('familiars.index',array('residenteId' =>$residente->id))
            ->with($respuestaID,$respuestaMSJ);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $idResidente = $id;
        $idFamiliar = $request->get('idFamiliar');
        $residente = Residente::find($idResidente);

        $residente->familiars()->detach($idFamiliar);



        return redirect()->route('familiars.index', array('residenteId' =>$idResidente))
            ->with('success','Familiar eliminado con éxito');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autosearch(Request $request)
    {
        $documento = $request->search;
        $familiars=Familiar::with('persona')->whereHas('persona', function($query) use ($documento){
            if($documento){
                $query->where('documento', 'LIKE', "%$documento%");
            }
        })->get()->sortBy(function($query){
            return $query->persona->apellido;
        });



        $response=array();
        foreach($familiars as $familiar){
            $response[] = array("value"=>$familiar->id,"label"=>$familiar->persona->getFullNameAttribute().'('.$familiar->persona->documento.')',"documento"=>$familiar->persona->documento,"tipoDocumento"=>$familiar->persona->tipoDocumento,"nombre"=>$familiar->persona->nombre,"apellido"=>$familiar->persona->apellido,"genero"=>$familiar->persona->genero,"email"=>$familiar->persona->email,"telefono"=>$familiar->persona->telefono,"domicilio"=>$familiar->persona->domicilio,"nacimiento"=>($familiar->persona->nacimiento)?date('Y-m-d', strtotime($familiar->persona->nacimiento)):'',"ingreso"=>($familiar->persona->ingreso)?date('Y-m-d', strtotime($familiar->persona->ingreso)):'',"baja"=>($familiar->persona->baja)?date('Y-m-d', strtotime($familiar->persona->baja)):'',"foto"=>$familiar->persona->foto);
        }

        return response()->json($response);

    }


}
