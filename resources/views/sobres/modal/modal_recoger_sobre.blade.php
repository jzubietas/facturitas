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
                                        <h4 style="text-align: center"><strong>Listado de pedidos del cliente <span class="badge badge-success nombre_cliente_recojo"></span></strong></h4>
                                        <tr>
                                            <th scope="col">Item</th>
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

                        <form id="formrecojo" name="formrecojo" role="form">

                            <input type="hidden" id="recojo_cliente" name="recojo_cliente">
                            <input type="hidden" id="recojo_pedido" name="recojo_pedido">

                            <div class="form-group">
                                <label for="recojo_cliente_name">Cliente</label>
                                <input type="text" class="form-control" id="recojo_cliente_name" placeholder="Cliente" readonly>
                            </div>

                            <div class="form-group">
                                <label for="recojo_pedido_codigo">Pedido</label>
                                <input type="text" class="form-control" id="recojo_pedido_codigo" placeholder="Pedido" readonly>
                            </div>

                            <div class="form-group">
                                <label for="recojo_pedido_grupo">Grupo</label>
                                <input type="text" class="form-control" id="recojo_pedido_grupo" placeholder="Grupo" readonly>
                            </div>

                            <div class="form-group">
                                {!! Form::label('recojo_fecha', 'Fecha de recojo') !!}
                                {!! Form::dateTimeLocal('recojo_fecha', \Carbon\Carbon::now(), ['class' => 'form-control', 'id' => 'recojo_fecha']) !!}
                            </div>

                            <div class="form-group">
                                <label for="recojo_descripcion">Descripcion</label>
                                <textarea class="form-control" id="recojo_descripcion" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Registrar recojo</button>
                            </div>

                        </form>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <span>Destino</span>
                                    </div>
                                    <div class="col">
                                        <span class="destino_recojo"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <span>Distrito</span>
                                    </div>
                                    <div class="col">
                                        <span class="distrito_recojo"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <span>Direccion</span>
                                    </div>
                                    <div class="col">
                                        <span class="direccion_recojo"></span>
                                    </div>
                                </div>

                            </div>
                        </div>

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
