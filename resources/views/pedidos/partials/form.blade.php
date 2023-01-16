<div class="card">
  <div class="card-body">
    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor') !!}
            {{--@if (Auth::user()->rol == 'Asesor' || Auth::user()->rol == 'Super asesor')--}}
              {{--<input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
              <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>--}}



              <select name="user_id" class="border form-control  border-secondary selectpicker" id="user_id" data-live-search="true" >
                <option value="0" class="ob" data-type="select" data-msj="Seleccione un Asesor">---- SELECCIONE ASESOR ----</option>
              </select>

          </div>


          <div class="form-group col-lg-6">
            {!! Form::label('cliente_id', 'Cliente*') !!} &nbsp; &nbsp; &nbsp;

            <a href="" data-target="#modal-historial" data-toggle="modal"><button class="btn btn-danger btn-sm">Deudores</button></a>

            @if($mirol =='Administrador')

            @endif
            <div class="pr-2 btn border-0 rounded text-right">
              <small class="rounded mb-2 bg-danger text-white" style="font-size: 16px">Desactivado</small>
              <small class="rounded mb-2 bg-info text-white" style="font-size: 16px">Permitido</small>
              <small class="rounded mb-2 bg-dark text-white" style="font-size: 16px">Normal</small>
            </div>


              <select name="cliente_id" class="border form-control  border-secondary selectpicker" id="cliente_id" data-live-search="true" >{{-- selectpicker lang="es" --}}
                <option>---- SELECCIONE CLIENTE ----</option>
              </select>

          </div>
        </div>
      </div>
    </div>

    <br>

    <div class="border rounded card-body border-secondary" id="vertabla">
      <div class="card-body">

      <div class="form-row">
          <div class="form-group col-lg-2">
          {!! Form::label('pruc', 'RUC *') !!} &nbsp; &nbsp; &nbsp;

            <a href="" data-target="#modal-add-ruc" id= "btn_agregar_ruc" data-toggle="modal" class="btn btn-info btn-sm">AGREGAR RUC Y R.S.</a>

            <select name="pruc" class="border form-control border-secondary selectpicker" id="pruc" data-live-search="true" style="height: 100% !important;">
              <option value="">---- SELECCIONE ----</option>
            </select>
          </div>

          @error('num_ruc')
            <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
          @enderror

          <div class="form-group col-lg-3">
           {!! Form::label('pempresa', 'Nombre de empresa') !!}
             <input type="text" name="pempresa" id="pempresa" class="form-control" placeholder="Nombre de empresa..." disabled>
         </div>

          <div class="form-group col-lg-3">
              {!! Form::label('ptipo_banca', 'Tipo de comprobante y banca') !!}
              <select name="ptipo_banca" id="ptipo_banca" class="border form-control border-secondary">
                  <option value="">---- SELECCIONE ----</option>
              </select>
          </div>

          <div class="form-group col-lg-3 d-flex justify-content-center align-items-center">
            <a href="" data-target="#modal-historial-2" data-toggle="modal">
              <button class="btn btn-danger btn-lg ">Historial</button>
            </a><!--align-items-stretch-->
          </div>



      </div>

        <div class="form-row">
        <div class="form-group col-lg-2">

        </div>

              <input type="hidden" name="pcodigo" id="pcodigo" value="{{ Auth::user()->identificador }}-{{ $fecha }}-{{ $numped}}" class="form-control" readonly>


        </div>
        <div class="form-row">

          <div class="form-group col-lg-2">
            {!! Form::label('pcantidad', 'Cantidad') !!}
              <input type="text" name="pcantidad" id="pcantidad" step="0.01" min="0" class="form-control number ob" data-type="text" data-msj="Ingrese una cantidad" placeholder="Cantidad...">
          </div>

            <div class="form-group col-lg-2">

                {!! Form::label('pmes', 'Mes') !!}
                {!! Form::select('pmes', $meses , $mes_selected, ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>

            <div class="form-group col-lg-2">

                {!! Form::label('panio', 'Año') !!}
                {!! Form::select('panio', $anios , $anno_selected, ['class' => 'form-control border selectpicker border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
            </div>


          <div class="form-group col-lg-1">
            {!! Form::label('pporcentaje', 'Porcentaje(%)') !!}
              <input type="number" name="pporcentaje" id="pporcentaje" step="0.1" min="0" class="form-control" placeholder="Porcentaje..." disabled>
          </div>
          <div class="form-group col-lg-1">
            {!! Form::label('pcourier', 'Courier(S/)') !!}
              {{-- <input type="number" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control" placeholder="Courier..."> --}}
              <input type="text" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control number" value="0" placeholder="Courier...">
          </div>
          <div class="form-group col-lg-3 d-flex justify-content-center align-items-center">
              <div class="btn-group-vertical" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-info btn-lg" id="prev">
                      <i class="fa fa-link"></i>
                      Previsualizar PDF
                  </button>
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#previsualizar_modal_pedido">
                      <i class="fa fa-copy"></i>
                      Previsualizar Modal
                  </button>
              </div>

          </div>
          <div class="form-group col-lg-2">

          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-lg-5">
            {!! Form::label('pdescripcion', 'Descripción') !!}
              <input type="text" name="pdescripcion OB" type="text" data-msj="ingrese 9 digitos" id="pdescripcion" class="form-control" placeholder="Descripción...">
          </div>
          <div class="form-group col-lg-4">
            {!! Form::label('pnota', 'Nota') !!}
              <input type="text" name="pnota" id="pnota" class="form-control" placeholder="Nota...">
          </div>

          <div class="form-group col-lg-3 d-flex justify-content-center align-items-center">

            <button type="button" id="bt_add" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle"></i> Agregar</button>

          </div>

        </div>
        <div class="row">

          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="text-align: center">
            <div class="table-responsive">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color: #A9D0F5">
                    <th>Opciones</th>
                    {{--<th>Código</th>--}}
                    <th>Empresa</th>
                    <th>Mes</th>
                    <th>Año</th>
                    <th>RUC</th>
                    <th>Cantidad</th>
                    <th>Tipo de comprobante<br>y banca</th>
                    <th>Porcentaje</th>
                    <th>Courier</th>
                    <th>Descripción</th>
                    <th>Nota</th>
                    <th>Adjunto</th>
                    <th>FT</th>
                  </thead>
                  <tfoot>
                    <th style="text-align: center">TOTAL</th>
                    {{--<th></th>--}}
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center"><h4 id="total">S/. 0.00</h4></th>
                  </tfoot>
                  <tbody>

                  </tbody>
                </table>
                <br>
                <textarea class="form-control d-none" rows="6" placeholder="Descripcion Otros" name="pedido_copiar_2" cols="50" id="pedido_copiar_2"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
