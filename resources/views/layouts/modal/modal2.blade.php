<div class="modal fade" id="modal-llamadas-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px!important;">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title" id="exampleModalLabel">Listado Clientes</h5>

        <button class="float-right btn btn-danger" data-dismiss="modal"><i class="fas fa-times" aria-hidden="true" ></i></button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-lg-12">

            <div class="btn-group" role="group" aria-label="Basic example">
              <button id="btnListNuevoCliente" type="button" class="btn rounded btn-info ml-2"> <i class="fa fa-user"></i> Nuevo Cliente</button>
              <button id="btnListCambioNombre" type="button" class="btn rounded btn-secondary  ml-2"><i class="fa fa-user-lock"></i> Cambio Nombre</button>
              <button id="btnListBloqueo" type="button" class="btn rounded btn-danger   ml-2"><i class="fa fa-lock"></i>  Bloqueo</button>
              <button id="btnListCambioNumero" type="button" class="btn rounded btn-warning  ml-2"><i class="fa fa-phone"></i>  Cambio Numero</button>
            </div>
          </div>
        </div>



        <div id="modal-ListadoClientes" class="modal-ListadoClientes">
          <div id="op-1-row" class="op-1-row"><!---->
            <div class="btn-group" role="group" aria-label="Basic example" id="radioBtnDiv">
              <div class="form-check ">
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnGuardar" checked value="1"/>
                <label class="form-check-label" for="rbnGuardar"> Sin Guardar </label>
              </div>
              <div class="form-check ml-4">
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnGuardado" value="2"/>
                <label class="form-check-label" for="rbnGuardado"> Guardado </label>
              </div>
              <div class="form-check ml-4">
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnConfirmado" value="3"/>
                <label class="form-check-label" for="rbnConfirmado"> Confirmado </label>
              </div>
            </div>
            <table id="tablaListadoLlamadas" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-info">
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Contacto</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <br>
        <div id="modal-CambioNombre" class="modal-CambioNombre">
          <div class="btn-group" role="group" aria-label="Basic example" id="radioBtnDiv2">
            <div class="form-check ">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnGuardar" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar"> Sin Guardar </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnGuardado" value="2"/>
              <label class="form-check-label" for="rbnGuardado"> Guardado </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnConfirmado" value="3"/>
              <label class="form-check-label" for="rbnConfirmado"> Confirmado </label>
            </div>
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaCambioNombre" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-secondary"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Contacto</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <div id="modal-BLoqueoCliente" class="modal-BLoqueoCliente">
          <div class="btn-group" role="group" aria-label="Basic example" id="radioBtnDiv3">
            <div class="form-check ">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnGuardar" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar"> Sin Bloqueo </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnGuardado" value="2"/>
              <label class="form-check-label" for="rbnGuardado"> Bloquer </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnConfirmado" value="3"/>
              <label class="form-check-label" for="rbnConfirmado"> Bloqueado </label>
            </div>
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaBloqueoClientes" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-danger"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Contacto</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <div id="modal-CambioNumero" class="modal-CambioNumero">
          <div class="btn-group" role="group" aria-label="Basic example" id="radioBtnDiv4">
            <div class="form-check ">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnGuardar" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar"> Sin Guardar </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnGuardado" value="2"/>
              <label class="form-check-label" for="rbnGuardado"> Guardado </label>
            </div>
            <div class="form-check ml-4">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnConfirmado" value="3"/>
              <label class="form-check-label" for="rbnConfirmado"> Confirmado </label>
            </div>
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaCambioNumero" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-yellow"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Contacto</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>
