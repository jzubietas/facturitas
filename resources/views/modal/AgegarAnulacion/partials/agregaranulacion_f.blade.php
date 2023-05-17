<form id="form-agregaranulacion-f-2" name="form-agregaranulacion-f-2" class="agregaranulacion" enctype="multipart/form-data">
    <input type="hidden" id="agregaranulacion_f" name="agregaranulacion_f">
    <input type="hidden" id="txtIdPedidoFactura" name="txtIdPedidoFactura">
    <input type="hidden" id="tipoAnulacion2" name="tipoAnulacion2" value="F">
    <input type="hidden" name="opcion" value="2">
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="codigoCodigoF" id="codigoCodigoF"
                   placeholder="Colocar codigo del pedido" class="bg-warning">
            <div class="input-icon">
                <i class="fa fa-barcode" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="asesorCodigoF" id="asesorCodigoF" placeholder="Asesor" readonly style="background: #A4907C;">
            <div class="input-icon">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="importeCodigoF" id="importeCodigoF"
                   placeholder="Colocar el importe del pedido" readonly style="background: #A4907C;">
            <div class="input-icon">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            {{--<input type="text" name="anularCodigoF" id="anularCodigoF" step="0.01" min="0"
                   class="form-control number ob" data-type="text" data-msj="Ingrese una cantidad"
                   placeholder="Cantidad..." style="background: #A4907C;">--}}

            <input name="anularCodigoF" id="anularCodigoF"  step="0.01" min="0" class="form-control bg-warning number ob" data-type="text"
                   placeholder="Colocar el importe a anular"  style="background: #A4907C;">
            <div class="input-icon">
                <i class="fa fa-minus-square" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="rucCodigoF" id="rucCodigoF"
                   placeholder="Colocar el RUC del cliente" readonly style="background: #A4907C;">
            <div class="input-icon">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="razonCodigoF" id="razonCodigoF"
                   placeholder="Colocar la razón social" readonly style="background: #A4907C;">
            <div class="input-icon">
                <i class="fa fa-map-pin" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
            {!! Form::file('inputArchivoSubirf[]', ['class' => 'form-control-file', 'id'=>'inputArchivoSubirf','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col lg-12">
            {!! Form::label('txtMotivoFactura', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
            {!! Form::textarea('txtMotivoFactura', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Completa el sustento para la anulación ', 'id'=>'txtMotivoFactura']) !!}
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
            <button type="submit" class="float-right btn btn-success btnEnviarFactura">Enviar</button>
        </div>
    </div>


</form>
