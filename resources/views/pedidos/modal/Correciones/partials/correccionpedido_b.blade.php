<form id="form-correccionpedido-b" name="form-correccionpedido-b" class="correccion">
    <input type="hidden" id="correccion_b" name="correccion_b">
    <input type="hidden" name="opcion" value="4">
    <ul class="list-group">
        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                Sustento
                <span class="text-danger">(Obligatorio)</span>
            </h6>
            <textarea name="sustento-b" class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar sustento"></textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                Bancarizaciones adjuntas <span class="text-danger">(Obligatorio)</span>
            </h6>
            <input type="file" name="correcion_b_adjuntos[]" id="correcion_b_adjuntos"  class="form-control" placeholder="" multiple>
        </li>


    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success">Enviar</button>
    </div>

</form>
