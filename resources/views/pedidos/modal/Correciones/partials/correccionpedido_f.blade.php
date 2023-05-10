<form id="form-correccionpedido-f" name="form-correccionpedido-f" class="correccion">
    <input type="hidden" id="correccion_f" name="correccion_f">
    <input type="hidden" name="opcion" value="2">
    <ul class="list-group">
        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                <b>Sustento por Pedido Completo <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <textarea name="sustento-f" class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar sustento"></textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                <b>Facturas que se van a corregir <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <input type="file" name="correcion_f_facturas[]" id="correcion_f_facturas"  class="form-control" placeholder="" multiple>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                <b>Detalle <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <textarea name="detalle-f" class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar detalle"></textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">Adjuntos referenciales</h6>
            <input type="file" name="correcion_f_adjuntos[]" id="correcion_f_adjuntos"  class="form-control" placeholder="" multiple>
        </li>
    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success">Enviar</button>
    </div>

</form>
