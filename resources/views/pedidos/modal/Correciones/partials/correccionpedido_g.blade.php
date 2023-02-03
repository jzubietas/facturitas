<form id="form-correccionpedido-g" name="form-correccionpedido-g" >
    <input type="hidden" id="correccion_pc" name="correccion_g">
    <ul class="list-group">
        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                Sustento por Pedido Completo
                <span class="text-danger">(*)</span>
            </h6>
            <textarea readonly class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; " required>response.sustento</textarea>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">
                Adjuntos de las guias <span class="text-danger">*</span>
            </h6>
            <input type="file" name="correcion_g_adjuntos" id="correcion_f_adjuntos"  class="form-control" placeholder="">
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-warning text-center font-weight-bold">Detalle o cambio que se va a realizar</h6>
            <textarea readonly class="form-control w-100"
                      rows="3" style=" color: red; font-weight: bold; background: white; " required>response.detalle</textarea>
        </li>


    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success">Enviar</button>
    </div>

</form>
