<form id="formdireccion" name="formdireccion">
    <input type="hidden" name="pedido_id" value="{{$pedido->id}}">
    <input type="hidden" name="grupo_id" value="{{$dirgrupo->id}}">
    <div class="contenedor-formulario"><!--formulario-->
        @if($dirgrupo->direccionEnvio!=null)
            <input type="hidden" name="direccion_envio_id" value="{{$dirgrupo->direccionEnvio->id}}">
            <div class="lima p-4">
                <div class="row">
                    <div class="col-12">
                        <h1>LIMA</h1>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-user text-red" aria-hidden="true"></i>
                        <input type="hidden" name="direccion_id" id="direccion_id">
                        {!! Form::label('nombre', 'Nombre del contacto quien recibe') !!}
                        {!! Form::text('nombre', $dirgrupo->nombre_cliente, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-phone text-red" aria-hidden="true"></i>

                        {!! Form::label('celular', 'Telefono del contacto quien recibe') !!}
                        <span class="badge badge-pill badge-secondary">9 digitos</span>
                        {!! Form::number('celular', $dirgrupo->celular_cliente, ['class' => 'form-control', 'id' => 'celular', 'min' =>'0', 'max' => '999999999', 'maxlength' => '9', 'oninput' => 'maxLengthCheck(this)','placeholder' => '9 digitos']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-street-view text-red" aria-hidden="true"></i>

                        {!! Form::label('direccion', 'Direccion') !!}
                        {!! Form::text('direccion', $dirgrupo->direccion?:$dirgrupo->direccionEnvio->direccion, ['class' => 'form-control', 'placeholder' => 'Direccion', 'required' => 'required']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <i class="fa fa-commenting-o text-red" aria-hidden="true"></i>

                        {!! Form::label('referencia', 'Referencia') !!}
                        {!! Form::text('referencia', $dirgrupo->referencia?:$dirgrupo->direccionEnvio->referencia, ['class' => 'form-control', 'placeholder' => 'Referencia', 'required' => 'required']) !!}
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
                                                {{($dirgrupo->distrito?:$dirgrupo->direccionEnvio->distrito)==$distrito->distrito?'selected':''}}
                                                value="{{$distrito->distrito}}">{{($distrito->distrito) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>


                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('observacion', 'Observacion') !!}
                        {!! Form::text('observacion', $dirgrupo->observacion?:$dirgrupo->direccionEnvio->observacion, ['class' => 'form-control', 'placeholder' => 'Observacion', 'required' => 'required']) !!}
                    </div>

                </div>
            </div>
        @elseif($dirgrupo->gastoEnvio!=null)
            <div class="provincia p-4">
                <div class="row">
                    <div class="col-12">
                        <h1>PROVINCIA</h1>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-none">
                        {!! Form::label('departamento', 'departamento') !!}
                        {!! Form::select('departamento', $departamento  , null, ['class' => 'form-control selectpicker border border-secondary', 'data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-none">
                        {!! Form::label('oficina', 'Oficina') !!}
                        {!! Form::text('oficina', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required']) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('tracking', 'Tracking') !!}
                        {!! Form::text('tracking', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required','maxlength' => 12]) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('numregistro', 'Numero de Registro') !!}
                        {!! Form::text('numregistro', null, ['class' => 'form-control', 'placeholder' => 'Nombre', 'required' => 'required','maxlength' => 12]) !!}
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! Form::label('importe', 'Importe') !!}
                        <input
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            type="text" maxlength="5" id="importe" name="importe"
                            placeholder="Importe" class="form-control number ob" step="0.01" min="0"
                            data-type="text" data-msj="Ingrese una cantidad">
                    </div>

                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <div class="col-10">
                                {!! Form::label('rotulo', 'Rotulo') !!}
                                @csrf
                                {!! Form::file('rotulo', ['class' => 'form-control-file', 'accept' =>'pdf/*']) !!}
                            </div>
                            <div
                                class="col-2 d-none justify-content-center align-items-center drop-rotulo">
                                <button type="button" id="droprotulo" class="btn btn-danger btn-md">
                                    <i class="fa fa-trash"></i></button>
                            </div>
                        </div>

                        {{----}}

                        <br>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="saveHistoricoProvincia"><!--checked-->
                            <label class="form-check-label font-weight-bold" for="saveHistoricoProvincia">
                                Grabar registro en historico
                            </label>
                        </div>

                        <div class="form-check form-switch" style="display: none">
                            <input class="form-check-input" type="checkbox"
                                   id="saveHistoricoProvinciaEditar" disabled>
                            <label class="form-check-label font-weight-bold"
                                   for="saveHistoricoProvinciaEditar">
                                Actualizar registro en historico
                            </label>
                        </div>

                    </div>

                    {{--<button type="button" id="saveHistoricoProvincia" class="btn btn-danger btn-md"><i class="fa"></i>GRABA HISTORICO</button>--}}
                </div>
            </div>
        @endif
    </div>
</form>
