<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Persona;
use App\Models\Residente;
use App\Models\ResidenteMedico;
use Illuminate\Http\Request;

use App\Models\Medico;
use Illuminate\Database\QueryException;
use DB;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:medico-listar|medico-crear|medico-editar|medico-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:medico-crear', ['only' => ['create','store']]);
        $this->middleware('permission:medico-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:medico-eliminar', ['only' => ['destroy']]);
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

        //$residente = Residente::with('medicos')->where('id', '=', $idResidente)->get();

        return view ('medicos.index',compact('residente'));
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
        $especialidads=Especialidad::orderBy('nombre','ASC')->get();
        $especialidads = $especialidads->pluck('nombre', 'id')->prepend('','');
        return view('medicos.create',compact('residente','especialidads'));
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
            $medico=$persona->medico()->create($input);



        }catch(QueryException $ex){
            try {
                $medico = Medico::where('persona_id','=',$persona->id)->first();

            }catch(QueryException $ex){

                $errorCode = $ex->errorInfo[1];
                if($errorCode == 1062){
                    $error='El medico ya está cargado al residente';
                }
                else{
                    $error=$ex->getMessage();
                }

                $ok=0;
            }



        }




        if ($ok){
            $residente->medicos()->attach($medico);
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Médico creado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }


        return redirect()->route('medicos.index',array('residenteId' =>$residente->id))
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
        $idMedico = $request->get('idMedico');
        $residente = Residente::find($idResidente);
        $medico = Medico::find($idMedico);
        $residenteMedico = $residente->medicos()->find($idMedico);

        $especialidads=Especialidad::orderBy('nombre','ASC')->get();
        $especialidads = $especialidads->pluck('nombre', 'id')->prepend('','');

        return view('medicos.edit',compact('residente','medico','residenteMedico','especialidads'));
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
            $medico = Medico::find($request->get('idMedico'));
            $medico->update($input);

            $update['nombre'] = $request->get('nombre');
            $update['apellido'] = $request->get('apellido');
            $update['email'] = $request->get('email');
            $update['telefono'] = $request->get('telefono');
            $update['domicilio'] = $request->get('domicilio');



            $update['tipoDocumento'] = $request->get('tipoDocumento');
            $update['documento'] = $request->get('documento');




            $medico->persona()->update($update);

            $idResidente = $request->get('idResidente');
            $residente = Residente::find($idResidente);
            //$residente->medicos()->updateExistingPivot($id, ['default'=>0]);

        } catch (\Exception $e) {
            $error=$e->getMessage();
            $ok=0;
        }

        if ($ok){
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Médico modificado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        return redirect()->route('medicos.index',array('residenteId' =>$residente->id))
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
        $idMedico = $request->get('idMedico');
        $residente = Residente::find($idResidente);

        $residente->medicos()->detach($idMedico);



        return redirect()->route('medicos.index', array('residenteId' =>$idResidente))
            ->with('success','Médico eliminado con éxito');
    }


}
