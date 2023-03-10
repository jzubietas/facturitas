<div class="modal fade" id="modal-agregar-anulacion" aria-labelledby="modal-agregar-anulacion" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Anulacion</h5>

                <button class="float-right btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">

                <input type="hidden" class="d-none" id="modalagregaranulacion" name="modalagregaranulacion">

                <div class="form-row">
                    <div class="form-group col-lg-6">

                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button id="btn_agregaranulacion_pc" type="button" class="btn rounded btn-info ml-2">Pedido Completo</button>
                            <button id="btn_agregaranulacion_f" type="button" class="btn rounded btn-secondary  ml-2">Facturación</button>
                        </div>

                    </div>
                </div>

                <div id="modal-agregaranulacion-pc-container" class="modal-agregaranulacion-pc-container">
                    @include('modal.AgegarAnulacion.partials.agregaranulacion_pc')
                </div>

                <div id="modal-agregaranulacion-f-container" class="modal-agregaranulacion-f-container">
                  @include('modal.AgegarAnulacion.partials.agregaranulacion_f')
                </div>

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
            border-color: #17a2b8;
        }

        input:focus + .input-icon i {
            color: #17a2b8;
        }

        input:focus + .input-icon:after {
            border-right-color: #17a2b8;
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
            top: 20px;
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
