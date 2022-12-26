<div class="card">
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item">
                <h4><b>Meta del mes</b></h4>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h5>Cobranzas noviembre</h5>
                            </li>
                            <li class="list-group-item">
                                <div class="progress">
                                    <div class="progress-bar
                @if(data_get($data_noviembre,'progress')<50)
                 bg-danger
                 @elseif( data_get($data_noviembre,'progress')<80)
                 bg-warning
                  @else
                  bg-success
                  @endif
                 "
                                         role="progressbar"
                                         style="width: {{ data_get($data_noviembre,'progress')}}%"
                                         aria-valuenow="{{ data_get($data_noviembre,'progress')}}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ data_get($data_noviembre,'progress')}}% - {{data_get($data_noviembre,'current')}}
                                        /{{data_get($data_noviembre,'total')}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <h4>Pedidos diciembre</h4>
                            </li>
                            <li class="list-group-item">
                                <div class="progress">
                                    <div class="progress-bar
                @if(data_get($data_diciembre,'progress')<40)
                 bg-danger
                 @elseif( data_get($data_diciembre,'progress')<70)
                 bg-warning
                  @else
                  bg-success
                  @endif
                 "
                                         role="progressbar"
                                         style="width: {{ data_get($data_diciembre,'progress')}}%"
                                         aria-valuenow="{{ data_get($data_diciembre,'progress')}}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ data_get($data_diciembre,'progress')}}% - {{data_get($data_diciembre,'current')}}
                                        /{{data_get($data_diciembre,'total')}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
