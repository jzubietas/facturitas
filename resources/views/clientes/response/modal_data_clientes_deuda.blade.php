<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="row">
                    @php
                        $oldDisk = setting('administracion.attachments.1_5.disk');
   $oldPath = setting('administracion.attachments.1_5.path');

   $oldDisk2 = setting('administracion.attachments.6_12.disk');
   $oldPath2 = setting('administracion.attachments.6_12.path');
                    @endphp
                    @if(intval($cliente->user->identificador)<=5)
                        @if($oldDisk && $oldPath)
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>Imagen para asesores <br>de <b>1 al 5</b></h3>
                                    </div>
                                    <div class="card-body" id="imagecontent1">
                                        <img src="{{Storage::disk($oldDisk)->url($oldPath)}}" class="w-100"/>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if(intval($cliente->user->identificador)>5)
                        @if($oldDisk2 && $oldDisk2)
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>Imagen para asesores <br>de <b>6 al 12</b></h3>
                                    </div>
                                    <div class="card-body" id="imagecontent2">
                                        <img src="{{Storage::disk($oldDisk2)->url($oldPath2)}}" class="w-100"/>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-8">
                <button type="button" onclick="copyElement('#copiar_cotizacion')" class="btn btn-dark"><i class="fa fa-copy"></i> Copiar</button>
<textarea class="form-control mb-4" rows="23" placeholder="Cotizacion" name="copiar_cotizacion" cols="50"
          id="copiar_cotizacion">
{{$messaje}}

@foreach($pedidos as $cotizacion)
@if($cotizacion->adelanto==0||(($cotizacion->total-$cotizacion->adelanto)>0))
{{$cotizacion->nombre_empresa}} - {{$cotizacion->created_at->format('d/m')}}

*{{money_f($cotizacion->cantidad)}} * {{$cotizacion->porcentaje}}% = {{money_f($cotizacion->ft)}}*
*ENVIO = {{money_f($cotizacion->courier)}}*
@if($cotizacion->adelanto>0)
*ADELANTO = {{money_f($cotizacion->adelanto)}}*
*TOTAL = {{money_f($cotizacion->total-$cotizacion->adelanto)}}*
@else
*TOTAL = {{money_f($cotizacion->total)}}*
@endif
-------------------------------------------------------------------------------
@endif
@endforeach
*ES IMPORTANTE PAGAR EL ENVIO*

*TOTAL A PAGAR={{money_f($totalDeuda)}}*

_______________________________________________________________________________

CUENTAS BANCARIAS

@foreach($cuentas_bancarias as $titula=>$cuentas)
{{$titula}}
@foreach($cuentas as $cuenta)
    {{$cuenta->numero}} - {{$cuenta->entidad_bancaria}} ({{$cuenta->tipo}})
@endforeach
@endforeach
</textarea>

            </div>
        </div>

    </div>
</div>
