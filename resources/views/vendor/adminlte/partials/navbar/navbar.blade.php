<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    <div class="tpl-snow">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

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
      @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_JEFE_LLAMADAS,\App\Models\User::ROL_LLAMADAS,\App\Models\User::ROL_ENCARGADO,\App\Models\User::ROL_ASESOR]))
        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
          <button class="nav-link btn btn-outline-info btn-sm  font-18 border-0 font-weight-bold btnLlamadas"
                  data-toggle="modal" data-target="#modal-llamadas-1" type="button">
                <i class="fas fa-users text-blue btnLlamadasCont" aria-hidden="true" ></i>
          </button>
        </li>
      @endif

      @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_ENCARGADO]))
        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
          <button class="nav-link btn btn-success btn-sm  font-11 font-weight-bold d-flex align-items-center justify-content-center a-navbar" id="btn_componente-1"
                  data-toggle="modal" data-target="#modal-annuncient-1" type="button">
            <i class="fas fa-bell" aria-hidden="true"></i>
            <p class="m-0 text-card-navbar ml-2">Permisos</p>
          </button>
        </li>
      @endif


        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-2">
            <button class="nav-link btn btn-secondary btn-sm font-11 font-weight-bold d-flex align-items-center justify-content-center a-navbar" id="btn_buscar_scan"
                    data-toggle="modal" data-target="#modal-escanear-estado-sobre" type="button">
                <i class="fa fa-barcode" aria-hidden="true"></i> <p class="m-0 text-card-navbar ml-2">Buscar</p>
            </button>
        </li>

        <div id="divListadoVidas" class="vidas-navbar d-flex"></div>

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
