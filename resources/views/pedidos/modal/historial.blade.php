<!-- Modal -->
<div class="modal fade" id="modal-historial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Clientes con <b>deuda</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card" style="overflow: hidden !important;">
                <div class="card-body" style="overflow-x: scroll !important;">
                    <div class="card-body border border-secondary rounded">
                        <table id="tablaPrincipal" class="table table-striped" style="text-align: center">
                            <thead><h4 style="text-align: center"><strong>Listado de la clientes con pedidos con
                                    deuda</strong></h4>
                            <tr>
                                <th scope="col" width="70" class="align-middle">item</th>
                                {{--<th scope="col" width="15" class="align-middle">Cliente</th>--}}
                                <th scope="col" width="15" class="align-middle">Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('pedidos.sinpagos') }}" class="btn btn-danger btn-sm">Ver deudores</a>
                <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@push('css')
    <style>
        #tablaPrincipal {
            width: 100% !important;
        }

        #tablaPrincipal td {
            text-align: start !important;
            vertical-align: middle !important;
        }

        #tablaPrincipal tbody td:nth-child(2){
            text-align: center !important;
        }

        #tablaPrincipal tbody td:nth-child(2) span{
            position: initial !important;
            padding: 4px !important;
        }

    </style>
@endpush
