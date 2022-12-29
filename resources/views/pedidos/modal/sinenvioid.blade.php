<!-- Modal -->
<div class="modal fade" id="modal-sinenvio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-link">
                <h5 class="modal-title" id="exampleModalLabel">Pedido sin envío</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulariosinenvio" name="formulariosinenvio" enctype="multipart/form-data">
                {{-- Form::Open(['route' => ['operaciones.sinenvio', $pedido],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) --}}
                <input type="hidden" id="hiddenSinenvio" name="hiddenSinenvio">
                <div class="modal-body">
                    <p>Desea enviar el PEDIDO <strong class="textcode">PED000</strong> <b style="color: #ff0000">SIN
                            SOBRE</b> al jefe operaciones?</p>
                    {{--<p>Está seguro que el pedido: <strong class="textcode">PED000</strong> será sin envío</p> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
