<div style="text-align: center; font-family:'Times New Roman', Times, serif">
    <h2>
        <p>Bienvenido(a) <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste</b></p>
    </h2>
</div>
<br>
<br>

<div class="row">
    <div class="col-lg-6">
        <div class="card" style="
    background-color: #a5770f1a;
">
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
                <div class="d-flex justify-content-center mb-4 pb-4">
                    <ul class="list-group">
                        <li class="list-group-item">
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-grafico-meta-pedidos-progress-bar></x-grafico-meta-pedidos-progress-bar>
                                </div>
                                <div class="col-md-6">
                                    <x-grafico-metas-progress-bar></x-grafico-metas-progress-bar>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
