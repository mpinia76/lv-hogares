<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Medicamento;
use Illuminate\Database\QueryException;


class MedicamentoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:medicamento-listar|medicamento-crear|medicamento-editar|medicamento-eliminar', ['only' => ['index','store']]);
        $this->middleware('permission:medicamento-crear', ['only' => ['create','store']]);
        $this->middleware('permission:medicamento-editar', ['only' => ['edit','update']]);
        $this->middleware('permission:medicamento-eliminar', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $medicamentos = Medicamento::all();
        return view ('medicamentos.index',compact('medicamentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('medicamentos.create');
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


        $medicamento = Medicamento::create($input);


        return redirect()->route('medicamentos.index')
            ->with('success','Medicamento creada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicamento = Medicamento::find($id);
        return view('medicamentos.show',compact('medicamento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medicamento = Medicamento::find($id);


        return view('medicamentos.edit',compact('medicamento'));
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




        $medicamento = Medicamento::find($id);
        $medicamento->update($input);



        return redirect()->route('medicamentos.index')
            ->with('success','Medicamento modificada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        Medicamento::find($id)->delete();

        return redirect()->route('medicamentos.index')
            ->with('success','Medicamento eliminada con éxito');
    }
}
