<form id="form-agregaranulacion-pc" name="form-agregaranulacion-pc" class="agregaranulacion">
    <input type="hidden" id="agregaranulacion_pc" name="agregaranulacion_pc">
    <input type="hidden" name="opcion" value="1">


    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="codigoCodigoPc" id="codigoCodigoPc"
                   placeholder="Colocar codigo del pedido">
            <div class="input-icon">
                <i class="fa fa-barcode" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="asesorCodigoPc" id="asesorCodigoPc"
                   placeholder="Asesor" disabled>
            <div class="input-icon">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="importeCodigoPc" id="importeCodigoPc"
                   placeholder="Colocar el importe del pedido" disabled>
            <div class="input-icon">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="anulacionCodigoPc" id="anulacionCodigoPc"
                   placeholder="Colocar el importe a anular" disabled>
            <div class="input-icon">
                <i class="fa fa-minus-square" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="rucCodigoPc" id="rucCodigoPc"
                   placeholder="Colocar el RUC del cliente" disabled>
            <div class="input-icon">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="razonCodigoPc" id="razonCodigoPc"
                   placeholder="Colocar la razón social" disabled>
            <div class="input-icon">
                <i class="fa fa-map-pin" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="cargaArchivosPC"
             class="d-flex justify-content-center align-items-center col-lg-12 col-md-12 col-sm-12 mb-4"
             style="height: 250px !important;background-color: #f9f9f9;border: 1px solid #e5e5e5; border-radius: 3px;">
            <i class="fa fa-upload"></i>
            <div class="result_picture position-absolute"
                 style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
                <img src="" class="h-100 img-fluid" alt="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
            <button type="submit" class="float-right btn btn-success ">Enviar</button>
        </div>
    </div>
</form>

