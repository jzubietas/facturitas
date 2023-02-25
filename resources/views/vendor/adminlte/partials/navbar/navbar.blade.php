<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
      @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_JEFE_LLAMADAS,\App\Models\User::ROL_LLAMADAS]))
        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
          <button class="nav-link btn btn-outline-info btn-sm  font-18 border-0 font-weight-bold btnLlamadas"
                  data-toggle="modal" data-target="#modal-llamadas-1" type="button">
            <i class="fas fa-users text-blue btnLlamadasCont" aria-hidden="true" ></i>
          </button>
        </li>
      @endif

      @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_ENCARGADO]))
        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
          <button class="nav-link btn btn-success btn-sm  font-11 font-weight-bold" id="btn_componente-1"
                  data-toggle="modal" data-target="#modal-annuncient-1" type="button">
            <i class="fas fa-bell" aria-hidden="true"></i> PERMISOSS
          </button>
        </li>
      @endif


        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-2">
            <button class="nav-link btn btn-secondary btn-sm font-11 font-weight-bold" id="btn_buscar_scan"
                    data-toggle="modal" data-target="#modal-escanear-estado-sobre" type="button">
                <i class="fa fa-barcode" aria-hidden="true"></i> Buscar
            </button>
        </li>
<!--=============================================================== LOGICA DE VIDAS ======================================================== -->
        <div id="id_div_vidas" class="navbar-nav ml-auto">
        <li class="nav-item dropdown show" id="my-annuncements-1">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <a class="font-36 border-0 font-weight-bold btnVidas1 ml-2"
                    data-toggle="modal" data-target="#modal-vidas-1" type="button">
                  <i class="fas fa-male text-success btnVidasCont" aria-hidden="true" ></i>
                </a>
            </span>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-2">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <a class=" font-36 border-0 font-weight-bold btnVidas2 ml-2"
                   data-toggle="modal" data-target="#modal-vidas-2" type="button">
                  <i class="fas fa-male text-warning btnVidasCont2" aria-hidden="true" ></i>
                </a>
            </span>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-3">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <a class=" font-36 border-0 font-weight-bold btnVidas3 ml-2"
                   data-toggle="modal" data-target="#modal-vidas-3" type="button">
                  <i class="fas fa-male text-danger btnVidasCont3" aria-hidden="true" ></i>
                </a>
            </span>
        </li>
        </div>
        {{-- Custom right links --}}
<!--=============================================================== END LOGICA DE VIDAS ======================================================== -->


        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
</nav>
