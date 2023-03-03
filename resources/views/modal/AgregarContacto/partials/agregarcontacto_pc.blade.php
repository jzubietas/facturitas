<form id="form-correccionpedido-pc" name="form-correccionpedido-pc" class="correccion">
    <input type="hidden" id="correccion_pc" name="correccion_pc">
    <input type="hidden" name="opcion" value="1">
    <ul class="list-group">
        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                <b>Sustento por Pedido Completo <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <textarea name="sustento-pc" class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar sustento"></textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                <b>Detalle <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <textarea name="detalle-pc" class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar detalle"></textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">Captura</h6>
                <div id="attachmentfiles" class="border border-dark rounded d-flex justify-content-center align-items-center mb-4 position-relative"
                     style="height: 400px">
                    <i class="fa fa-upload"></i>
                    <div class="result_picture position-absolute" style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
                        <img src="" class="h-100 img-fluid" alt="">
                    </div>
                </div>
                <div class="alert alert-warning">Puede copiar y pegar la imagen o hacer click en el recuadro para seleccionar un archivo</div>

            <input type="file" name="correcion_pc_captura" id="correcion_pc_captura"  class="d-none form-control" placeholder="">
        </li>
    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success">Enviar</button>
    </div>

</form>
