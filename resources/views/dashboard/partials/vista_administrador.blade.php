<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Buscar Cliente/RUC</div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <input id="input_search_cliente" class="form-control" maxlength="11"
                                   placeholder="Buscar cliente">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <select id="input_search_type" class="form-control">
                                <option value="CLIENTE">CLIENTE</option>
                                <option value="RUC">RUC</option>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-dark" id="buttom_search_cliente">
                                    <i class="fa fa-search"></i>
                                    Buscar
                                </button>
                                <button type="button" class="btn btn-light"
                                        id="buttom_search_cliente_clear">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="search_content_result">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">Seleccionar Mes</span>
                    </div>
                    <input type="text" class="form-control date-picker" id="datepickerDashborad" aria-describedby="basic-addon3">
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" id="widget-container">
                    <div class="col-md-12">
                        <x-dashboard.graficos.meta-progress-bar></x-dashboard.graficos.meta-progress-bar>
                    </div>
                    <div class="col-md-12">
                        <x-dashboard.graficos.borras.pedidos-por-dia rol="Administrador"
                                                                     title="Cantidad de pedidos de los asesores por dia"
                                                                     label-x="Asesores"
                                                                     label-y="Cant. Pedidos"
                                                                     only-day
                        ></x-dashboard.graficos.borras.pedidos-por-dia>

                        <x-dashboard.graficos.borras.pedidos-por-dia rol="Administrador"
                                                                     title="Cantidad de pedidos de los asesores por mes"
                                                                     label-x="Asesores"
                                                                     label-y="Cant. Pedidos"></x-dashboard.graficos.borras.pedidos-por-dia>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart tab-pane active w-100" id="pedidosxasesor" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart tab-pane active w-100" id="cobranzaxmes" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div id="pagosxmes" class="w-100" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- @include('dashboard.modal.alerta') --}}
