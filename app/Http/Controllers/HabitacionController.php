<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Habitacion;
use Illuminate\Database\QueryException;


class HabitacionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:habitacion-listar|habitacion-crear|habitacion-editar|habitacion-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:habitacion-crear', ['only' => ['create','store']]);
        $this->middleware('permission:habitacion-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:habitacion-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $habitacions = Habitacion::all();
        return view ('habitacions.index',compact('habitacions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('habitacions.create');
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


        $habitacion = Habitacion::create($input);


        return redirect()->route('habitacions.index')
            ->with('success','Habitación creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $habitacion = Habitacion::find($id);
        return view('habitacions.show',compact('habitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $habitacion = Habitacion::find($id);


        return view('habitacions.edit',compact('habitacion'));
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




        $habitacion = Habitacion::find($id);
        $habitacion->update($input);



        return redirect()->route('habitacions.index')
            ->with('success','Habitación modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Habitacion::find($id)->delete();

        return redirect()->route('habitacions.index')
            ->with('success','Habitación eliminada con éxito');
    }
}
