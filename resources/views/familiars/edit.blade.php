@extends('layouts.app')
@section('headSection')

    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">

@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Residente
                <small>Editar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('residentes.index') }}">Residentes</a></li>
                <!--<li class="active">Edit Form</li>-->
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('residentes.update',$residente->id) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="box-body">
                                @include('includes.messages')
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">

                                        <div class="form-group">
                                            {{Form::label('nombre', 'Nombre')}}
                                            {{Form::text('nombre', $residente->persona->nombre, ['class' => 'form-control','placeholder'=>'Nombre'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            {{Form::label('apellido', 'Apellido')}}
                                            {{Form::text('apellido', $residente->persona->apellido, ['class' => 'form-control','placeholder'=>'Apellido'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('tipoDocumento', 'Tipo')}}
                                            {{ Form::select('tipoDocumento',['DNI'=>'DNI','PAS'=>'Pasaporte','CI'=>'Cedula'], $residente->persona->tipoDocumento,['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('documento', 'Documento')}}
                                            {{Form::text('documento', $residente->persona->documento, ['class' => 'form-control','placeholder'=>'Documento'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            <label for="genero">Género</label>
                                            {{ Form::select('genero',[''=>'','M'=>'M','F'=>'F','X'=>'X'], $residente->persona->genero,['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            {{Form::label('email', 'E-mail')}}
                                            {{Form::email('email', $residente->persona->email, ['class' => 'form-control','placeholder'=>'E-mail'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('telefono', 'Teléfono')}}
                                            {{Form::text('telefono', $residente->persona->telefono, ['class' => 'form-control','placeholder'=>'Telefono'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            {{Form::label('domicilio', 'Domicilio')}}
                                            {{Form::text('domicilio', $residente->persona->domicilio, ['class' => 'form-control','placeholder'=>'Domicilio'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('nacimiento', 'Nacimiento')}}
                                            {{Form::date('nacimiento', ($residente->persona->nacimiento)?date('Y-m-d', strtotime($residente->persona->nacimiento)):'', ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                    <!--<div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('fallecimiento', 'Fallecimiento')}}
                                            {{Form::date('fallecimiento', ($residente->persona->fallecimiento)?date('Y-m-d', strtotime($residente->persona->fallecimiento)):'', ['class' => 'form-control'])}}
                                        </div>
                                    </div>-->
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('ingreso', 'Ingreso')}}
                                            {{Form::date('ingreso', ($residente->ingreso)?date('Y-m-d', strtotime($residente->ingreso)):'', ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('baja', 'Baja')}}
                                            {{Form::date('baja', ($residente->baja)?date('Y-m-d', strtotime($residente->baja)):'', ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">

                                        <div class="form-group">
                                            <label for="foto">Foto</label>
                                            @if($residente->persona->foto)
                                                <img id="original" src="{{ url('images/'.$residente->persona->foto) }}" height="200">
                                            @endif
                                            <input type="file" name="foto" class="form-control" placeholder="">

                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-8">

                                        <div class="form-group">
                                            {{Form::label('observaciones', 'Observaciones')}}
                                            {{Form::textarea('observaciones',  $residente->persona->observaciones, ['class' => 'form-control'])}}

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a href='{{ route('residentes.index') }}' class="btn btn-warning">Volver</a>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.box -->


                </div>
                <!-- /.col-->
            </div>
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('footerSection')
    <!-- jQuery 3 -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- page script -->

@endsection
