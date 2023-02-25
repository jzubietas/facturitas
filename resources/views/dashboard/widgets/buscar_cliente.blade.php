<div class="col-md-12">
    <div class="row">
        <div class="col-lg-6">
            <div class="card" style="background-color: #a5770f1a;">
                <div class="card-header">Buscar Cliente/RUC</div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text p-0">
                                        <select id="input_search_type" class="form-control">
                                            <option value="CLIENTE">CLIENTE</option>
                                            <option value="RUC">RUC</option>
                                        </select>
                                    </div>
                                </div>
                                <input id="input_search_cliente" class="form-control" maxlength="11"
                                       placeholder="Buscar cliente">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group mb-3">
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
      {{--<div class="col-lg-6">
        <div class="alert alert-default-success alert-dismissible" >
          <div class="card-header">Quitar vida</div>
          <div class="card-header">
            <div class="row">
              <div class="col-md-9">
                    <div class="input-group-text p-0">
                      <select name="cbx_user_id_vidas" class="form-control border-secondary" id="cbx_user_id_vidas" data-live-search="true" >
                        <option value="">---- SELECCIONE USUARIO ----</option>
                      </select>
                    </div>

              </div>
              <div class="col-md-3">
                <div class="input-group mb-3">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-danger" id="buttom_quita_vida_cliente">
                      <i class="fa fa-male"></i>
                      Quitar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>--}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="search_content_result">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
