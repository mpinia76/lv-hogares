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
                <i class="fa fa-users" aria-hidden="true"></i> Residentes
                <!--<small>Create, Read, Update, Delete</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('residentes.index') }}">Residentes</a></li>
                <!--<li class="active">Data tables</li>-->
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Residentes</h3>
                            <a class='pull-right btn btn-success' href="{{ route('residentes.create') }}">Nuevo</a>
                        </div>
                        @include('includes.messages')


                    <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Edad</th>
                                    <th>Ingreso</th>
                                    <th>Habitación</th>
                                    <th>Acciones</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($residentes as $residente)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            @if($residente->persona->foto)
                                                <img id="original" class="img-circle" src="{{ url('images/'.$residente->persona->foto) }}" width="100px;">
                                            @else
                                                <img id="original" class="img-circle" src="{{ url('images/user.png') }}" >
                                            @endif
                                        </td>
                                        <td>{{ $residente->persona->getFullNameAttribute() }}</td>
                                        <td>{{($residente->persona->nacimiento)?$residente->persona->getAgeAttribute():''}}</td>
                                        <td>{{($residente->ingreso)?date('d/m/Y', strtotime($residente->ingreso)):''}}</td>
                                        <td>{{($residente->habitacion)?$residente->habitacion->nombre:''}}</td>
                                        <td>@can('residente-editar')<a title="editar" href="{{ route('residentes.edit',$residente->id) }}"><span class="glyphicon glyphicon-edit"></span></a>@endcan
                                        @can('residente-eliminar')
                                            <form id="delete-form-{{ $residente->id }}" method="post" action="{{ route('residentes.destroy',$residente->id) }}" style="display: none">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            @endcan
                                            <a title="eliminar" href="" onclick="
                                                if(confirm('Está seguro?'))
                                                {
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{ $residente->id }}').submit();
                                                }
                                                else{
                                                event.preventDefault();
                                                }" ><span class="glyphicon glyphicon-trash"></span></a>
                                            @can('familiar-listar')<a title="familiares" href="{{ route('familiars.index', array('residenteId' =>$residente->id)) }}"><span class="glyphicon glyphicon-user"></span></a>@endcan
                                            @can('medico-listar')<a title="médicos" href="{{ route('medicos.index', array('residenteId' =>$residente->id)) }}"><span class="glyphicon glyphicon-education"></span></a>@endcan
                                            @can('residenteMedicamento-listar')<a title="medicamentos" href="{{ route('residenteMedicamentos.index', array('residenteId' =>$residente->id)) }}"><span class="glyphicon glyphicon-erase"></span></a>@endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Nro.</th>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Edad</th>
                                    <th>Ingreso</th>
                                    <th>Habitación</th>
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
                "responsive": true,
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
