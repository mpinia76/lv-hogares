@extends('layouts.app')
@section('headSection')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}">
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
                <i class="fa fa-user-md" aria-hidden="true"></i>Médicos de {{ $residente->persona->getFullNameAttribute() }}
                <!--<small>Create, Read, Update, Delete</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('medicos.index') }}">Médicos</a></li>
                <!--<li class="active">Data tables</li>-->
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Médicos</h3>
                            <a class='pull-right btn btn-success' href="{{ route('medicos.create', array('residenteId' =>$residente->id)) }}">Nuevo</a>
                        </div>
                        @include('includes.messages')


                    <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nro.</th>

                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Especialidad</th>
                                    <th>Acciones</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($residente->medicos as $medico)

                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>

                                        <td>{{ $medico->persona->getFullNameAttribute() }}</td>
                                        <td>{{$medico->persona->telefono}}</td>
                                        <td>{{$medico->especialidad->nombre}}</td>

                                        <td>@can('medico-editar')<a title="editar" href="{{ route('medicos.edit',array($residente->id,'idMedico'=>$medico->id)) }}"><span class="glyphicon glyphicon-edit"></span></a>@endcan
                                        @can('medico-eliminar')
                                            <form id="delete-form-{{ $medico->id }}" method="post" action="{{ route('medicos.destroy',array($residente->id,'idMedico'=>$medico->id)) }}" style="display: none">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            @endcan
                                            <a title="eliminar" href="" onclick="
                                                if(confirm('Está seguro?'))
                                                {
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{ $medico->id }}').submit();
                                                }
                                                else{
                                                event.preventDefault();
                                                }" ><span class="glyphicon glyphicon-trash"></span></a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Especialidad</th>
                                    <th>Acciones</th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
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
    <!-- DataTables -->
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
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
    <script>
        $(document).ready(function() {
            $('#example1').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-AR.json"
                }
            });
        });
        $(function () {
            $('#example1').DataTable()
            $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false
            })
        })
    </script>
@endsection
