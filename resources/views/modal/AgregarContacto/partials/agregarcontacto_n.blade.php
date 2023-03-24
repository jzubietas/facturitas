<form id="form-agregarcontacto-n" name="form-agregarcontacto-n" class="agregarcontacto">
    <input type="hidden" id="agregarcontacto_n" name="agregarcontacto_n">
    <input type="hidden" name="opcion" value="1">
    <ul class="list-group">

        <li class="list-group-item text-wrap">
          <h6 class="alert alert-info text-center font-weight-bold">
            <b>Elegir cliente <span class="text-danger">(Obligatorio):</span></b>
          </h6>

          <select name="cbxClienteAgregaNuevo" class="border form-control selectpicker border-secondary bg-dark" id="cbxClienteAgregaNuevo"
                  data-show-subtext="true" data-live-search="true"
                  data-live-search-placeholder="Seleccione cliente" title="Ningun cliente seleccionado">
          </select>
        </li>

        <li class="list-group-item text-wrap">
            <h6 class="alert alert-info text-center font-weight-bold">
                <b>Numero de contacto <span class="text-danger">(Obligatorio):</span></b>
            </h6>
            <input name="txtNombreContactoNuevo" id="txtNombreContactoNuevo" class="form-control w-100"
                     style=" color: red; font-weight: bold; background: white; "  placeholder="Ingrese el Numero de contacto">
        </li>

    </ul>
    <hr class="mt-2 mb-3"/>
    <div class="form-group col-lg-12">
        <button type="submit" class="float-right btn btn-success btnEnviarAgregaContacto">Enviar</button>
    </div>

</form>
