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
                <i class="fa fa-people-roof" aria-hidden="true"></i> Familiar
                <small>Crear</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('familiars.index', array('residenteId' =>$residente->id))  }}">Familiares</a></li>
                <!--<li class="active">Create Form</li>-->
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            @if($residente->persona->foto)
                                <img id="original" class="img-circle" src="{{ url('images/'.$residente->persona->foto) }}" width="100px;">
                            @else
                                <img id="original" class="img-circle" src="{{ url('images/user.png') }}" >
                            @endif
                            <h3 class="box-title"> {{ $residente->persona->getFullNameAttribute() }}</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('familiars.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="box-body">
                                @include('includes.messages')
                                {{Form::hidden('idResidente',$residente->id)}}
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            <label for="tipo">Tipo</label>
                                            {{ Form::select('tipoDocumento',['DNI'=>'DNI','PAS'=>'Pasaporte','CI'=>'Cedula'], '',['class' => 'form-control','id'=>'tipoDocumento']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('documento', 'Documento')}}
                                            {{Form::text('documento', '', ['class' => 'form-control','placeholder'=>'Documento'])}}
                                        </div>

                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">

                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            <label for="apellido">Apellido</label>
                                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" value="{{ old('apellido') }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            <label for="genero">G??nero</label>
                                            {{ Form::select('genero',[''=>'','M'=>'M','F'=>'F','X'=>'X'], '',['class' => 'form-control','id'=>'genero']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            {{Form::label('email', 'E-mail')}}
                                            {{Form::email('email', '', ['class' => 'form-control','placeholder'=>'E-mail'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            <label for="telefono">Tel??fono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono" value="{{ old('telefono') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            {{Form::label('domicilio', 'Domicilio')}}
                                            {{Form::text('domicilio', '', ['class' => 'form-control','placeholder'=>'Domicilio'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('nacimiento', 'Nacimiento')}}
                                            {{Form::date('nacimiento', '', ['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            <label for="parentesco">Parentesco</label>
                                            <input type="text" class="form-control" id="parentesco" name="parentesco" placeholder="Parentesco" value="{{ old('parentesco') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            <label for="principal">Principal</label><br>
                                            <input type="checkbox"  id="principal" name="principal" >
                                        </div>
                                    </div>

                                </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a href='{{ route('familiars.index', array('residenteId' =>$residente->id)) }}' class="btn btn-warning">Volver</a>
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


    <!-- CSS -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript">

        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

            $( "#documento" ).autocomplete({
                source: function( request, response ) {
                    // Fetch data
                    $.ajax({
                        url:"{{route('searchfamiliar')}}",
                        type: 'get',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                    // Set selection
                    $('#documento').val(ui.item.documento); // display the selected text
                    $('#tipoDocumento').val(ui.item.tipoDocumento);
                    $('#nombre').val(ui.item.nombre);
                    $('#apellido').val(ui.item.apellido);

                    $("#genero").val(ui.item.genero);
                    $("#email").val(ui.item.email);
                    $("#domicilio").val(ui.item.domicilio);
                    $("#telefono").val(ui.item.telefono);
                    $("#nacimiento").val(ui.item.nacimiento);


                    return false;
                }
            });

        });
    </script>


@endsection
