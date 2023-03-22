<form id="form-agregaranulacion-pc" name="form-agregaranulacion-pc" enctype="multipart/form-data">
    <input type="hidden" id="agregaranulacion_pc" name="agregaranulacion_pc">
    <input type="hidden" name="opcion" value="1">
    <input type="hidden" id="txtIdPedidoCompleto" name="txtIdPedidoCompleto">
    <input type="hidden" id="tipoAnulacion" name="tipoAnulacion" value="C">

    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="codigoCodigoPc" id="codigoCodigoPc"
                   placeholder="Colocar codigo del pedido" class="bg-warning">
            <div class="input-icon">
                <i class="fa fa-barcode" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="asesorCodigoPc" id="asesorCodigoPc"
                   placeholder="Asesor" readonly style="background: #62CDFF;">
            <div class="input-icon">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="importeCodigoPc" id="importeCodigoPc"
                   placeholder="Colocar el importe del pedido" readonly style="background: #62CDFF;">
            <div class="input-icon">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="anulacionCodigoPc" id="anulacionCodigoPc"
                   placeholder="Colocar el importe a anular" readonly style="background: #62CDFF;">
            <div class="input-icon">
                <i class="fa fa-minus-square" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="rucCodigoPc" id="rucCodigoPc"
                   placeholder="Colocar el RUC del cliente" readonly style="background: #62CDFF;">
            <div class="input-icon">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="razonCodigoPc" id="razonCodigoPc"
                   placeholder="Colocar la razón social" readonly style="background: #62CDFF;">
            <div class="input-icon">
                <i class="fa fa-map-pin" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
            {!! Form::file('inputArchivoSubir[]', ['class' => 'form-control-file', 'id'=>'inputArchivoSubir','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col lg-12">
            {!! Form::label('txtMotivoPedComplet', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
            {!! Form::textarea('txtMotivoPedComplet', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Completa el sustento para la anulación ', 'required' => 'required', 'id'=>'txtMotivoPedComplet']) !!}
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
            <button type="submit" class="float-right btn btn-success btnEnviarPagoCompleto">Enviar</button>
        </div>
    </div>
</form>

