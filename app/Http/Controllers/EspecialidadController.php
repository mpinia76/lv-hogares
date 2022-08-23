<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Especialidad;
use Illuminate\Database\QueryException;


class EspecialidadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:especialidad-listar|especialidad-crear|especialidad-editar|especialidad-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:especialidad-crear', ['only' => ['create','store']]);
        $this->middleware('permission:especialidad-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:especialidad-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $especialidads = Especialidad::all();
        return view ('especialidads.index',compact('especialidads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('especialidads.create');
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
            'nombre' => 'required'

        ]);


        $input = $request->all();


        $especialidad = Especialidad::create($input);


        return redirect()->route('especialidads.index')
            ->with('success','Especialidad creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $especialidad = Especialidad::find($id);
        return view('especialidads.show',compact('especialidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $especialidad = Especialidad::find($id);


        return view('especialidads.edit',compact('especialidad'));
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
            'nombre' => 'required'

        ]);

        $input = $request->all();




        $especialidad = Especialidad::find($id);
        $especialidad->update($input);



        return redirect()->route('especialidads.index')
            ->with('success','Especialidad modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Especialidad::find($id)->delete();

        return redirect()->route('especialidads.index')
            ->with('success','Especialidad eliminada con éxito');
    }
}
