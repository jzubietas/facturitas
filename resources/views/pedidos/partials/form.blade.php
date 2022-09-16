<div class="card">
  <div class="card-body">
    <div class="border rounded card-body border-secondary">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-6">
            {!! Form::label('user_id', 'Asesor') !!}
            @if (Auth::user()->rol == 'Asesor' || Auth::user()->rol == 'Super asesor')
              <input type="hidden" name="user_id" requerid value="{{ Auth::user()->id }}" class="form-control">
              <input type="text" name="user_name" value="{{ Auth::user()->name }}" class="form-control" disabled>
            @else
              {!! Form::select('user_id', $users, null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ASESOR ----']) !!}
            @endif
          </div>
          <div class="form-group col-lg-6">
            {!! Form::label('cliente_id', 'Cliente*') !!} &nbsp; &nbsp; &nbsp; 
            @if(0 < count((array)$deudores))
            <a href="" data-target="#modal-historial" data-toggle="modal"><button class="btn btn-danger btn-sm">Deudores</button></a>
            @endif
              <select name="cliente_id" class="border form-control selectpicker border-secondary" id="cliente_id" data-live-search="true">
                <option value="">---- SELECCIONE CLIENTE ----</option>
                  @foreach($clientes1 as $cliente)
                    @if($cliente->cantidad > 0 && $dateM == $cliente->mes && $dateY == $cliente->anio)
                      <option style="background: #4ac4e2" value="{{ $cliente->id }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                    @elseif($cliente->cantidad > 0 && (($dateM*1)-($cliente->mes*1)) > 0 && (($dateY*1)-($cliente->anio*1)) == 0)
                      @if(Auth::user()->rol == 'Super asesor')
                        <option style="background: #e73d3d" value="{{ $cliente->id }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                      @endif
                    @else
                      <option value="{{ $cliente->id }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                    @endif   
                  @endforeach
                  @foreach($clientes2 as $cliente)
                    <option value="{{ $cliente->id }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                  @endforeach
                  @foreach($clientes3 as $cliente)
                    <option value="{{ $cliente->id }}">{{$cliente->nombre}} - {{$cliente->celular}}</option>
                  @endforeach
              </select>              
              @include('pedidos.modal.historial')
          </div>
        </div>  
      </div>
    </div>

    <br>

    <div class="border rounded card-body border-secondary" id="vertabla">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-lg-2">
            {!! Form::label('pcodigo', 'Codigo') !!}
              <input type="text" name="pcodigo" id="pcodigo" value="{{ Auth::user()->identificador }}-{{ $fecha }}-{{ $numped}}" class="form-control" disabled>
          </div>
          <div class="form-group col-lg-4">
            {!! Form::label('pempresa', 'Nombre de empresa') !!}
              <input type="text" name="pempresa" id="pempresa" class="form-control" placeholder="Nombre de empresa...">
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('pmes', 'Mes') !!}
            {!! Form::select('pmes', $meses , '0', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('panio', 'Año') !!}
            {{-- {!! Form::number('panio', 2022, ['class' => 'form-control', 'id' => 'panio', 'min' =>'0']) !!} --}}
            {!! Form::select('panio', $anios , '2022', ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
          </div>
          <div class="form-group col-lg-2">            
            {!! Form::label('pruc', 'RUC') !!} <a href="" data-target="#modal-add-ruc" data-toggle="modal">(Agregar +)</a><br>
            @error('num_ruc')
              <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
            @enderror
            {{-- <input type="number" name="pruc" id="pruc" step="1" min="0" max="99999999999" maxlength="11" oninput="maxLengthCheck(this)" class="form-control" placeholder="RUC..."> --}}
            {!! Form::select('pruc', $rucs , null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
          </div>
          <div class="form-group col-lg-2">
            {!! Form::label('pcantidad', 'Cantidad') !!}<!-- , 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)'-->
              {{-- <input type="number" name="pcantidad" id="pcantidad" step="0.01" min="0" class="form-control" placeholder="Cantidad..."> --}}
              <input type="text" name="pcantidad" id="pcantidad" step="0.01" min="0" class="form-control number" placeholder="Cantidad...">
          </div>       
          <div class="form-group col-lg-3">
            {!! Form::label('ptipo_banca', 'Tipo de comprobante y banca') !!}
            <select name="ptipo_banca" id="ptipo_banca" class="border form-control border-secondary">
              <option value="">---- SELECCIONE ----</option>
            </select>
          </div>       
          <div class="form-group col-lg-2">
            {!! Form::label('pporcentaje', 'Porcentaje(%)') !!}
              <input type="number" name="pporcentaje" id="pporcentaje" step="0.1" min="0" class="form-control" placeholder="Porcentaje..." disabled>
          </div>
          <div class="form-group col-lg-3">
            {!! Form::label('pcourier', 'Courier(S/)') !!}
              {{-- <input type="number" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control" placeholder="Courier..."> --}}
              <input type="text" name="pcourier" id="pcourier" step="0.01" min="0" class="form-control number" placeholder="Courier...">
          </div>
          <div class="form-group col-lg-5">
            {!! Form::label('pdescripcion', 'Descripción') !!}
              <input type="text" name="pdescripcion" id="pdescripcion" class="form-control" placeholder="Descripción...">
          </div>
          <div class="form-group col-lg-5">
            {!! Form::label('pnota', 'Nota') !!}
              <input type="text" name="pnota" id="pnota" class="form-control" placeholder="Nota...">
          </div>
          {{-- <div class="form-group col-lg-3">
            {!! Form::label('padjunto', 'Adjunto') !!}
            {!! Form::file('pimagen', ['class' => 'form-control-file', 'accept' => '/zip']) !!}
          </div> --}}
          <div class="form-group col-lg-2" style="margin-top: 30px;text-align: center;">
            <div class="form-group">
              <button type="button" id="bt_add" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Agregar</button>
            </div>
          </div>

          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" style="text-align: center">
            <div class="table-responsive">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color: #A9D0F5">
                    <th>Opciones</th>
                    <th>Código</th>
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
                    <th></th>
                    <th style="text-align: center"><h4 id="total">S/. 0.00</h4></th>
                  </tfoot>
                  <tbody>
                    
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
</div>