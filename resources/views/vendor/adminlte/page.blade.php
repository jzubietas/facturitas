@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    <style>
        @foreach(get_color_role() as $rol=>$color)
            .bg-{{Str::slug($rol)}}  {
            @if(is_array($color))
             background: {{$color[0]}} !important;;
            color: {{$color[1]}} !important;;
            @else
             background: {{$color}};
            color: #000 !important;
            @endif
             font-weight: bold !important;;
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
    <div id="alert-authorization">
        <x-common-autorizar-ruta-motorizado></x-common-autorizar-ruta-motorizado>
    </div>

    @include('pedidos.modal.escanear_estado_sobres')
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        $(document).ready(function () {
            if(document.location.href!='{{route('envios.distribuirsobres')}}'){
                for (var key in localStorage){
                    if(key.includes('.envios.distribuirsobres')){
                        localStorage.removeItem(key)
                    }
                }
            }

            $('#modal-escanear-estado-sobre').on('show.bs.modal', function (event) {
                $('#info-pedido').html('<div class="text-center"><img src="{{asset('imagenes/scan.gif')}}" width="300" class="mr-8"><h5 class="font-weight-bold">Escanee un pedido para saber sus detalles</h5></div>');
                $('#input-info-pedido').focus();
                $('#input-info-pedido').val("");

                $('#input-info-pedido').change(function (event) {
                    event.preventDefault();
                    var codigo_caturado = ($(this).val() || '').trim();
                    var codigo_mejorado = codigo_caturado.replace(/['']+/g, '-').replaceAll("'", '-').replaceAll("(", '*');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('escaneo.estado_pedidos') }}",
                        data: {
                            'codigo' : codigo_mejorado,
                        },
                        success: function (data) {
                            console.log(data);
                            if(data.codigo == 0){
                                $('#info-pedido').html('<div class="text-danger text-center"><i class="fa fa-exclamation-triangle font-44" aria-hidden="true"></i><br><h4 class="font-weight-bold">Este pedido no se encuentra en el sistema</h4></div>');
                            }else if(data.codigo == 1){
                                $('#input-info-pedido').val("");

                                var InfoString = '<h4 class="font-16 font-weight-bold">Información del pedido:</h4> <table class="table w-100">';
                                InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Codigo</td><td>' + data.pedido.codigo + '</td><td class="font-weight-bold p-8">Estado</td><td style="width: 250px;"><span class="bagde p-8 br-12 font-weight-bold" style="font-size:12px; background-color: '+ data.pedido.condicion_envio_color +'">' +data.pedido.condicion_envio + '<s/pan></td></tr>';
                                InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0"></td><td></td><td class="font-weight-bold p-8"></td><td></td></tr>';
                                // SI TIENE DIRECCION
                                if(data.pedido.estado_sobre == 0){

                                    InfoString += '<tr><td class="font-weight-bold p-8">Tiene Direccion</td><td colspan="3"> NO TIENE DIRECCION</td></tr>';
                                }else {

                                    InfoString += '<tr><td colspan="4" class="font-weight-bold p-8" style="background-color:#ededed;"><i class="fa fa-map-marker text-success mr-12" aria-hidden="true"></i> DIRECCION</td></tr>';
                                    InfoString += '<tr><td class="font-weight-bold">Dirección</td><td>' + data.pedido.env_direccion + '</td>';
                                    if (data.pedido.env_zona == 'OLVA') {
                                        InfoString += '<tr>';
                                    } else {
                                        InfoString += '<td class="font-weight-bold p-8">Distrito</td><td>' + data.pedido.env_distrito + '</td></tr>';
                                    }

                                    InfoString += '<tr><td class="font-weight-bold">Zona</td><td>' + data.pedido.env_zona + '</td><td class="font-weight-bold p-8">Destino</td><td>' + data.pedido.env_destino + '</td></tr>';

                                }
                                // SI ESTA ASIGNADO A UN MOTORIZADO
                                if(data.pedido.direccion_grupo == null) {
                                    InfoString += '<tr><td class="font-weight-bold p-8">Esta asignado a una zona?</td><td colspan="3"> NO</td></tr>';
                                }else{
                                    //SI TIENE MOTORIZADO
                                    if(data.pedido.direcciongrupo.motorizado == null){
                                        InfoString += '<tr><td class="font-weight-bold p-8">Se encuentra en Reparto?</td><td colspan="3"> NO</td></tr>';
                                    }else{
                                        if(data.pedido.direcciongrupo.fecha_salida == null){
                                            var env_fecha_salida = "Fecha no asignada";
                                        }else{
                                            var env_fecha_salida = data.pedido.direcciongrupo.fecha_salida;
                                        }
                                        InfoString += '<tr><td colspan="4" class="font-weight-bold p8 pt-8 pb-8" style="background-color:#ededed;"><i class="fa fa-motorcycle text-primary mr-12" aria-hidden="true"></i> COURIER</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Nombre Motorizado</td><td>'+ data.pedido.direcciongrupo.motorizado.name +'</td><td class="font-weight-bold p-8">Zona motorizado</td><td>'+ data.pedido.direcciongrupo.motorizado.zona +'</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">Zona</td><td>'+ data.pedido.direcciongrupo.distribucion +'</td><td class="font-weight-bold p-8">Fecha de salida</td><td>'+ data.pedido.direcciongrupo.fecha_salida_format  +'</td></tr>';
                                        InfoString += '<tr><td class="font-weight-bold p-8 pt-0 pb-0">ID Grupo</td><td>'+ data.pedido.direcciongrupo.id +'</td><td class="font-weight-bold p-8">Estado Grupo</td><td><span class="bagde p-8 br-12 font-weight-bold font-11" style="background-color: #f97100; padding: 4px 16px !important; background-color: '+ data.pedido.condicion_envio_color +'">'+ data.pedido.direcciongrupo.condicion_envio  +'</span></td></tr>';

                                        //SI TIENE MOTORIZADO
                                        if(data.pedido.condicion_envio_code == 10){
                                            InfoString += '<tr><td colspan="4" class="font-weight-bold p8 pt-8 pb-8" style="background-color:#ededed;"><i class="fa fa-paperclip text-danger mr-12" aria-hidden="true"></i> ADJUNTOS</td></tr>';
                                            InfoString += '<tr><td><img style="width:150px; height: 150px; object-fit:cover;" src="'+ data.pedido.direcciongrupo.foto1 +'"></td><td><img style="width:150px; height: 150px; object-fit:cover;" src="'+ data.pedido.direcciongrupo.foto2 +'"></td><td class="font-weight-bold p-8"><img style="width:150px; height: 150px; object-fit:cover;" src="'+ data.pedido.direcciongrupo.foto3 +'"></td><td></td></tr>';
                                        }else{
                                            InfoString += '<tr><td class="font-weight-bold p-8">Tiene adjuntos?</td><td colspan="3"> NO</td></tr>';
                                        }
                                    }
                                }
                            }
                            $('#info-pedido').html(InfoString);

                        }
                    }).always(function(){
                        $('#codigo_confirmar').focus();
                    });

                    return false;
                });
            });
        })
    </script>
@stop
