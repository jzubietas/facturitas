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

        @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN,\App\Models\User::ROL_ENCARGADO]))
            <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
                <button class="nav-link btn btn-success btn-sm  font-11 font-weight-bold" id="btn_componente-1"
                        data-toggle="modal" data-target="#modal-annuncient-1" type="button">
                    <i class="fas fa-bell" aria-hidden="true"></i> PERMISOS
                </button>
            </li>
        @endif


        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-2">
            <button class="nav-link btn btn-secondary btn-sm font-11 font-weight-bold" id="btn_buscar_scan"
                    data-toggle="modal" data-target="#modal-escanear-estado-sobre" type="button">
                <i class="fa fa-barcode" aria-hidden="true"></i> Buscar
            </button>
        </li>

        <li class="nav-item dropdown show" id="my-annuncements-1">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <img src="{{asset('images/header/icon-a.png')}}" style=" width: 67%; ">
            </span>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-2">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <img src="{{asset('images/header/icon-b.png')}}" style=" width: 67%; ">
            </span>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-3">
            <span class="nav-link p-1 m-0" aria-expanded="true">
                <img src="{{asset('images/header/icon-c.png')}}" style=" width: 67%; ">
            </span>
        </li>
        {{-- Custom right links --}}



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
