<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Residente;
use App\Models\Medicamento;

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
        $medicamentos=Medicamento::orderBy('nombre','ASC')->get();
        $medicamentos = $medicamentos->pluck('full_name', 'id')->prepend('','');
        return view('residenteMedicamentos.create',compact('residente','medicamentos'));
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
            'medicamento' => 'required',
            'alta' => 'required',
            'stock' => 'required',
            'dosis' => 'required',
            'toma' => 'required'
        ]);


        $input = $request->all();

        $idResidente = $request->get('idResidente');
        $residente = Residente::find($idResidente);
        $medicamento = Medicamento::find($request->get('medicamento'));
        DB::beginTransaction();
        $ok=1;


            try {
                $residente->medicamentos()->attach($medicamento, ['alta'=> $request->get('alta'),'stock'=> $request->get('stock'),'dosis'=> $request->get('dosis'),'toma'=> $request->get('toma'),'suspension'=> $request->get('suspension')]);



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




        if ($ok){


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
        $medicamentos=Medicamento::orderBy('nombre','ASC')->get();
        $medicamentos = $medicamentos->pluck('full_name', 'id')->prepend('','');

        return view('residenteMedicamentos.edit',compact('residente','medicamento','residenteMedicamento','medicamentos'));
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
            'medicamento' => 'required',
            'alta' => 'required',
            'stock' => 'required',
            'dosis' => 'required',
            'toma' => 'required'
        ]);


        $input = $request->all();


        DB::beginTransaction();
        $ok=1;
        try {


            $idResidente = $request->get('idResidente');
            $residente = Residente::find($idResidente);

            $residente->medicamentos()->updateExistingPivot($id, ['alta'=> $request->get('alta'),'stock'=> $request->get('stock'),'dosis'=> $request->get('dosis'),'toma'=> $request->get('toma'),'suspension'=> $request->get('suspension')]);

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
