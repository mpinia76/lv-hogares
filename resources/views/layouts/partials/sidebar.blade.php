  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            @if(Auth::user()->image)
          <img src="{{ url('/images/'.Auth::user()->image) }}" class="img-circle" alt="User Image">
            @else
                <img src="{{ url('/images/user.png') }}" class="img-circle" alt="User Image">
            @endif
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
        @can('usuario-listar')<ul class="sidebar-menu" data-widget="tree">
          <li class="header">SEGURIDAD</li>

          @can('usuario-listar')<li><a href="{{ route('users.index') }}"><i class="fa fa-circle-o"></i> Usuarios</a></li>@endcan
          @can('rol-listar')<li><a href="{{ route('roles.index') }}"><i class="fa fa-circle-o"></i> Roles</a></li>@endcan

      </ul>@endcan
        @can('usuario-listar')<ul class="sidebar-menu" data-widget="tree">
            <li class="header">ADMINISTRACION</li>

            @can('residente-listar')<li><a href="{{ route('residentes.index') }}"><i class="fa fa-circle-o"></i> Residentes</a></li>@endcan


        </ul>@endcan
    </section>
    <!-- /.sidebar -->
  </aside>

