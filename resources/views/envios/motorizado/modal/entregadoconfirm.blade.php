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
                    <div class="row">

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto de los sobres</p>
                            <img class="foto1" src="" alt="FOTO 1" height="400px" width="400px" class="img-fluid">

                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto del domicilio</p>
                            <img class="foto2" alt="FOTO 2" height="400px" width="400px" class="img-fluid">
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><br>
                            <p class="font-weight-bold">Foto de quien recibe</p>
                            <img class="foto3" alt="FOTO 3" height="400px" width="400px" class="img-fluid">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cerrarmodalatender" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
                </div>
            {{ Form::Close() }}
        </div>
    </div>
</div>
