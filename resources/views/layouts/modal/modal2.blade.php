<div class="modal fade" id="modal-llamadas-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px!important;">
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h5 class="modal-title" id="exampleModalLabel">Listado Clientes</h5>

        <button class="float-right btn btn-danger" data-dismiss="modal"><i class="fas fa-times" aria-hidden="true" ></i></button>
      </div>
      <div class="modal-body">
        <div class="form-row ">
          <div class="form-group mb-0">

            <div class="btn-group" role="group" aria-label="Basic example">
              <button id="btnListNuevoCliente" type="button" class="btn rounded btn-info ml-2"> <i class="fa fa-user"></i> Nuevo Cliente
                  <i class="btnNewClienteCont ml-4" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </button>
              <button id="btnListCambioNombre" type="button" class="btn rounded btn-secondary  ml-2" ><i class="fa fa-user-lock"></i> Cambio Nombre
                  <i class="btnChangeNameCont ml-4" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </button>
              @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
              <button id="btnListBloqueo" type="button" class="btn rounded btn-danger   ml-2" ><i class="fa fa-lock"></i>  Bloqueo
                  <i class="btnBloqueoCont ml-4" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </button>
              @endif
              <button id="btnListCambioNumero" type="button" class="btn rounded btn-warning  ml-2" ><i class="fa fa-phone"></i>  Cambio Numero
                  <i class="btnChangeNroCont ml-4" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </button>
            </div>
          </div>
        </div>



        <div id="modal-ListadoClientes" class="modal-ListadoClientes">
          <div id="op-1-row" class="op-1-row"><!---->
            <div class="btn-group card-footer" role="group" aria-label="Basic example" id="radioBtnDiv">
              <div class="form-check d-flex gap-5">
                <label class="form-check-label" for="rbnGuardar"> Sin Guardar </label>
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnGuardar" checked value="1"/>
                <div class="ml-5">
                  <i class="btnSinGuardarCont" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
                </div>
              </div>
              <div class="form-check ml-5  d-flex gap-5">
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnGuardado" value="2"/>
                <label class="form-check-label" for="rbnGuardado"> Guardado </label>
                <div class="ml-5">
                  <i class="btnGuardadoCont" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
                </div>
              </div>
              @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
              <div class="form-check ml-5  d-flex gap-5">
                <input class="form-check-input" type="radio" name="rbnTipo" id="rbnConfirmado" value="3"/>
                <label class="form-check-label" for="rbnConfirmado"> Confirmado </label>
                <div class="ml-5">
                  <i class="btnConfirmadoCont" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
                </div>
              </div>
              @endif
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

        <div id="modal-CambioNombre" class="modal-CambioNombre">
          <div class="btn-group card-footer" role="group" aria-label="Basic example" id="radioBtnDiv2">
            <div class="form-check  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnGuardar2" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar2"> Sin Guardar </label>
              <div class="ml-5">
                <i class="btnNoSaveContCamNom" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </div>
            </div>
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnGuardado2" value="2"/>
              <label class="form-check-label" for="rbnGuardado2"> Guardado </label>
              <div class="ml-5">
                <i class="btnSavedContCamNom" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo2" id="rbnConfirmado2" value="3"/>
              <label class="form-check-label" for="rbnConfirmado2"> Confirmado </label>
              <div class="ml-5">
                <i class="btnConfirmContCamNom" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @endif
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaCambioNombre" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-secondary"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Nvo. Nombre</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
        <div id="modal-BLoqueoCliente" class="modal-BLoqueoCliente">
          <div class="btn-group card-footer" role="group" aria-label="Basic example" id="radioBtnDiv3">
            <div class="form-check  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnGuardar3" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar3"> Sin Bloqueo </label>
              <div class="ml-5">
                <i class="btnNoSaveContBloq" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </div>
            </div>
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnGuardado3" value="2"/>
              <label class="form-check-label" for="rbnGuardado3"> Bloquear </label>
              <div class="ml-5">
                <i class="btnSavedContBloq" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo3" id="rbnConfirmado3" value="3"/>
              <label class="form-check-label" for="rbnConfirmado3"> Bloqueado </label>
              <div class="ml-5">
                <i class="btnConfirmContBloq" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @endif
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaBloqueoClientes" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-danger"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Numero</th>
                <th scope="col">Imagen</th>
                <th scope="col">Sustento</th>
                <th scope="col">Accion</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        @endif
        <div id="modal-CambioNumero" class="modal-CambioNumero">
          <div class="btn-group card-footer" role="group" aria-label="Basic example" id="radioBtnDiv4">
            <div class="form-check  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnGuardar4" checked value="1"/>
              <label class="form-check-label" for="rbnGuardar4"> Sin Guardar </label>
              <div class="ml-5">
                <i class="btnNoSaveContCamNro" aria-hidden="true" ><i class="dot-notify noti-side">0</i></i>
              </div>
            </div>
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnGuardado4" value="2"/>
              <label class="form-check-label" for="rbnGuardado4"> Guardado </label>
              <div class="ml-5">
                <i class="btnSavedContCamNro" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @if(in_array(auth()->user()->rol,[\App\Models\User::ROL_ADMIN]))
            <div class="form-check  ml-5  d-flex gap-5">
              <input class="form-check-input" type="radio" name="rbnTipo4" id="rbnConfirmado4" value="3"/>
              <label class="form-check-label" for="rbnConfirmado4"> Confirmado </label>
              <div class="ml-5">
                <i class="btnConfirmContCamNro" aria-hidden="true" ><i class="dot-notify noti-side">-</i></i>
              </div>
            </div>
            @endif
          </div>
          <div id="op-1-row" class="op-1-row"><!---->
            <table id="tablaCambioNumero" class="table table-striped w-100" style="text-align: center">
              <thead class="bg-gradient-yellow"></h4>
              <tr>
                <th scope="col">Tipo</th>
                <th scope="col">Asesor</th>
                <th scope="col">Celular</th>
                <th scope="col">Nombre Cliente</th>
                <th scope="col">Nvo. Numero</th>
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
