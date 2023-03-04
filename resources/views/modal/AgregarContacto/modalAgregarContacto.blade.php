<div class="modal fade" id="modal-agregar-contacto" aria-labelledby="modal-agregar-contacto" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Contacto</h5>

                <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">

                <input type="hidden" class="d-none" id="modalagregarcontacto" name="modalagregarcontacto">

                <div class="form-row">
                    <div class="form-group col-lg-6">

                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button id="btn_agregarcontacto_pc" type="button" class="btn rounded btn-info ml-2">Nuevo</button>
                            <button id="btn_agregarcontacto_f" type="button" class="btn rounded btn-secondary  ml-2">Cambiar Nombre</button>
                            <button id="btn_agregarcontacto_g" type="button" class="btn rounded btn-warning  ml-2">Bloqueo</button>
                            <button id="btn_agregarcontacto_b" type="button" class="btn rounded btn-danger  ml-2">Cambiar Numero</button>
                        </div>

                    </div>
                </div>

                <div id="modal-agregarcontacto-pc-container" class="modal-agregarcontacto-pc-container">
                    @include('modal.AgregarContacto.partials.agregarcontacto_pc')

                </div>

                <div id="modal-agregarcontacto-f-container" class="modal-agregarcontacto-f-container">
                    @include('modal.AgregarContacto.partials.agregarcontacto_f')
                </div>

                <div id="modal-correccionpedido-g-container" class="modal-agregarcontacto-g-container">
                    @include('modal.AgregarContacto.partials.agregarcontacto_g')
                </div>

                <div id="modal-correccionpedido-b-container" class="modal-agregarcontacto-b-container">
                    @include('modal.AgregarContacto.partials.agregarcontacto_b')
                </div>

            </div>

        </div>
    </div>
</div>
