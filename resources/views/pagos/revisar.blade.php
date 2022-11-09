@extends('adminlte::page')

@section('title', 'Detalle de pagos')

@section('content_header')
  <h1>DETALLE DEL <b>PAGO</b>: PAG000{{ $pagos->id }}</h1>
     {{-- <a href="" data-target="#modal-historial-{{ $pago->id }}" data-toggle="modal"><button class="btn btn-info btn-sm">Historial</button></a> --}}
  {{-- @include('contratos.modals.modalHistorial') --}}  
@stop

@section('content')

@include('pagos.movimientos')

  <div class="card">
      
    {!! Form::model($pago, ['route' => ['administracion.updaterevisar', $pago],'enctype'=>'multipart/form-data', 'id'=>'formulario','files'=>true]) !!}
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <table class="table table-active">
            <thead>
              <tr>
                <td>
                  <th scope="col" class="col-lg-2" style="text-align: right;">ASESOR:</th>
                  <th scope="col">{{ $pagos->users }}</th>
                </td>
                <td>
                  <th scope="col" class="col-lg-2" style="text-align: right;">CLIENTE:</th>
                  <th scope="col">{{ $pagos->celular }} - {{ $pagos->nombre }}</th>
                </td>
                {{-- <td>
                  <th scope="col" colspan="" class="col-lg-2" style="text-align: right;">ESTADO:</th>
                  <th scope="col">
                    <select name="condicion" class="border form-control selectpicker border-secondary" id="condicion" data-live-search="true">
                      <option value="">---- SELECCIONE ----</option>
                        @foreach($condiciones as $condicion)
                      <option value="{{ $condicion }}" {{ ($condicion == $pagos->condicion ? "selected" : "") }}>{{$condicion}}</option>
                        @endforeach
                    </select>
                  </th>
                </td> --}}
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-12">
            <h3 style="text-align: center"><strong>PEDIDOS A PAGAR</strong></h3>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ITEM</th>
                    <th scope="col">PEDIDO</th>
                    <th scope="col">CODIGO</th>
                    <th scope="col">ESTADO DE PAGO</th>
                    <th scope="col">ESTADO</th>
                    <th scope="col">MONTO TOTAL</th>
                    <th scope="col">ABONADO</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPe = 0;
                    $sumPe = 0;
                  @endphp
                  @foreach ($pagoPedidos as $pagoPedido)
                    <tr>
                      <td>{{ $contPe + 1 }}</td>
                      <td>PED000{{ $pagoPedido->pedidos }}<input type="hidden" name="pedido_id[]" id="pedido_id" value="{{ $pagoPedido->pedidos }}"></td>
                      <td>{{ $pagoPedido->codigo }}</td>
                        @if($pagoPedido->pagado == 1)
                        <td>ADELANTO</td>
                        @else
                        <td>
                          @if ($pagoPedido->pagado==2)
                            PAGADO
                          @endif  
                        </td>
                        @endif                
                      <td>{{ $pagoPedido->condicion }}</td>
                      <td>{{ $pagoPedido->total }}</td>
                      <td>{{ $pagoPedido->abono }}</td>
                    </tr>
                    @php
                      $sumPe = $sumPe + $pagoPedido->abono;
                      $contPe++;
                    @endphp
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td>TOTAL ABONADO</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($sumPe, 2, '.', ' ')?></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>          
        </div>
      </div>
    </div>
    
    {{--@include('pagos.conciliar')--}}

    
    <div class="card-body">
      <div class="border rounded card-body border-secondary">
        <div class="form-row">
          <div class="form-group col-lg-12">
            <h3 style="text-align: center"><strong>PAGOS REALIZADOS POR EL CLIENTE</strong> @if($pagos->saldo>0) SALDO A FAVOR DEL CLIENTE: {{ $pagos->saldo }}@endif</h3>
            
            {{--<a href="" data-target="#modal-movimientos-get" data-toggle="modal"><button class="btn btn-danger btn-sm">Movimientos</button></a>--}}

            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ITEM</th>
                    <th scope="col">PAGO</th>
                    <th scope="col">BANCO</th>                
                    <th scope="col">MONTO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">CUENTA DESTINO</th>
                    <th scope="col">TITULAR</th>
                    <th scope="col">FECHA DEPOSITO</th>                    
                    <th scope="col">IMAGEN</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPa = 0;
                    $sumPa = 0;
                  @endphp
                  @foreach ($detallePagos as $detallePago)
                    <tr>
                      <td>{{ $contPa + 1 }}</td>
                      <td>DETPAG00{{ $detallePago->id }}<input type="hidden" name="detalle_id[]" value="{{ $detallePago->id }}" class="form-control"></td>
                      <td>{{ $detallePago->banco }}</td>                  
                      <td>{{ $detallePago->monto }}</td>
                      <td>{{ $detallePago->fecha }}</td>
                      
                      <td>
                        {!! Form::select('cuenta[]', $cuentas, $detallePago->cuenta, ['class' => 'form-control selectpicker border border-secondary', 'id'=>'cuenta','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}  
                      </td>                  
                      <td>
                        {!! Form::select('titular[]', $titulares, $detallePago->titular, ['class' => 'form-control selectpicker border border-secondary', 'id'=>'titular','data-live-search' => 'true', 'placeholder' => '---- SELECCIONE ----']) !!}
                      </td>
                      <td>
                        {{-- <input type="text" class="form-control" value={{$detallePago->fecha_deposito}} name="fecha_deposito[]" id="fecha_deposito"> --}}
                        {!! Form::date('fecha_deposito[]', $detallePago->fecha_deposito, ['class' => 'form-control', 'id' => 'fecha_deposito']) !!}
                        <p>
                          <br>
                          <a href="" data-target="#modal-conciliar-get" data-toggle="modal" data-conciliar="{{ $detallePago->id }}"><button class="btn btn-danger btn-sm">Conciliar</button></a>
                        </p>
                      </td>
                      <td>
                        <a href="" data-target="#modal-imagen-{{ $detallePago->id }}" data-toggle="modal">
                          <img src="{{ asset('storage/pagos/' . $detallePago->imagen) }}" alt="{{ $detallePago->imagen }}" height="200px" width="200px" class="img-thumbnail">
                        </a>
                        <p>
                          <br>
                          <a href="{{ route('pagos.descargarimagen', $detallePago->imagen) }}"><button type="button" class="btn btn-secondary"> Descargar</button></a>
                        </p>
                      </td>
                    </tr>
                    @php
                      $sumPa = $sumPa + $detallePago->monto;
                      $contPa++;
                    @endphp
                    @include('pagos.modals.modalimagen')
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="6" style="text-align: center; padding-top: 5%;"><b>OBSERVACION</b></td>
                    <td colspan="3">
                      {!! Form::textarea('observacion', $pagos->observacion, ['class' => 'form-control', 'rows' => '7', 'placeholder' => 'Ingrese observaciones']) !!}
                    </td>
                  </tr>
                  <tr>
                    <th style="text-align: center">TOTAL:</th>
                    <th></th>
                    <th></th>
                    <th><h4><?php echo number_format($sumPa, 2, '.', ' ')?></h4></th>
                    <th></th> 
                    <th></th>
                    <th></th>
                    <th style="text-align: center">ESTADO:</th>
                    <th>
                      <select name="condicion" class="border form-control selectpicker border-secondary" id="condicion" data-live-search="true">
                        <option value="">---- SELECCIONE ----</option>
                        @foreach($condiciones as $condicion)
                        <option value="{{ $condicion }}" {{ ($condicion == $pagos->condicion ? "selected" : "") }}>{{$condicion}}</option>
                        @endforeach
                      </select>
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>  
    </div>
    <div class="card-footer" id=guardar>
      <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Guardar</button>
      <a href="{{ route('administracion.porrevisar') }}" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> Cancelar</a>
    </div>
    {!! Form::close() !!}
  </div>
@stop

@section('js')

  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script>
    var tableconciliar=null;
    $(document).ready(function() {

      tableconciliar=$('#tablaPrincipalConciliar').DataTable({
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          columns: 
          [
            {
              data: 'titular'
            },
            {
              data: 'banco'
            },
            {
              data: 'fecha'
            },
            {
              data: 'movimiento'
            },
            {
              data: 'monto'
            }
          ],
          language: {
            "decimal": "",
            "emptyTable": "No hay informaciÃ³n",
            "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
            }
          }
        });

      $('#modal-conciliar-get').on('show.bs.modal', function (event) {
       
        console.log("aa")
        var button = $(event.relatedTarget) 
        var idunico = button.data('conciliar')
        console.log(idunico);

        //var obtener_detpag='191';
        tableconciliar.destroy();

        tableconciliar=$('#tablaPrincipalConciliar').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
          //"order": [[ 0, "desc" ]],
          'ajax': {
            url:"{{ route('movimientostablaconciliar') }}",					
            'data': { "conciliar":idunico}, 
            "type": "get",
          },
          columns: 
          [
            {
              data: 'titular', 
              name: 'titular',
              sWidth:'30%',
              render: function ( data, type, row, meta ) {
                return '<span class="titular">' + data + '</span>';
              }
            },
            {
              data: 'banco', 
              name: 'banco',
              sWidth:'15%', 
              render: function ( data, type, row, meta ) {
                return '<span class="banco">' + data + '</span>';
              }
            },
            {
              data: 'fecha', 
              name: 'fecha',
              sWidth:'10%', 
              render: function ( data, type, row, meta ) {
                return '<span class="fecha">' + data + '</span>';
              }
            },
            {
              data: 'tipo', 
              name: 'tipo',
              sWidth:'20%', 
              render: function ( data, type, row, meta ) {
                return '<span class="tipomovimiento">' + data + '</span>';
              }
            },
            {
              data: 'importe', 
              name: 'importe',
              sWidth:'10%', 
              render: function ( data, type, row, meta ) {
                return '<span class="monto">' + data + '</span>';
              }
            },
          ],
          language: {
            "decimal": "",
            "emptyTable": "No hay informaciÃ³n",
            "info": "Mostrando del _START_ al _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
            }
          },
        });


      });

    });

        
  </script>


  <script>
    //VALIDAR ANTES DE ENVIAR
    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
    });

    function validarFormulario(evento) {
      evento.preventDefault();      
      /* var cuenta = document.getElementById('cuenta').value; */
      cuenta = document.getElementsByName("cuenta[]");
      /* var titular = document.getElementById('titular').value; */
      titular = document.getElementsByName("titular[]");
      /* var fecha_deposito = document.getElementById('fecha_deposito').value; */
      fecha_deposito = document.getElementsByName("fecha_deposito[]");
      var condicion = document.getElementById('condicion').value;

      var cuent = [];
      var tit = [];
      var fec = [];

      for(var i=0;i<cuenta.length;i++){
          cuent.push(cuenta[i].value);
          tit.push(titular[i].value);
          fec.push(fecha_deposito[i].value);
      }

      console.info(cuent);
      console.info(tit);
      console.info(fec);

      if (condicion == "ABONADO") {
        if (cuent.includes('') == true) {
            Swal.fire(
              'Error',
              'Completar la cuenta en todos los pagos',
              'warning'
            )
          }
          else  if (tit.includes('') == true) {
            Swal.fire(
              'Error',
              'Completar el titular de la cuenta en todos los pagos',
              'warning'
            )
          }
          else  if (fec.includes('') == true) {
            Swal.fire(
              'Error',
              'Completar la fecha de deposito en todos los pagos',
              'warning'
            )
          }
          else {
            this.submit();
          }  
      }  
      else {
            this.submit();
          }   
    }
  </script>
@stop
