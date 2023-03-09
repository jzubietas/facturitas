<div class="modal fade" id="modal-agregar-anulacion" aria-labelledby="modal-agregar-anulacion" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Anulacion</h5>

                <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">

                <input type="hidden" class="d-none" id="modalagregaranulacion" name="modalagregaranulacion">

                <div class="form-row">
                    <div class="form-group col-lg-6">

                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button id="btn_agregaranulacion_pc" type="button" class="btn rounded btn-info ml-2">Pedido Completo</button>
                            <button id="btn_agregaranulacion_f" type="button" class="btn rounded btn-secondary  ml-2">Anulacion</button>
                        </div>

                    </div>
                </div>

                <div id="modal-agregaranulacion-pc-container" class="modal-agregaranulacion-pc-container">
                    @include('modal.AgegarAnulacion.partials.agregaranulacion_pc')
                </div>

                <div id="modal-agregaranulacion-f-container" class="modal-agregaranulacion-f-container">
                  @include('modal.AgegarAnulacion.partials.agregaranulacion_f')
                </div>

            </div>

        </div>
    </div>
</div>
