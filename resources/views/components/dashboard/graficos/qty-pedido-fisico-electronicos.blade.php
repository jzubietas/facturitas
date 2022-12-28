<div class="card">
    <div class="card-body">
         <div class="row">
                <div class="col-md-4">
                    <div class="card" style="
    background: #00bcd4;
">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div><h5 class="text-white">{{Str::upper(data_get($jsChart,'fisico.title'))}}</h5></div>
                                <div>
                                    <h3 class="text-white">{{data_get($jsChart,'fisico.count')}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="
    background: #e91e63;
">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div><h5 class="text-white">{{Str::upper(data_get($jsChart,'electroinco.title'))}}</h5></div>
                                <div>
                                   <h3 class="text-white"> {{data_get($jsChart,'electroinco.count')}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
