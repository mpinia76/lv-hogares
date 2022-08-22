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


        DB::beginTransaction();
        try {
            try {
                $persona = Persona::create($input);

            }catch(QueryException $ex){

                try {
                    $persona = Persona::where('documento','=',$input['documento'])->first();
                    if (!empty($persona)){
                        $persona->update($input);


                    }
                }catch(QueryException $ex){

                    $respuestaID='error';
                    $respuestaMSJ=$ex->getMessage();

                }


            }
            $familiar=$persona->familiar()->create($input);
            $idResidente = $request->get('idResidente');
            $residente = Residente::find($idResidente);
            $residente->familiars()->attach($familiar, ['parentesco'=> $request->get('parentesco')]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }



        return redirect()->route('familiars.index',array('residenteId' =>$residente->id))
            ->with('success','Familiar creado con éxito');
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
            $residente->familiars()->updateExistingPivot($id, ['parentesco'=> $request->get('parentesco')]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }



        return redirect()->route('familiars.index',array('residenteId' =>$residente->id))
            ->with('success','Familiar modificado con éxito');
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


}
