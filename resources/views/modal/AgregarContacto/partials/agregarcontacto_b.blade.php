<form id="form-agregarcontacto-b" name="form-agregarcontacto-b" class="agregarcontacto" >
    <input type="hidden" id="agregarcontacto_b" name="agregarcontacto_b">
    <input type="hidden" name="opcion" value="4">
    <ul class="list-group">
        <li class="list-group-item text-wrap">
          <h6 class="alert alert-danger text-center font-weight-bold">
            <b>Elegir cliente <span class="text-danger">(Obligatorio):</span></b>
          </h6>

          <select name="cbxClienteBloqueo" class="border form-control selectpicker border-secondary" id="cbxClienteBloqueo"
                  data-show-subtext="true" data-live-search="true"
                  data-live-search-placeholder="Seleccione cliente" title="Ningun cliente seleccionado">
          </select>
        </li>

        <li class="list-group-item text-wrap">
          <h6 class="alert alert-danger text-center font-weight-bold">
            Sustento
            <span class="text-danger">(Obligatorio)</span>
          </h6>
          <textarea name="txtSustentoBloqueo" id="txtSustentoBloqueo" class="form-control w-100"
                    rows="3" style=" color: red; font-weight: bold; background: white; "  placeholder="Colocar sustento"></textarea>
        </li>

        <li class="list-group-item text-wrap">
          <h6 class="alert alert-warndangering text-center font-weight-bold">
            Captura
            <span class="text-danger">(Obligatorio)</span>
          </h6>
          <div id="attachmentfiles" class="border border-dark rounded d-flex justify-content-center align-items-center mb-4 position-relative"
               style="height: 400px" >
            <i class="fa fa-upload"></i>
            <div class="result_picture position-absolute" style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
              <img src="" class="h-100 img-fluid" alt="">
            </div>
          </div>
          <div class="alert alert-danger">Puede copiar y pegar la imagen o hacer click en el recuadro para seleccionar un archivo</div>

          <input type="file" name="agregarcontacto_b_captura" id="agregarcontacto_b_captura"  class="d-none form-control" placeholder="">
        </li>

    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success">Enviar</button>
    </div>

</form>
