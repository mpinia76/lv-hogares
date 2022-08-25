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
                <i class="fa fa-capsules" aria-hidden="true"></i> Medicamentos
                <!--<small>Create, Read, Update, Delete</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ route('residenteMedicamentos.index') }}">Medicamentos</a></li>
                <!--<li class="active">Data tables</li>-->
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">

                            @if($residente->persona->foto)
                                <img id="original" class="img-circle" src="{{ url('images/'.$residente->persona->foto) }}" width="100px;">
                            @else
                                <img id="original" class="img-circle" src="{{ url('images/user.png') }}" >
                            @endif
                                <h3 class="box-title"> {{ $residente->persona->getFullNameAttribute() }}</h3>
                            <a class='pull-right btn btn-success' href="{{ route('residenteMedicamentos.create', array('residenteId' =>$residente->id)) }}">Nuevo</a>
                        </div>
                        @include('includes.messages')


                    <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nro.</th>

                                    <th>Medicamento</th>
                                    <th>Alta</th>
                                    <th>Stock</th>
                                    <th>Actual</th>
                                    <th>D. diaria</th>
                                    <th>Toma diaria</th>
                                    <th>Reposición</th>
                                    <th>Suspensión</th>
                                    <th>Acciones</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($residente->medicamentos as $medicamento)
                                    <?php
                                        $color='#f9f9f9';

                                        if ($medicamento->getReponerAttribute()==1){
                                            $color='orange';
                                        }
                                        if(($medicamento->pivot->suspension)&&($medicamento->pivot->suspension<now())){
                                            $color='#ADD8E6';
                                        }
                                        ?>
                                    <tr style="background-color: {{$color}}">
                                        <td>{{ $loop->index + 1 }}</td>

                                        <td>{{ $medicamento->getFullNameAttribute() }}</td>
                                        <td>{{($medicamento->pivot->alta)?date('d/m/Y', strtotime($medicamento->pivot->alta)):''}}</td>
                                        <td>{{$medicamento->pivot->stock}}</td>
                                        <td>{{$medicamento->getStockActualAttribute()}}</td>
                                        <td>{{$medicamento->pivot->dosis}}</td>
                                        <td>{{$medicamento->pivot->toma}}</td>
                                        <td>{{date('d/m/Y', strtotime($medicamento->getReposicionAttribute()))}}</td>
                                        <td>{{($medicamento->pivot->suspension)?date('d/m/Y', strtotime($medicamento->pivot->suspension)):''}}</td>
                                        <td>@can('residenteMedicamento-editar')<a title="editar" href="{{ route('residenteMedicamentos.edit',array($residente->id,'idMedicamento'=>$medicamento->id)) }}"><span class="glyphicon glyphicon-edit"></span></a>@endcan
                                        @can('residenteMedicamento-eliminar')
                                            <form id="delete-form-{{ $medicamento->id }}" method="post" action="{{ route('residenteMedicamentos.destroy',array($residente->id,'idMedicamento'=>$medicamento->id)) }}" style="display: none">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            @endcan
                                            <a title="eliminar" href="" onclick="
                                                if(confirm('Está seguro?'))
                                                {
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{ $medicamento->id }}').submit();
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

                                    <th>Medicamento</th>
                                    <th>Alta</th>
                                    <th>Stock</th>
                                    <th>Actual</th>
                                    <th>D. diaria</th>
                                    <th>Toma diaria</th>
                                    <th>Reposición</th>
                                    <th>Suspensión</th>
                                    <th>Acciones</th>

                                </tr>
                                </tfoot>

                            </table>
                            <div class="row">
                                <div class="col-lg-offset-0 col-lg-2 col-md-2">
                                    <div style="background-color: orange">Reponer medicación</div>
                                </div>
                                <div class="col-lg-offset-0 col-lg-2 col-md-2">
                                    <div style="background-color: #ADD8E6">Medicación suspendida</div>
                                </div>
                                <div class="col-lg-offset-0 col-lg-2 col-md-2">
                                    <div style="background-color: #f9f9f9">Medicación vigente</div>
                                </div>
                            </div>
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
