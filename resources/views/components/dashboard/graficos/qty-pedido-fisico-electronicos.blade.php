<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <ul class="list-group">
          <li class="list-group-item">
            <h4>{{Str::upper('Pedidos por atender Físicos')}}</h4>
          </li>
          @foreach(data_get($resultados,'fisico') as $item)
            <li class="list-group-item">
              <div class="card" style="background: {{data_get($item,'bg')}};">
                <div class="card-body ml-2 mr-2">
                  <div class="center-around d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="text-{{data_get($item,'color')}}">{{Str::upper(data_get($item,'title'))}}</h5>
                    </div>
                    <div>
                      <h3 class="text-{{data_get($item,'color')}}">{{data_get($item,'count')}}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>

      {{--          PENDIENTE DE ANULACION--}}
      <div class="col-md-4">
        <ul class="list-group">
          <li class="list-group-item">
            <h4>{{Str::upper('Pedidos por atender Electrónicos')}}</h4>
          </li>
          @foreach(data_get($resultados,'electronic') as $item)
            <li class="list-group-item">
              <div class="card" style="background: {{data_get($item,'bg')}};">
                <div class="card-body ml-2 mr-2">
                  <div class="center-around d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="text-{{data_get($item,'color')}}">{{Str::upper(data_get($item,'title'))}}</h5>
                    </div>
                    <div>
                      <h3 class="text-{{data_get($item,'color')}}">{{data_get($item,'count')}}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="col-md-4">
        <ul class="list-group">
          <li class="list-group-item">
            <h4>{{Str::upper('PEDIDOS PENDIENTES DE ANULACION')}}</h4>
          </li>
          @foreach(data_get($resultados,'anulado') as $item)
            <li class="list-group-item">
              <div class="card" style="background: {{data_get($item,'bg')}};">
                <div class="card-body ml-2 mr-2">
                  <div class="center-around d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="text-{{data_get($item,'color')}}">{{Str::upper(data_get($item,'title'))}}</h5>
                    </div>
                    <div>
                      <h3 class="text-{{data_get($item,'color')}}">{{data_get($item,'count')}}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>

    </div>
  </div>
</div>
