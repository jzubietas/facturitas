<form id="form-agregaranulacion-f" name="form-agregaranulacion-f" class="agregaranulacion">
    <input type="hidden" id="agregaranulacion_f" name="agregaranulacion_f">
    <input type="hidden" name="opcion" value="2">
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="codigoCodigoF" id="codigoCodigoF"
                   placeholder="Colocar codigo del pedido">
            <div class="input-icon">
                <i class="fa fa-barcode" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="asesorCodigoF" id="asesorCodigoF" placeholder="Asesor" disabled>
            <div class="input-icon">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="importeCodigoF" id="importeCodigoF"
                   placeholder="Colocar el importe del pedido" disabled>
            <div class="input-icon">
                <i class="fa fa-plus-square" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="anularCodigoF" id="anularCodigoF"
                   placeholder="Colocar el importe a anular" disabled>
            <div class="input-icon">
                <i class="fa fa-minus-square" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="rucCodigoF" id="rucCodigoF"
                   placeholder="Colocar el RUC del cliente" disabled>
            <div class="input-icon">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
            </div>
        </div>
        <div class="input-group input-group-icon col-lg-6 col-md-6 col-sm-12">
            <input name="razonCodigoF" id="razonCodigoF"
                   placeholder="Colocar la razón social" disabled>
            <div class="input-icon">
                <i class="fa fa-map-pin" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="cargaArchivosF"
             class="d-flex justify-content-center align-items-center col-lg-12 col-md-12 col-sm-12 mb-4"
             style="height: 250px !important;background-color: #f9f9f9;border: 1px solid #e5e5e5; border-radius: 3px;">
            <i class="fa fa-upload"></i>
            <div class="result_picture position-absolute"
                 style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
                <img src="" class="h-100 img-fluid" alt="">
            </div>
        </div>
        <input type="file" name="agregar_imagen_f" id="agregar_imagen_f" class="d-none form-control" placeholder="">
    </div>
    <div class="row">
        <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
            <button type="submit" class="float-right btn btn-success ">Enviar</button>
        </div>
    </div>


</form>
