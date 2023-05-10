<form id="formdireccion" name="formdireccion">
    <input type="hidden" name="pedido_id" value="{{$pedido->id}}">
    <div class="contenedor-formulario">
        @if($dirgrupo!=null)
            @if(in_array($dirgrupo->condicion_envio_code,[\App\Models\Pedido::RECEPCION_MOTORIZADO_INT,\App\Models\Pedido::MOTORIZADO_INT]))
                <div class="alert alert-warning">
                    El pedido ya esta en ruta, debe agregar un sustento
                </div>
            @else
                <div class="alert alert-warning">
                    El pedido se encuentra en
                    <b class="badge badge-success" style="background: {{\App\Models\Pedido::getColorByCondicionEnvio($dirgrupo->condicion_envio)}}!important;">
                        {{$dirgrupo->condicion_envio}}
                    </b>,<br> debe agregar un sustento
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="cambio_direccion_sustento">Ingresar sustento para el cambio de dirección</label>
                        <textarea id="cambio_direccion_sustento" class="form-control"
                                  name="cambio_direccion_sustento">{{$pedido->cambio_direccion_sustento??''}}</textarea>
                    </div>
                </div>
            </div>
        @endif
        @if(Str::upper($pedido->destino?:'')!='PROVINCIA')
            <div class="lima p-4">
                <div class="row">
                    <div class="col-12">
                        <h1>LIMA</h1>
                    </div>

                    <div id="cnt-distritos" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('distrito', 'Distrito') !!}<br>
                        <select name="distrito" id="distrito" class="distrito form-control"
                                data-show-subtext="true" data-live-search="true"
                                data-live-search-placeholder="Seleccione distrito">
                            @foreach(collect($distritos)->groupBy('zona') as $zona=>$items)
                                <optgroup label="{{$zona}}">
                                    @foreach($items as $distrito)
                                        <option data-subtext="{{$distrito->zona}}"
                                                {{$pedido->env_distrito==$distrito->distrito?'selected':''}}
                                                value="{{$distrito->distrito}}">{{($distrito->distrito) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-user text-red" aria-hidden="true"></i>
                        <input type="hidden" name="direccion_id" id="direccion_id">
                        {!! Form::label('nombre', 'Nombre del contacto quien recibe') !!}
                        {!! Form::text('nombre', $pedido->env_nombre_cliente_recibe, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-phone text-red" aria-hidden="true"></i>
                        {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                        <span class="badge badge-pill badge-secondary">9 digitos</span>
                        {!! Form::number('celular', $pedido->env_celular_cliente_recibe, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '9', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-street-view text-red" aria-hidden="true"></i>
                        {!! Form::label('direccion', 'Direccion') !!}
                        {!! Form::text('direccion', $pedido->env_direccion, ['class' => 'form-control', 'placeholder' => 'Direccion', 'required' => 'required']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>
                        {!! Form::label('referencia', 'Referencia') !!}
                        {!! Form::text('referencia', $pedido->env_referencia, ['class' => 'form-control', 'placeholder' => 'Referencia', 'required' => 'required']) !!}
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('observacion', 'Observacion') !!}
                        {!! Form::text('observacion', $pedido->env_observacion, ['class' => 'form-control', 'placeholder' => 'Observacion', 'required' => 'required']) !!}
                    </div>

                </div>
            </div>
        @else
            <div class="provincia p-4">
                <div class="row">
                    <div class="col-12">
                        <h1>PROVINCIA</h1>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('numregistro', 'Numero de Registro') !!}
                        {!! Form::text('numregistro', $pedido->env_numregistro, ['class' => 'form-control', 'placeholder' => 'Numero de Registro', 'required' => 'required','maxlength' => 12]) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('tracking', 'Tracking') !!}
                        {!! Form::text('tracking', $pedido->env_tracking, ['class' => 'form-control', 'placeholder' => 'Tracking', 'required' => 'required','maxlength' => 12]) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('importe', 'Importe') !!}
                        <input
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            type="text" maxlength="5" id="importe" name="importe"
                            placeholder="Importe" class="form-control number ob" step="0.01" min="0"
                            value="{{$pedido->env_importe}}"
                            data-type="text" data-msj="Ingrese una cantidad">
                    </div>

                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <div class="col-10">
                                {!! Form::label('rotulo', 'Rotulo') !!}
                                @csrf
                                {!! Form::file('rotulo', ['class' => 'form-control-file', 'accept' =>'pdf/*']) !!}
                            </div>
                            <div class="col-2 d-none justify-content-center align-items-center drop-rotulo">
                                <button type="button" id="droprotulo" class="btn btn-danger btn-md">
                                    <i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</form>
