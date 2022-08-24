@extends('layouts.app')
@section('headSection')

    <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-capsules" aria-hidden="true"></i> Medicamento
                <small>Crear</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('residenteMedicamentos.index', array('residenteId' =>$residente->id))  }}">Medicamentos</a></li>
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
                        <form role="form" action="{{ route('residenteMedicamentos.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="box-body">
                                @include('includes.messages')
                                {{Form::hidden('idResidente',$residente->id)}}
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">
                                        <div class="form-group">
                                            <label for="medicamento">Medicamento</label>
                                            {{ Form::select('medicamento',$medicamentos, '',['class' => 'form-control js-example-basic-single']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('alta', 'Alta')}}
                                            {{Form::date('alta', date("Y-m-d", strtotime(now())), ['class' => 'form-control','placeholder'=>'Alta'])}}
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('stock', 'Stock')}}
                                            {{Form::number('stock', '', ['class' => 'form-control','placeholder'=>'Stock'])}}
                                        </div>
                                    </div>




                                </div>
                                <div class="row">
                                    <div class="col-lg-offset-3 col-lg-6 col-md-2">
                                        <div class="form-group">
                                            {{Form::label('dosis', 'Dosis diaria')}}
                                            {{Form::number('dosis', '', ['class' => 'form-control','placeholder'=>'Dosis','step' => '0.01'])}}
                                        </div>

                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-4">

                                        <div class="form-group">
                                            <label for="toma">Toma diaria</label>
                                            <input type="text" class="form-control" id="toma" name="toma" placeholder="Toma diaria" value="{{ old('toma') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-offset-3 col-lg-6 col-md-3">
                                        <div class="form-group">
                                            {{Form::label('suspension', 'Suspensión')}}
                                            {{Form::date('suspension', '', ['class' => 'form-control','placeholder'=>'Suspensión'])}}
                                        </div>
                                    </div>
                                </div>


                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a href='{{ route('residenteMedicamentos.index', array('residenteId' =>$residente->id)) }}' class="btn btn-warning">Volver</a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.js-example-basic-single').select2();

        });
    </script>



@endsection
