<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Residente;
use App\Models\ResidenteFamiliar;
use Illuminate\Http\Request;

use App\Models\Familiar;
use Illuminate\Database\QueryException;
use DB;

class ResidenteMedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:residenteMedicamento-listar|residenteMedicamento-crear|residenteMedicamento-editar|residenteMedicamento-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:residenteMedicamento-crear', ['only' => ['create','store']]);
        $this->middleware('permission:residenteMedicamento-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:residenteMedicamento-eliminar', ['only' => ['destroy']]);
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

        return view ('residenteMedicamentos.index',compact('residente'));
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
        return view('residenteMedicamentos.create',compact('residente'));
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
                $familiar=$persona->familiar()->create($input);



            }catch(QueryException $ex){
                try {
                    $familiar = Familiar::where('persona_id','=',$persona->id)->first();

                }catch(QueryException $ex){

                    $errorCode = $ex->errorInfo[1];
                    if($errorCode == 1062){
                        $error='El medicamento ya está cargado al residente';
                    }
                    else{
                        $error=$ex->getMessage();
                    }

                    $ok=0;
                }



            }




        if ($ok){
            $principal = $request->get('principal')?1:0;
            $residente->medicamentos()->attach($medicamento, ['parentesco'=> $request->get('parentesco'),'principal'=> $principal]);
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Medicamento creado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }


        return redirect()->route('residenteMedicamentos.index',array('residenteId' =>$residente->id))
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
        $idMedicamento = $request->get('idMedicamento');
        $residente = Residente::find($idResidente);
        $medicamento = Medicamento::find($idMedicamento);
        $residenteMedicamento = $residente->medicamentos()->find($idMedicamento);
        //$parentesco = $residenteFamiliar->pivot->parentesco;

        return view('residenteMedicamentos.edit',compact('residente','medicamento','residenteMedicamento'));
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


            $idResidente = $request->get('idResidente');
            $residente = Residente::find($idResidente);
            $principal = $request->get('principal')?1:0;
            $residente->medicamentos()->updateExistingPivot($id, ['parentesco'=> $request->get('parentesco'),'principal'=> $principal]);

        } catch (\Exception $e) {
            $error=$e->getMessage();
            $ok=0;
        }

        if ($ok){
            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Medicamento modificado con éxito';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        return redirect()->route('residenteMedicamentos.index',array('residenteId' =>$residente->id))
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
        $idMedicamento = $request->get('idMedicamento');
        $residente = Residente::find($idResidente);

        $residente->medicamentos()->detach($idMedicamento);



        return redirect()->route('residenteMedicamentos.index', array('residenteId' =>$idResidente))
            ->with('success','Medicamento eliminado con éxito');
    }




}
