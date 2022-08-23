<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Mutual;
use Illuminate\Database\QueryException;


class MutualController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:mutual-listar|mutual-crear|mutual-editar|mutual-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:mutual-crear', ['only' => ['create','store']]);
        $this->middleware('permission:mutual-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:mutual-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $mutuals = Mutual::all();
        return view ('mutuals.index',compact('mutuals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('mutuals.create');
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


        $mutual = Mutual::create($input);


        return redirect()->route('mutuals.index')
            ->with('success','Obra Social creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mutual = Mutual::find($id);
        return view('mutuals.show',compact('mutual'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mutual = Mutual::find($id);


        return view('mutuals.edit',compact('mutual'));
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




        $mutual = Mutual::find($id);
        $mutual->update($input);



        return redirect()->route('mutuals.index')
            ->with('success','Obra Social modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Mutual::find($id)->delete();

        return redirect()->route('mutuals.index')
            ->with('success','Obra Social eliminada con éxito');
    }
}
