<!-- Modal -->
<div class="modal fade" id="modal-relacionar-registro_courier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel"><b>Registros del Asesor</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="courierreg" name="courierreg" >
            <div class="card-group">

                <div class="card col-2">
                    <img class="card-img-top d-none" src="..." alt="Image cap">
                    <div class="card-header">
                        Informacion del registro courier
                    </div>
                    <div class="card-body border border-secondary rounded">

                        <h5 class="card-title d-none">Card title</h5>

                        <p class="card-text">Registro: <span class="data-registro"></span></p>
                        <p class="card-text"><small class="text-muted data-created-at">Creacion</small></p>
                        <p class="card-text">Estado <span class="badge badge-success data-status">Activo</span></p>

                    </div>

                </div>

                <div class="card col-10">
                    <img class="card-img-top d-none" src="..." alt="Image cap">
                    <div class="card-header">
                        Informacion de la seleccion del envio
                    </div>
                    <div class="card-body border border-secondary ">
                        <div class="row">
                            <div clas="col-12">
                                <div class="table-responsive">
                                    <table id="datatable-registros-asesor"
                                           class="table table-striped table-bordered nowrap"
                                           style="text-align: center;">
                                        <thead>
                                        <h4 style="text-align: center"><strong>Listado de envio para seleccion</strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Tracking</th>{{--direccion--}}
                                            <th scope="col">Num Registro</th>{{--referencia--}}
                                            <th scope="col">Fecha</th>{{----}}
                                            <th scope="col">Importe</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <div class="modal-footer">
                {{--<a href="{{ route('pedidos.sinpagos') }}" class="btn btn-danger btn-sm">Ver deudores</a>--}}
                <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
