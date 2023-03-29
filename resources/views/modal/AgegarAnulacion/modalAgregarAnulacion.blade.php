<div class="modal fade" id="modal-agregar-anulacion" aria-labelledby="modal-agregar-anulacion" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Anulacion</h5>

                <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">

                <input type="hidden" class="d-none" id="modalagregaranulacion" name="modalagregaranulacion">

                {{--Inicio Tabs Solicitud Anulacion--}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#tabPedidoCompleto"   data-toggle="tab" > Pedido Completo</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tabFactura"  data-toggle="tab" > Factura</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="tabPedidoCompleto">
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
                                            <div class="col-lg-6">
                                                <h6 class="alert alert-warning text-center font-weight-bold" style="background: #62CDFF !important;">
                                                    <b>Doc <span class="text-danger">por anular</span> <i class="fa fa-arrow-right text-danger" aria-hidden="true"></i></b>
                                                </h6>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
                                                    {!! Form::file('inputArchivoSubir[]', ['class' => 'form-control-file', 'id'=>'inputArchivoSubir','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col lg-12">
                                                {!! Form::label('txtMotivoPedComplet', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
                                                {!! Form::textarea('txtMotivoPedComplet', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Completa el sustento para la anulación ',  'id'=>'txtMotivoPedComplet']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h6 class="alert alert-warning text-center font-weight-bold" style="background: #62CDFF !important;">
                                                    <b>Captura de pantalla <span class="text-danger">(*)</span> <i class="fa fa-arrow-right text-danger" aria-hidden="true"></i></b>
                                                </h6>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
                                                    {!! Form::file('inputArchivoCapturaSubir[]', ['class' => 'form-control-file', 'id'=>'inputArchivoCapturaSubir','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col lg-12">
                                                {!! Form::label('txtResponsablePedComplet', 'Ingrese el responsable para la anulacion de pedido') !!}
                                                {!! Form::text('txtResponsablePedComplet', '', ['class' => 'form-control', 'placeholder' => 'Completa el responsable para la anulación ',  'id'=>'txtResponsablePedComplet']) !!}
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
                                                <button type="submit" class="float-right btn btn-success btnEnviarPagoCompleto">Enviar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="tabFactura">
                                    <form id="form-agregaranulacion-f" name="form-agregaranulacion-f" class="agregaranulacion" enctype="multipart/form-data">
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
                                            <div class="col-lg-6">
                                                <h6 class="alert alert-warning text-center font-weight-bold">
                                                    <b>Doc <span class="text-danger">por anular</span> <i class="fa fa-arrow-right text-danger" aria-hidden="true"></i>
                                                    </b>
                                                </h6>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
                                                    {!! Form::file('inputArchivoSubirf[]', ['class' => 'form-control-file', 'id'=>'inputArchivoSubirf','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col lg-12">
                                                {!! Form::label('txtMotivoFactura', 'Ingrese el motivo de la anulación del pedido(Max. 250 caracteres)') !!}
                                                {!! Form::textarea('txtMotivoFactura', '', ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Completa el sustento para la anulación ', 'id'=>'txtMotivoFactura']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h6 class="alert alert-warning text-center font-weight-bold">
                                                    <b>Captura de pantalla <span class="text-danger">(*)</span> <i class="fa fa-arrow-right text-danger" aria-hidden="true"></i></b>
                                                </h6>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group input-group-icon col-lg-12 col-md-12 col-sm-12">
                                                    {!! Form::file('inputArchivoCapturaSubirf[]', ['class' => 'form-control-file', 'id'=>'inputArchivoCapturaSubirf','accept'=>'.png, .jpg,.jpeg,.pdf, .xlsx , .xls', 'multiple']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col lg-12">
                                                {!! Form::label('txtResponsableFactura', 'Ingrese responsable para anulacion de factura') !!}
                                                {!! Form::text('txtResponsableFactura', '', ['class' => 'form-control', 'placeholder' => 'Completa el responsable para la anulación ', 'id'=>'txtResponsableFactura']) !!}
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="input-group input-group-icon d-flex justify-content-center align-items-center">
                                                <button type="submit" class="float-right btn btn-success btnEnviarFactura">Enviar</button>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Fin Tabs Solicitud Anulacion--}}
            </div>

        </div>
    </div>
</div>

@push('css')
    <style>
        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        h4 {
            color: #f0a500;
        }

        input,
        input[type="radio"] + label,
        input[type="checkbox"] + label:before,
        select option,
        select {
            width: 100%;
            padding: 1em;
            line-height: 1.4;
            background-color: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 3px;
            -webkit-transition: 0.35s ease-in-out;
            -moz-transition: 0.35s ease-in-out;
            -o-transition: 0.35s ease-in-out;
            transition: 0.35s ease-in-out;
            transition: all 0.35s ease-in-out;
        }

        input:focus {
            outline: 0;
            border-color: black;
        }

        input:focus + .input-icon i {
            color: black;
        }

        input:focus + .input-icon:after {
            border-right-color: black;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"] + label,
        select {
            display: inline-block;
            width: 50%;
            text-align: center;
            float: left;
            border-radius: 0;
        }

        input[type="radio"] + label:first-of-type {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        input[type="radio"] + label:last-of-type {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        input[type="radio"] + label i {
            padding-right: 0.4em;
        }

        input[type="radio"]:checked + label,
        input:checked + label:before,
        select:focus,
        select:active {
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"] + label {
            position: relative;
            display: block;
            padding-left: 1.6em;
        }

        input[type="checkbox"] + label:before {
            position: absolute;
            top: 0.2em;
            left: 0;
            display: block;
            width: 1em;
            height: 1em;
            padding: 0;
            content: "";
        }

        input[type="checkbox"] + label:after {
            position: absolute;
            top: 0.45em;
            left: 0.2em;
            font-size: 0.8em;
            color: #fff;
            opacity: 0;
            font-family: FontAwesome;
            content: "\f00c";
        }

        input:checked + label:after {
            opacity: 1;
        }

        select {
            height: 3.4em;
            line-height: 2;
        }

        select:first-of-type {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        select:last-of-type {
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
        }

        select:focus,
        select:active {
            outline: 0;
        }

        select option {
            background-color: #17a2b8;
            color: #fff;
        }

        .input-group {
            margin-bottom: 1em;
            zoom: 1;
        }

        .input-group:before,
        .input-group:after {
            content: "";
            display: table;
        }

        .input-group:after {
            clear: both;
        }

        .input-group-icon {
            position: relative;
        }

        .input-group-icon input {
            padding-left: 4.4em;
        }

        .input-group-icon .input-icon {
            position: absolute;
            top: 0px;
            left: 10px;
            width: 3.4em;
            line-height: 3.4em;
            text-align: center;
            pointer-events: none;
        }

        .input-group-icon .input-icon:after {
            position: absolute;
            top: 0.6em;
            bottom: 0.6em;
            left: 3.4em;
            display: block;
            border-right: 1px solid #e5e5e5;
            content: "";
            -webkit-transition: 0.35s ease-in-out;
            -moz-transition: 0.35s ease-in-out;
            -o-transition: 0.35s ease-in-out;
            transition: 0.35s ease-in-out;
            transition: all 0.35s ease-in-out;
        }

        .input-group-icon .input-icon i {
            -webkit-transition: 0.35s ease-in-out;
            -moz-transition: 0.35s ease-in-out;
            -o-transition: 0.35s ease-in-out;
            transition: 0.35s ease-in-out;
            transition: all 0.35s ease-in-out;
        }

        .container {
            max-width: 38em;
            padding: 1em 3em 2em 3em;
            margin: 0em auto;
            background-color: #fff;
            border-radius: 4.2px;
            box-shadow: 0px 3px 10px -2px rgba(0, 0, 0, 0.2);
        }

        .row {
            zoom: 1;
        }

        .row:before,
        .row:after {
            content: "";
            display: table;
        }

        .row:after {
            clear: both;
        }

        .col-half {
            padding-right: 10px;
            float: left;
            width: 50%;
        }

        .col-half:last-of-type {
            padding-right: 0;
        }

        .col-third {
            padding-right: 10px;
            float: left;
            width: 33.33333333%;
        }

        .col-third:last-of-type {
            padding-right: 0;
        }

        @media only screen and (max-width: 540px) {
            .col-half {
                width: 100%;
                padding-right: 0;
            }
        }

    </style>
@endpush
