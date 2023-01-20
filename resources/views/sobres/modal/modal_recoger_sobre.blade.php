<!-- Modal -->
<div class="modal fade" id="modal-recoger-sobre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel"><b>Pedidos para recojo</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-group">

                <!--lista clientes-->
                <div class="card">
                    <img class="card-img-top d-none" src="..." alt="Image cap">
                    <div class="card-body border border-secondary rounded">
                        <div class="row">
                            <div clas="col-12">
                                <div class="table-responsive">
                                    <table id="datatable-clientes-lista-recojer"
                                           class="table table-striped table-bordered nowrap"
                                           style="text-align: center;width:100%;">
                                        <thead>
                                        <h4 style="text-align: center"><strong>Listado de clientes para seleccion</strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Celular</th>
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
                    <div class="card-footer">
                        Informacion de la seleccion del cliente
                    </div>
                </div>

                <!--listado pedidos-->
                <div class="card">
                    <img class="card-img-top d-none" src="..." alt="Image cap">
                    <div class="card-body border border-secondary rounded">
                        <div class="row">
                            <div clas="col-12">
                                <div class="table-responsives">
                                    <table id="datatable-pedidos-lista-recojer" class="table table-striped" style="text-align: center;width:100%;">
                                        <thead>
                                        <h4 style="text-align: center"><strong>Listado de pedidos del cliente seleccionado</strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Id</th>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Condicion</th>
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
                    <div class="card-footer">
                        Informacion de la seleccion del pedido
                    </div>
                </div>

                <!--formulario-->
                <div class="card">
                    <img class="card-img-top d-none" src="..." alt="Image cap">
                    <div class="card-body border border-secondary rounded">

                        <form id="forma" name="forma" role="form">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Email address</label>
                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Example textarea</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Accion</button>

                        </form>

                    </div>
                    <div class="card-footer">
                        ago
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
