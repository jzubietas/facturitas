@foreach($motorizadosAuthorizaciones as $id=>$cantidad)
    <div id="notificacion-pedidos-no-recibidos-{{$id}}" style="position: fixed;
    bottom: 16px;
    right: 16px;
    width: 400px;
    height: 250px;
    background-color: #b14237e0;
    z-index: 999;
    border-radius: 8px; color:white; padding:24px;">
        <i class="fa fa-exclamation-triangle text-warning font-36" aria-hidden="true"></i>
        <h3 class="font-24 mt-12 font-weight-bold">SOBRES NO RECIBIDOS</h3>
        <p>
            Actualmente hay <b class="badge badge-warning">{{$cantidad}}</b> sobres no recibidos de parte del motorizado
            del <b class="badge badge-warning">{{$zonemotorizados[$id]}}</b>,
            autorice la ruta para que puedan
            salir a Reparto
        </p>
        <button data-loading-text="Autorizando ..."
                data-btn-text="Autorizar Ruta"
                data-authorization-button="{{route('settings.authorization-motorizado',['user'=>$id,'action'=>'autorizar_ruta'])}}"
                class="btn btn-success">
            <span style="display: none" class="spinner-border spinner-border-sm" role="status"
                  aria-hidden="true"></span>
            Autorizar Ruta
        </button>
    </div>
@endforeach
@foreach($reprogramados as $grupo)
    <div id="notificacion-pedidos-reprogramados-{{$grupo->id}}" style="position: fixed;
    bottom: 16px;
    right: 16px;
    width: 400px;
    height: auto;
    background-color: #00b972;
    z-index: 999;
    border-radius: 8px; color:white; padding:24px;">
        <i class="fa fa-exclamation-triangle text-warning font-36" aria-hidden="true"></i>
        <h3 class="font-24 mt-12 font-weight-bold">SOBRE REPROGRAMADO</h3>
        <p>
            El motorizado <b>{{$grupo->motorizado->zona}}</b> a reprogramado el pedido <b>{{$grupo->codigos}}</b> para
            la fecha <b>{{$grupo->reprogramacion_at->format('d-m-Y')}}</b><br>
            <b>Celular del cliente: {{$grupo->celular_cliente}}</b>
        </p>
        <div class="my-4">
            <a href="{{$grupo->getFirstMedia('reprogramacion_adjunto')->getFullUrl()}}" target="_blank">
                <img src="{{$grupo->getFirstMedia('reprogramacion_adjunto')->getFullUrl()}}" class="w-100">
            </a>
        </div>
        <button data-loading-text="Autorizando reprogramación ..."
                data-btn-text="Aceptar reprogramación"
                data-reprogramacion-button="{{route('settings.authorization-motorizado',['user'=>$grupo->id,'direccion_grupo'=>$grupo->id,'action'=>'reprogramacion'])}}"
                class="btn btn-warning">
            <span style="display: none" class="spinner-border spinner-border-sm" role="status"
                  aria-hidden="true"></span>
            Aceptar reprogramación
        </button>
    </div>
@endforeach
@push('js')
    <script>
        (function () {
            $(document).ready(function () {
                $(document).on('click', '[data-authorization-button]', function () {
                    const action = $(this).data('authorization-button')
                    $.post(action).done(function (id) {
                        $("#notificacion-pedidos-no-recibidos-" + id).remove()
                    })
                })
                $(document).on('click', '[data-reprogramacion-button]', function () {
                    const action = $(this).data('reprogramacion-button')
                    $.post(action).done(function (id) {
                        $("#notificacion-pedidos-reprogramados-" + id).remove()
                    })
                })
            })
        })()
    </script>
@endpush
