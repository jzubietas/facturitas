  <!-- Modal -->
  <div class="modal fade" id="modal-add-ruc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Agregar RUC</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{ Form::Open(['route' => ['pedidos.agregarruc'], 'id'=>'formulario2']) }}
        <div class="modal-body">
          <p style="text-align: center">Ingrese el <strong>RUC</strong> a agregar</p>
        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <div class="row">   
                      @error('num_ruc')
                        <small class="text-danger" style="font-size: 16px">{{ $message }}</small>
                      @enderror                 
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        {!! Form::label('agregarruc', 'Numero de RUC  a registrar') !!}
                        <input type="number" name="agregarruc" id="agregarruc" step="1" min="0" max="99999999999" maxlength="11" oninput="maxLengthCheck(this)" class="form-control" placeholder="RUC..." required>
                      </div>
                      
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        {!! Form::label('cliente_id_ruc', 'cliente') !!}
                        <select name="cliente_id_ruc" class="border form-control selectpicker border-secondary" id="cliente_id_ruc" data-live-search="true" required>
                          <option value="">---- SELECCIONE CLIENTE ----</option>
                            @foreach($clientes_ruc as $cliente)
                              <option value="{{ $cliente->id }}">{{$cliente->celular}} - {{$cliente->nombre}}</option>   
                            @endforeach
                        </select>
                        {{-- <select name="cliente_id_ruc" id="cliente_id_ruc" class="border form-control selectpicker border-secondary" data-live-search="true">
                          <option value="">---- SELECCIONE ----</option>
                        </select> --}}
                      </div>
                      <div class="form-group col-lg-12">
                        {!! Form::label('pempresaruc', 'Nombre de empresa') !!}
                          <input type="text" name="pempresaruc" id="pempresaruc" class="form-control" placeholder="Nombre de empresa...">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info" id="atender">Confirmar</button>
        </div>
        {{ Form::Close() }}
      </div>
    </div>
  </div>
