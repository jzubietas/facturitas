<div class="modal fade" id="modal-ver_motivoanulacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title lblTitleMotivoAnulacion" id="exampleModalLabel">Motivo Anulacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{--{{ Form::Open(['route' => ['users.destroy', $user], 'method' => 'delete']) }}--}}
            <form id="frmVerMotivoAnulacionUsuario">
                <div class="modal-body">
                    <div class="divResponsableAsesor">
                        <p style="text-align: justify; font-size:20px;"><strong>Responsable Asesor:</strong> <br> <span id="txtResponsableAsesor"></span> </p>
                    </div>
                    <div class="divResponsableEncarg">
                        <p style="text-align: justify; font-size:20px;"><strong>Responsable Encargado:</strong> <br> <span id="txtResponsableEncarg"></span> </p>
                    </div>
                    <p style="text-align: justify; font-size:20px;"><strong>Motivo:</strong> <br> <span id="txtMotivoAnulacion"></span> </p>

                    <div class="card">
                        <p style="text-align: justify; font-size:20px;"><strong>Adjuntos:</strong> </p>
                        <div class="card-body row ">
                            <div id="imagenAnulacionUsuario"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
