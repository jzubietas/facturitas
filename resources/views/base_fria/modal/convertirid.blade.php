  <!-- Modal -->
  <div class="modal fade" id="modal-convertir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1000px!important;">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalLabel">Convertir base fría a CLIENTE 2</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <form id="formconvertir" name="formconvertir">
        <input type="hidden" id="hiddenId" name="hiddenID" class="form-control">
        <div class="modal-body">
          <p>Confirme si desea <strong>PASAR DE BASE FRIA A CLIENTE</strong> a: <br> <strong class="textcode">BF00 id - celular</strong></p>

        </div>
        <div style="margin: 10px">
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h5 style="text-align: center"><b>Datos del cliente</b></h5>
                      </div>
                      <div class="form-group col-lg-6">
                        {!! Form::label('nombre', 'Nombre*') !!}
                        {!! Form::text('nombre', 'nombre', ['class' => 'form-control', 'id' => 'nombre', 'required']) !!}
                        @error('nombre')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                     {{--
                      <div class="form-group col-lg-3">
                        {!! Form::label('dni', 'DNI') !!}
                        {!! Form::number('dni', null, ['class' => 'form-control', 'id' => 'dni', 'min' =>'0', 'max' => '99999999', 'maxlength' => '8', 'oninput' => 'maxLengthCheck(this)', 'required']) !!}
                        @error('dni')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                     --}}
                      <div class="form-group col-lg-3">
                        {!! Form::label('celular', 'Celular*') !!}
                        {!! Form::number('celular', 'celular', ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)', 'required']) !!}
                        @error('celular')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      {{--
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h5 style="text-align: center"><br><b>Direccion de envío de documentos</b></h5>
                      </div>
                      <div class="form-group col-lg-6">
                        {!! Form::label('provincia', 'Provincia*') !!}
                        {!! Form::text('provincia', null, ['class' => 'form-control', 'id' => 'provincia', 'required']) !!}
                        @error('provincia')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group col-lg-6">
                        {!! Form::label('distrito', 'Distrito*') !!}
                        {!! Form::text('distrito', null, ['class' => 'form-control', 'id' => 'distrito', 'required']) !!}
                        @error('distrito')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group col-lg-6">
                        {!! Form::label('direccion', 'Dirección*') !!}
                        {!! Form::text('direccion', null, ['class' => 'form-control', 'id' => 'direccion']) !!}
                        @error('direccion')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>

                      <div class="form-group col-lg-6">
                        {!! Form::label('referencia', 'Referencia*') !!}
                        {!! Form::text('referencia', null, ['class' => 'form-control', 'id' => 'referencia', 'required']) !!}
                        @error('referencia')
                          <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                      <div class="form-group col-lg-6">
                        <p style="color: red">*CAMPO OBLIGATORIO</p>
                      </div>--}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="border rounded card-body border-secondary">
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h5 style="text-align: center"><b>Porcentajes</b></h5>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group">
                                <label for="porcentaje_fsb">FISICO - sin banca</label>
                                <input type="number" step="0.1" name="porcentaje_fsb" id="porcentaje_fsb" min="0" max="8" value="0" class="form-control" required>
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group">
                                <label for="porcentaje_esb">ELECTRONICA - sin banca</label>
                                <input type="number" step="0.1" name="porcentaje_esb" id="porcentaje_esb" min="0" max="8" value="0" class="form-control" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br><br>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group">
                                <label for="porcentaje_esb">FISICO - banca</label>
                                <input type="number" step="0.1" name="porcentaje_fcb" id="porcentaje_fcb" min="0" max="8" value="0" class="form-control" required>
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="input-group">
                                <label for="porcentaje_esb">ELECTRONICA - banca</label>
                                <input type="number" step="0.1" name="porcentaje_ecb" id="porcentaje_ecb" min="0" max="8" value="0" class="form-control" required>
                            </div>
                          </div>
                        </div>
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
          <button type="submit" id="submit" class="btn btn-info">Confirmar</button>
        </div>

        </form>

      </div>
    </div>
  </div>
