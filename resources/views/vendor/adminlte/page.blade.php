@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    <style>
        @foreach(get_color_role() as $rol=>$color)
            .bg-{{Str::slug($rol)}} {
            @if(is_array($color))
            background: {{$color[0]}}!important;;
            color: {{$color[1]}}!important;;
            @else
            background: {{$color}};
            color: #000!important;
            @endif
            font-weight: bold!important;;
        }
        @endforeach
    </style>
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation --}}
        @if($layoutHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>

    <div id="notificacion-pedidos-no-recibidos" style="position: fixed;
    bottom: 16px;
    right: 16px;
    width: 400px;
    height: 250px;
    background-color: #b14237e0;
    z-index: 999;
    border-radius: 8px; color:white; padding:24px;">


        <i class="fa fa-exclamation-triangle text-warning font-36" aria-hidden="true"></i>
        <h3 class="font-24 mt-12 font-weight-bold">SOBRES NO RECIBIDOS</h3>
        <p>Actualmente hay <b>X</b> sobres no recibidos de parte de los motorizados, autorice la ruta para que puedan salir a Reparto</p>
        <a href="#" data-target="modal-autorizar-ruta" data-toggle="modal" class="btn btn-success">Autorizar Ruta</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-autorizar-ruta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Autorizar ruta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Ingrese un sustento antes de autorizar la ruta de los motorizados:</p>
                    <textarea class="form-control" placeholder="Ingrese un sustento"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger" id="borrate">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
