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



        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-1">
            <button class="btn btn-success  font-11 font-weight-bold" id="btn_componente-1"
                    data-toggle="modal" data-target="#modal-annuncient-1" type="button">
                <i class="fa fa-accusoft" aria-hidden="true"></i> MODAL
            </button>
        </li>


        <li class="nav-item dropdown show p-1" id="my-btn-annuncements-2">
            <button class="btn btn-secondary font-11 font-weight-bold" id="btn_buscar_scan"
                    data-toggle="modal" data-target="#modal-escanear-estado-sobre" type="button">
                <i class="fa fa-barcode" aria-hidden="true"></i> Buscar
            </button>
        </li>

        <li class="nav-item dropdown show" id="my-annuncements-1">
            <a href="" class="nav-link" data-toggle="dropdown" aria-expanded="true">
                <i class="fas fa-envelope text-white"></i>
                <span class="d-none badge navbar-badge text-bold text-xs badge-danger badge-pill">3</span>
            </a>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-2">
            <a href="" class="nav-link" data-toggle="dropdown" aria-expanded="true">
                <i class="fas fa-envelope text-white"></i>
                <span class="d-none badge navbar-badge text-bold text-xs badge-danger badge-pill">3</span>
            </a>
        </li>
        <li class="nav-item dropdown show" id="my-annuncements-3">
            <a href="" class="nav-link" data-toggle="dropdown" aria-expanded="true">
                <i class="fas fa-envelope text-white"></i>
                <span class="d-none badge navbar-badge text-bold text-xs badge-danger badge-pill">3</span>
            </a>
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
