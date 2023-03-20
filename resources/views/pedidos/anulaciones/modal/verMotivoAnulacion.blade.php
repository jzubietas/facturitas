<div class="modal fade" id="modal-ver_motivoanulacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Motivo Anulacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{--{{ Form::Open(['route' => ['users.destroy', $user], 'method' => 'delete']) }}--}}
            <form id="frmDesactivarUsuario">
                <div class="modal-body">
                    <p style="text-align: justify; font-size:20px;"><strong>Motivo:</strong> <br> <span id="txtMotivoAnulacion"></span> </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
