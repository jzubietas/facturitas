<!-- Modal -->
<div class="modal fade" id="modal-motorizado-entregar-confirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Entregas de motorizado Confirmaciones</h5>
            </div>
            
            <form id="formulariomotorizadoentregarconfirm" name="formulariomotorizadoentregarconfirm" enctype="multipart/form-data">
                <input type="hidden" id="hiddenMotorizadoEntregarConfirm" name="hiddenMotorizadoEntregarConfirm">
                <div class="modal-body">

                   
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrarmodalatender">Cerrar</button>
                    <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
