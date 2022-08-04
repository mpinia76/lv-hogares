<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Ocupacion;
use Illuminate\Database\QueryException;


class OcupacionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:ocupacion-listar|ocupacion-crear|ocupacion-editar|ocupacion-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:ocupacion-crear', ['only' => ['create','store']]);
        $this->middleware('permission:ocupacion-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:ocupacion-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $ocupacions = Ocupacion::all();
        return view ('ocupacions.index',compact('ocupacions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('ocupacions.create');
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


        $ocupacion = Ocupacion::create($input);


        return redirect()->route('ocupacions.index')
            ->with('success','Ocupación creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ocupacion = Ocupacion::find($id);
        return view('ocupacions.show',compact('ocupacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ocupacion = Ocupacion::find($id);


        return view('ocupacions.edit',compact('ocupacion'));
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




        $ocupacion = Ocupacion::find($id);
        $ocupacion->update($input);



        return redirect()->route('ocupacions.index')
            ->with('success','Ocupación modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Ocupacion::find($id)->delete();

        return redirect()->route('ocupacions.index')
            ->with('success','Ocupación eliminada con éxito');
    }
}
