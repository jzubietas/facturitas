@extends('adminlte::page')

@section('title', 'Detalle de pagos')

@section('content_header')
  <h1>DETALLE DEL <b>PAGO</b>:{{ $pagos->codigo_mostrar }}</h1>
     {{-- <a href="" data-target="#modal-historial-{{ $pago->id }}" data-toggle="modal"><button class="btn btn-info btn-sm">Historial</button></a> --}}
  {{-- @include('contratos.modals.modalHistorial') --}}
@stop

@section('content')

@include('pagos.movimientos')

@include('pagos.modals.revisarhistorial')
@include('pagos.modals.CambiarImagen')
@include('pagos.modals.EditarDetallepago')
{{--@include('pagos.modals.modalImagenId')--}}

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
                    <th scope="col"><span style="color:red;">DIFERENCIA</span></th>
                    <th scope="col">Historial</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPe = 0;
                    $sumPe = 0;
                    $sumPe2 = 0;
                  @endphp
                  @foreach ($pagoPedidos as $pagoPedido)
                    <tr>
                      <td>{{ $contPe + 1 }}</td>
                      <td>PED000{{ $pagoPedido->pedidos }}<input type="hidden" name="pedido_id[]" id="pedido_id" value="{{ $pagoPedido->pedidos }}"></td>
                      <td>{{ $pagoPedido->codigo }}</td>
                      <

                      @if($pagos->condicion==\App\Models\Pago::ABONADO)
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO ABONADO</td>
                          @else
                          <td>PAGADO ABONADO</td>
                          @endif
                      @elseif($pagos->condicion==\App\Models\Pago::OBSERVADO)
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO OBSERVADO</td>
                          @else
                          <td>PAGADO OBSERVADO</td>
                          @endif
                      @elseif($pagos->condicion==\App\Models\Pago::PENDIENTE)

                          <td>PENDIENTE</td>

                      @elseif($pagos->condicion==\App\Models\Pago::PAGO)
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO PAGO</td>
                          @else
                          <td>PAGADO PAGO</td>
                          @endif
                      @endif

                      <td>{{ $pagoPedido->condicion }}</td>
                      <td>{{ $pagoPedido->total }}</td>
                      <td><input type="hidden" name="pedido_id_abono[]" id="pedido_id_abono" value="{{ $pagoPedido->abono }}">{{ $pagoPedido->abono }}</td>
                      @if ($pagoPedido->total - $pagoPedido->abono < 3)
                        <td><span style="color:black;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span></td>
                      @else
                        <td><span style="color:red;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span></td>
                      @endif
                      <td>
                        <a href="" data-target="#modal-historial-pagos-pedido" data-toggle="modal" data-pedido="{{ $pagoPedido->codigo }}" data-pago="{{$pago->id}}"><button class="btn btn-danger btn-sm">Historial</button></a>
                      </td>
                    </tr>
                    @php
                      $sumPe = $sumPe + $pagoPedido->abono;
                      $sumPe2 = $sumPe2 + ($pagoPedido->total - $pagoPedido->abono );
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
                    <td><span style="color:red;"><?php echo number_format($sumPe2, 2, '.', ' ')?></span></td>
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

            {{--@include('pagos.modals.modalDeleteConciliar')--}}

            <div class="table-responsive">
              <table class="table table-striped table_pagos_realizados">
                <thead>
                  <tr>
                    <th width='50px' scope="col">ITEM</th>
                    <th width='80px' scope="col">PAGO</th>
                    <th width='80px' scope="col">BANCO</th>
                    <th width='80px' scope="col">MONTO</th>
                    {{--<th scope="col">FECHA</th>--}}
                    {{--<th scope="col">CUENTA DESTINO</th>--}}
                    <th width='250px' scope="col">TITULAR</th>
                    <th width='80px' scope="col">FECHA DEPOSITO</th>

                    <th scope="col">IMAGEN</th>
                    <th scope="col">ACCION</th>
                      <th scope="col">NOTA/OBSERVACION</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $contPa = 0;
                    $sumPa = 0;
                  @endphp
                  @foreach ($detallePagos as $detallePago)
                    <tr class="nohide_{{ $contPa + 1 }}">
                      <td>{{ $contPa + 1 }}</td>

                      <td>
                       <span><?php
                          if($detallePago->id<10){ ?>
                            COMPR000{{ $detallePago->id }}
                          <?php }else if($detallePago->id<100){ ?>
                            COMPR00{{ $detallePago->id }}
                          <?php }else if($detallePago->id<1000){ ?>
                            COMPR0{{ $detallePago->id }}
                          <?php }else{ ?>
                            COMPR{{ $detallePago->id }}
                          <?php } ?>  </span>


                      <input type="hidden" name="detalle_id[]" value="{{ $detallePago->id }}" class="form-control"></td>
                      <td class="banco_{{ $contPa + 1 }}">{{ $detallePago->banco }}</td>
                      <td>{{ $detallePago->monto }}</td>



                      <td class="titular_{{ $contPa + 1 }}">{{ $detallePago->titular }}</td>

                      <td class="fechadeposito_{{ $contPa + 1 }}">{{ $detallePago->fecha_deposito }}</td>
                      <td>

                        <p>
                          <br><a href="" data-target="#modal-imagen-{{ $detallePago->id }}" data-toggle="modal">
                          <img src="{{ asset('storage/pagos/' . $detallePago->imagen) }}" alt="{{ $detallePago->imagen }}" height="200px" width="200px" class="img-thumbnail" id="imagen_{{ $contPa + 1 }}">
                        </a>

                        </p>
                        <a href="" data-target="#modal-cambiar-imagen" data-toggle="modal" data-imagen="{{ $detallePago->imagen }}" data-conciliar="{{ $detallePago->id }}" data-item="{{ $contPa + 1 }}"><button class="btn btn-danger btn-md">Cambiar</button></a>
                        <input type="hidden" value="" name="conciliar[]" class="conciliar_count" id="conciliar_{{ $contPa + 1 }}" >
                      </td>
                      <td>

                        <p>
                          <br>
                          <a href="{{ route('pagos.descargarimagen', $detallePago->imagen) }}" class="text-center"><button type="button" class="btn btn-secondary btn-md"> Descargar</button></a>

                          <a href="" data-target="#modal-conciliar-get" class="modal-conciliar-get" data-fechadeposito="{{ $detallePago->fecha_deposito }}" data-toggle="modal" data-conciliar="{{ $detallePago->id }}" data-item="{{ $contPa + 1 }}"><button class="btn btn-danger btn-md">Conciliar</button></a>
                          <a href="" data-target="#modal-editar-get" data-toggle="modal" data-pbanco="{{ $detallePago->banco }}" data-titulares="{{ $detallePago->titular }}" data-fecha="{{ $detallePago->fecha_deposito_change }}" data-conciliar="{{ $detallePago->id }}" data-item="{{ $contPa + 1 }}"><button class="btn btn-warning btn-md">Editar</button></a>
                        </p>
                      </td>
                        <td>
                            {{$detallePago->nota}} {{$detallePago->observacion}}
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
                    <td colspan="5" style="text-align: center; padding-top: 5%;"><b>OBSERVACION</b></td>
                    <td colspan="3">
                      {!! Form::textarea('observacion', $pagos->observacion, ['class' => 'form-control', 'rows' => '7', 'placeholder' => 'Ingrese observaciones']) !!}
                    </td>
                  </tr>
                  <tr>
                    <th style="text-align: center">TOTAL:</th>
                    <th></th>

                    <th colspan="2">
                        <h4><?php echo number_format($sumPa, 2, '.', ' ')?></h4>
                    </th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center"></th>
                    <th>

                      <select name="condicion" class="border form-control selectpicker border-secondary d-none" id="condicion" data-live-search="true" >
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
      @if(count($devoluciones)>0)
          <div class="card-body" id="section_devoluciones">
              <div class="border rounded card-body border-secondary">
                  <div class="form-row">
                      <div class="form-group col-lg-12">
                          <h3>DEVOLUCIONES</h3>
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
                                      <th scope="col">Nro OPERACION</th>
                                      <th scope="col">TITULAR</th>
                                      <th scope="col">FECHA DEVOLUCION</th>
                                      <th scope="col">ESTADO</th>
                                      <th scope="col">IMAGEN</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @php
                                      $contPa = 0;
                                      $sumPa = 0;
                                  @endphp
                                  @foreach ($devoluciones as $devolucion)
                                      <tr>
                                          <td>{{ $contPa + 1 }}</td>
                                          <td>{{ $devolucion->code_id }}</td>
                                          <td>{{ $devolucion->bank_destino }}</td>
                                          <td>{{$devolucion->amount_format}}</td>
                                          <td>{{ optional($devolucion->created_at)->format('d-m-Y h:i A') }}</td>
                                          <td>{{ $devolucion->bank_number }}</td>
                                          <td>{{ $devolucion->num_operacion?:'--' }}</td>
                                          <td>{{ $devolucion->cliente->nombre }}</td>
                                          <td>{{ optional($devolucion->returned_at)->format('d-m-Y h:i A')??'--' }}</td>
                                          <td class="bg-{{$devolucion->estado_color}}">
                                              {{$devolucion->estado_text}}
                                          </td>
                                          <td><a href="" data-target="#modal-imagen-{{ $detallePago->id }}"
                                                 data-toggle="modal">
                                                  <img
                                                      src="{{ Storage::disk($devolucion->voucher_disk)->url($devolucion->voucher_path) }}"
                                                      alt="{{ basename($devolucion->voucher_path) }}" height="200px"
                                                      width="200px" class="img-thumbnail"></a>
                                              <p>
                                                  <br>
                                                  <a target="_blank"
                                                     href="{{ Storage::disk($devolucion->voucher_disk)->url($devolucion->voucher_path) }}">
                                                      <button type="button" class="btn btn-secondary"> Descargar
                                                      </button>
                                                  </a>
                                              </p>
                                          </td>
                                      </tr>
                                      @php
                                          $sumPa = $sumPa + $devolucion->amount;
                                          $contPa++;
                                      @endphp
                                      @include('pagos.modals.modalimagen')
                                  @endforeach
                                  </tbody>
                                  <tfoot>
                                  <th style="text-align: center">TOTAL</th>
                                  <th></th>
                                  <th></th>
                                  <th><h4><?php echo money_f($sumPa) ?></h4></th>
                                  <th></th>
                                  </tfoot>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      @endif
    <div class="card-footer text-center" id="guardar">
      <div class="row">
        <div class="col-2 text-left">


        <button type = "button" onClick="history.back()" class="btn btn-danger btn-lg"><i class="fas fa-arrow-left"></i>ATRAS</button>
        </div>
        <div class="col-10">
          <button type="button" id="aprobarrbtn" class="btn btn-success btn-lg"><i class="fas fa-save"></i> APROBAR</button>
        </div>
      </div>

      <input type="hidden" name="mover_revisar" value="observado">


    </div>
    <div class="card-footer" >
      <button type="submit" class="btn btn-success btn-lg d-none"><i class="fas fa-save"></i> GUARDAR</button>

    </div>
    {!! Form::close() !!}
  </div>
@stop

@section('css')
<style>
  .modal.verimagen.left .modal-dialog,
 .modal.verimagen.right .modal-dialog {
   position: fixed;
   margin: auto;
   width: 320px;
   height: 100%;
   -webkit-transform: translate3d(0%, 0, 0);
       -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
           transform: translate3d(0%, 0, 0);
 }

 .modal.verimagen.left .modal-content,
 .modal.verimagen.right .modal-content {
   height: 100%;
   overflow-y: auto;
 }

 .modal.verimagen.left .modal-body,
 .modal.verimagen.right .modal-body {
   padding: 15px 15px 80px;
 }

/*Left*/


 .modal.verimagen.left.fade.in .modal-dialog{
   left: 0;
 }

/*Right*/

 .modal.verimagen.right.fade.in .modal-dialog {
   right: 0;
 }
 .modal.verimagen.right .modal-dialog {
   right: 0;
 }

/* ----- MODAL STYLE ----- */
 .modal-content {
   border-radius: 0;
   border: none;
 }

 .modal-header {
   border-bottom-color: #EEEEEE;
   background-color: #FAFAFA;
 }

 .modal-dialog{
   right:0;
   padding-right: 0 !important;
   margin-right: 0 !important;
 }

 /*@media (min-width: 576px)
 {
   .modal-dialog
   {
     margin:inherit;
     padding-right: 0 !important;
      margin-right: 0 !important;

   }
 }*/
 </style>
@stop

@section('js')

  {{--<script src="{{ asset('js/datatables.js') }}"></script>--}}
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://momentjs.com/downloads/moment.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.11.4/dataRender/datetime.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


  <script>
    var tableconciliar=null;
    $(document).ready(function() {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $(document).on("click",".button_conciliar",function(event){

        var iconciliar = $(this).data('conciliar')
        var iitem = $(this).data('item')

        $(".hide_"+iitem).remove();
        console.log("fila "+iitem);
        console.log("agregando fila")
        console.log($(this).data('banco'));

          if($(this).data('banco')=='BCP')
          {
            $('.table_pagos_realizados tbody tr.nohide_'+iitem).after(
              '<tr style="background-color:#ff7800;" class="hide_'+iitem+' hide_conciliar oculto">'+
              '<td class="text-light">'+iitem+'</td><td class="text-light"> </td>'+
              '<td class="text-light">'+$(this).data('banco')+'</td>'+
              '<td class="text-light">'+$(this).data('importe')+'</td>'+
              '<td class="text-light">'+$(this).data('titular')+'</td>'+
              '<td class="text-light">'+$(this).data('fecha')+'</td>'+
              '</tr>'
          );

          }else if($(this).data('banco')=='BBVA')
          {
            $('.table_pagos_realizados tbody tr.nohide_'+iitem).after(
            '<tr style="background-color:#1973B8;" class="hide_'+iitem+' hide_conciliar oculto">'+
            '<td class="text-light">'+iitem+'</td><td class="text-light"> </td>'+
              '<td class="text-light">'+$(this).data('banco')+'</td>'+
              '<td class="text-light">'+$(this).data('importe')+'</td>'+
              '<td class="text-light">'+$(this).data('titular')+'</td>'+
              '<td class="text-light">'+$(this).data('fecha')+'</td>'+
              '</tr>'
          );

          }else if ($(this).data('banco')=='INTERBANK' || $(this).data('banco')=='IBK')
          {
            $('.table_pagos_realizados tbody tr.nohide_'+iitem).after(
            '<tr class="bg-success hide_'+iitem+' hide_conciliar oculto">'+
            '<td class="text-light">'+iitem+'</td><td class="text-light"> </td>'+
              '<td class="text-light">'+'INTERBANK'+'</td>'+
              '<td class="text-light">'+$(this).data('importe')+'</td>'+
              '<td class="text-light">'+$(this).data('titular')+'</td>'+
              '<td class="text-light">'+$(this).data('fecha')+'</td>'+
              '</tr>'
          );

          }else if ($(this).data('banco')=='YAPE')
          {
            $('.table_pagos_realizados tbody tr.nohide_'+iitem).after(
            '<tr style="background-color:#6f42c1;" class="hide_'+iitem+' hide_conciliar oculto">'+
            '<td class="text-light">'+iitem+'</td><td class="text-light"> </td>'+
              '<td class="text-light">'+$(this).data('banco')+'</td>'+
              '<td class="text-light">'+$(this).data('importe')+'</td>'+
              '<td class="text-light">'+$(this).data('titular')+'</td>'+
              '<td class="text-light">'+$(this).data('fecha')+'</td>'+
              '</tr>'
          );

          }else if ($(this).data('banco')=='PLIN')
          {
            $('.table_pagos_realizados tbody tr.nohide_'+iitem).after(
            '<tr style="background-color:#0693e3;" class="hide_'+iitem+' hide_conciliar oculto" >'+
            '<td class="text-light">'+iitem+'</td><td class="text-light"> </td>'+
              '<td class="text-light">'+$(this).data('banco')+'</td>'+
              '<td class="text-light">'+$(this).data('importe')+'</td>'+
              '<td class="text-light">'+$(this).data('titular')+'</td>'+
              '<td class="text-light">'+$(this).data('fecha')+'</td>'+
              '</tr>'
          );

        }


        $("#conciliar_"+iitem).val(iconciliar);

        console.log(iconciliar)
        //$(".textcode").html(idunico);
        //$(".textimporte").html(importemodal);
        $("#modal-conciliar-get").modal("hide");
      });

      tableconciliar=$('#tablaPrincipalConciliar').DataTable({
          "bPaginate": false,
          "bFilter": false,
          "bInfo": false,
          "length": 3,
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
            "emptyTable": "No hay información",
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

      $('#modal-cambiar-imagen').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var imagen = button.data('imagen');
        var conciliar = button.data('conciliar');
        var itemcount = button.data('item');
//#modal-imagen-4359 modalimagen_item
        let item_modalimagen=$(".nohide_"+itemcount).find("td").eq(0).html();
        let pago_modalimagen=$.trim($(".nohide_"+itemcount).find("td").eq(1).find("span").html());
        let banco_modalimagen=$(".nohide_"+itemcount).find("td").eq(2).html();
        let monto_modalimagen=$(".nohide_"+itemcount).find("td").eq(3).html();
        let titular_modalimagen=$(".nohide_"+itemcount).find("td").eq(4).html();
        let fecha_modalimagen=$(".nohide_"+itemcount).find("td").eq(5).html();
        console.log(pago_modalimagen);
        console.log("#modal-cambiar-"+conciliar+" .modalimagen_item")
        $("#modal-cambiar-imagen .modalimagen_item").val(item_modalimagen);
        $("#modal-cambiar-imagen .modalimagen_pago").val(pago_modalimagen);
        $("#modal-cambiar-imagen .modalimagen_banco").val(banco_modalimagen);
        $("#modal-cambiar-imagen .modalimagen_monto").val(monto_modalimagen);
        $("#modal-cambiar-imagen .modalimagen_titular").val(titular_modalimagen);
        $("#modal-cambiar-imagen .modalimagen_fecha").val(fecha_modalimagen);


        console.log("imagen "+imagen);
        console.log("conciliar "+conciliar);
        console.log("itemcount "+itemcount);

        $("#DPConciliar").val(conciliar);
        $("#DPitem").val(itemcount);


        var urlimg = "{{asset('imagenes/logo_facturas.png')}}";
        urlimg = urlimg.replace('imagenes/', 'storage/pagos/');

        urlimg = urlimg.replace('logo_facturas.png', imagen);
        urlimg = urlimg.replace(' ', '%20');
        console.log(urlimg)

        $("#picture").attr("src", urlimg );

      });

      $(document).on("click","#change_imagen",function(){
        var fd2 = new FormData();
        //agregados el id pago
        let files=$('input[name="pimagen')
        var fileitem=$("#DPitem").val();

        fd2.append("DPConciliar",$("#DPConciliar").val() )
        for (let i = 0; i < files.length; i++) {
          fd2.append('adjunto', $('input[type=file][name="pimagen"]')[0].files[0]);
        }
        $.ajax({
          data: fd2,
          processData: false,
          contentType: false,
          type: 'POST',
          url:"{{ route('pagos.changeImg') }}",
          success:function(data){
            if(data.html=='0')
            {
            }else{
              $("#modal-cambiar-imagen").modal("hide");

              var urlimg = "{{asset('imagenes/logo_facturas.png')}}";
              urlimg = urlimg.replace('imagenes/', 'storage/pagos/');

              urlimg = urlimg.replace('logo_facturas.png', data.html);
              urlimg = urlimg.replace(' ', '%20');
              console.log(urlimg);

              $("#imagen_"+fileitem).attr("src", urlimg );

              //$("#picture").attr("src", urlimg );

            }
          }
        });


      });



      $(document).on("change","#pimagen",function(event){
        console.log("cambe image")
        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = (event) => {
          //$("#picture").attr("src",event.target.result);
            document.getElementById("picture").setAttribute('src', event.target.result);
        };
        reader.readAsDataURL(file);

      });

      $('#modal-conciliar-get').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget)

        var idunico = button.data('conciliar')
        var iditem = button.data('item');

        //
        let item_modalimagen=$(".nohide_"+iditem).find("td").eq(0).html();
        let pago_modalimagen=$.trim($(".nohide_"+iditem).find("td").eq(1).find("span").html());
        let banco_modalimagen=$(".nohide_"+iditem).find("td").eq(2).html();
        let monto_modalimagen=$(".nohide_"+iditem).find("td").eq(3).html();
        let titular_modalimagen=$(".nohide_"+iditem).find("td").eq(4).html();
        let fecha_modalimagen=$(".nohide_"+iditem).find("td").eq(5).html();
        //console.log(pago_modalimagen);
        //console.log("#modal-cambiar-"+conciliar+" .modalimagen_item")
        $("#modal-conciliar-get .modalimagen_item").val(item_modalimagen);
        $("#modal-conciliar-get .modalimagen_pago").val(pago_modalimagen);
        $("#modal-conciliar-get .modalimagen_banco").val(banco_modalimagen);
        $("#modal-conciliar-get .modalimagen_monto").val(monto_modalimagen);
        $("#modal-conciliar-get .modalimagen_titular").val(titular_modalimagen);
        $("#modal-conciliar-get .modalimagen_fecha").val(fecha_modalimagen);
        //

        //var fechadeposito = button.data('fechadeposito');
        let row_conciliar=button.closest("tr");
        var fechadeposito=  row_conciliar.find("td").eq(5).html();
        console.log(fechadeposito)


        //console.log(bb_banco)

        //cambiar logica de campos obtener ahora de tabla datos
         /*let row_conciliar=button.closest("tr");
         bb_banco=row_conciliar.find("td").eq(2).html();//banco
         bb_titular=row_conciliar.find("td").eq(4).html();//titular
         bb_fecha=row_conciliar.find("td").eq(5).html();//fecha


         console.log(bb_banco)
         console.log(bb_titular)
         console.log(bb_fecha)*/

        //incluir todos los conciliar
        var excluir=[];
        $('input[name="conciliar[]').each(function(){
          if(this.value!='')
            excluir.push(this.value)
            //xcluir=excluir+''+this.value+',';
       });
       //excluir=excluir.substring(0, excluir.length - 1);
       //console.log(excluir);

       var pasarExclusiones=excluir.join(',');
       //console.log(pasarExclusiones)
        //var
        //var inputt=("#conciliar_"+iditem).val();
        //console.log(idunico);

        //var obtener_detpag='191';
        tableconciliar.destroy();

        tableconciliar=$('#tablaPrincipalConciliar').DataTable({
          "bPaginate": true,
          "bFilter": true,
          "bInfo": true,
          "bAutoWidth": false,
           "pageLength":5,
          "order": [[ 0, "asc" ]],
          'ajax': {
            url:"{{ route('movimientostablaconciliar') }}",
            'data': { "conciliar":idunico,"excluir":pasarExclusiones ,"fechadeposito":fechadeposito},
            "type": "get",
          },
          columns:
          [
            {
              data: 'id',
              name: 'id',
              "visible":false
              /*sWidth:'30%',
              render: function ( data, type, row, meta ) {
                return '<span class="id">' + data + '</span>';
              }*/
            },
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
              render: $.fn.dataTable.render.moment( 'DD/MM/YYYY' )
              /*render: function ( data, type, row, meta ) {
                return '<span class="fecha">' + data + '</span>';
              }*/
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
            {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {
              data = data+''+
                            '<button class="btn btn-danger btn-sm button_conciliar" data-conciliar="'+row.id+'" data-item="'+iditem+'" data-importe="'+row.importe+'" data-titular='+row.titular+' data-banco="'+row.banco+'" data-fecha="'+row.fechamodal+'" data-tipo="'+row.tipo+'"><i class="fas fa-check-circle"></i></button>'+
                          '';
              return data;
            },
          }
          ],
          language: {
            "decimal": "",
            "emptyTable": "No hay información",
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

      $('#modal-editar-get').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var pbanco = button.data('pbanco')
        var titulares = button.data('titulares')
        var fecha = button.data('fecha')
        var conciliar = button.data('conciliar')
        var nitem = button.data('item')

        //var fecha=$.datepicker.formatDate( "dd-mm-yy", new Date(fecha));
        $("#conciliarupdate").val(conciliar)
        $("#itemupdate").val(nitem)
        $("#pbanco").val(pbanco).selectpicker("refresh");
        $("#titulares").val(titulares).selectpicker("refresh");
        console.log(fecha);
        $("#pfecha").val(fecha);

      });

      $(document).on("click","#edit_dp",function(e){
        e.preventDefault();
        if ($('#pbanco').val() == '')
        {
          Swal.fire(
            'Error',
            'Seleccione banco ',
            'warning'
          );
          return false;
        }else if ($('#titulares').val() == '')
        {
          Swal.fire(
            'Error',
            'Seleccione titular',
            'warning'
          )
          return false;
        }else if ($('#pfecha').val() == '')
        {
          Swal.fire(
            'Error',
            'Seleccione la fecha',
            'warning'
          )
          return false;
        }else{
          conciliar=$("#conciliarupdate").val();
          item=$("#itemupdate").val();
          titular = $('#titulares option:selected').val();
          banco = $('#pbanco option:selected').val();
          fecha = $("#pfecha").val();

          var formd = new FormData();

          //formData.append("item",cambiaitem )
          formd.append("conciliar",conciliar)
          formd.append("item",item)
          formd.append("titular",titular)
          formd.append("banco",banco)


          formd.append("fecha",fecha)


          $.ajax({
            type:'POST',
            url:"{{ route('pagodetalleUpdate') }}",
            data:formd,
            processData: false,
            contentType: false,
          }).done(function (data) {
            $("#modal-editar-get").modal("hide");
            console.log(data.html)
            console.log(data.html.banco);
            //console.log("banco a "+banco)
            //console.log("titular a "+titular)
            //console.log("titular a "+titular)

            $(".banco_"+item).html(data.html.banco);
            $(".titular_"+item).html(data.html.titular);
            $(".fechadeposito_"+item).html(data.html.fecha);

            //let bb_modal=
            $('a.modal-conciliar-get[data-item="1"]').attr('data-fechadeposito', data.html.fecha);

            //let row_conciliar=button.closest("tr");
            //bb_modal=data_conciliar_row.find("td").eq(7).find('.modal-conciliar-get');
            //bb_modal.attr("data-fechadeposito",data.html.fecha_conciliar);
            /*bb_banco=row_conciliar.find("td").eq(2).html();//banco
            bb_titular=row_conciliar.find("td").eq(4).html();//titular
            bb_fecha=row_conciliar.find("td").eq(5).html();//fecha*/


            //console.log(bb_banco)

            //$('#tablaPrincipal').DataTable().ajax.reload();

            //location.reload();
          });


        }



        let tipotrans = $("#pbanco").val();
        let descrip_otros = $("#descrip_otros").val();


      });

      $('#modal-historial-pagos-pedido').on('show.bs.modal', function (event) {

       console.log("aa")
       var button = $(event.relatedTarget)
       var pedido = button.data('pedido')
       var pago = button.data('pago')

       tableconciliar.destroy();

       tableconciliar=$('#tablapagospedidoshistorial').DataTable({
         "bPaginate": true,
         "bFilter": true,
         "bInfo": true,
         "bAutoWidth": false,
          "pageLength":5,
         "order": [[ 0, "asc" ]],
         'ajax': {
           url:"{{ route('pagostablahistorial') }}",
           'data': { "pedido":pedido,"pago":pago },
           "type": "get",
         },
         "search": {
            "search": pedido
          },
         columns: [
        {
            data: 'id',
            name: 'id',
            render: function ( data, type, row, meta ) {
              var cantidadvoucher=row.cantidad_voucher;
              var cantidadpedido=row.cantidad_pedido;
              var unido= ( (cantidadvoucher>1)? 'V':'I' )+''+( (cantidadpedido>1)? 'V':'I' );
              if(row.id<10){
                return 'PAG'+row.users+unido+'000'+row.id;
              }else if(row.id<100){
                return 'PAG00'+row.users+unido+''+row.id;
              }else if(row.id<1000){
                return 'PAG0'+row.users+unido+''+row.id;
              }else{
                return 'PAG'+row.users+unido+''+row.id;
              }

              /*if(row.id<10){
                return 'PAG000'+row.id;
              }else if(row.id<100){
                return 'PAG00'+row.id;
              }else if(row.id<1000){
                return 'PAG0'+row.id;
              }else{
                return 'PAG'+row.id;
              } */
            }
        },
        {
          data: 'codigos'
          , name: 'codigos'
          , render: function ( data, type, row, meta ) {
            if(data==null){
              return 'SIN PEDIDOS';
            }else{
              var returndata='';
              var jsonArray=data.split(",");
              $.each(jsonArray, function(i, item) {
                  returndata+=item+'<br>';
              });
              return returndata;
            }
          }
        },
        {//asesor
          data: 'users', name: 'users' },
        {//cliente
          data: 'celular',
            name: 'celular',
            render: function ( data, type, row, meta ) {
              return row.celular;
            },
        },
        {//observacion
          data: 'observacion', name: 'observacion'
        },
        /*{
          data: 'total_cobro', name: 'total_cobro'
        },*/
        {//totalpagado
          data: 'total_pago', name: 'total_pago'
        },
        {//fecha
          data: 'fecha',
          name: 'fecha',
          render: function ( data, type, row, meta ) {
              return data;
          }
        },//estado de pedido
        {
          data: 'condicion',
          name: 'condicion',
          render: function ( data, type, row, meta ) {
            return data;
          }
        },//estado de pago
        {data: 'action', name: 'action', orderable: false, searchable: false,sWidth:'20%'},
        ],
         language: {
           "decimal": "",
           "emptyTable": "No hay información",
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

      $(document).on("click","#aprobarrbtn",function(){
        console.log("aprobar");
        $("#condicion").val("{{\App\Models\Pago::ABONADO}}").selectpicker("refresh");
        $("#formulario").submit();

      });

      $(document).on("click","#observarbtn",function(){
        console.log("observar")
        $("#condicion").val("{{\App\Models\Pago::OBSERVADO}}").selectpicker("refresh");
        $("#formulario").submit();
      });

      $(document).on("click","#pendientebtn",function(){
        console.log("pendiente")
        $("#condicion").val("{{\App\Models\Pago::PENDIENTE}}").selectpicker("refresh");
        $("#formulario").submit();
      });

      $(document).on("submit","#formulario",function(event){
            event.preventDefault();
            console.log("form submit");

            var campo_condicion = $("#condicion").val();
            var inputconciliar=0
            if(campo_condicion=='{{\App\Models\Pago::ABONADO}}')
            {
                    var total_conciliar={{$contPa}};
                    console.log("total detalles para conciliar: "+total_conciliar);
                    var exxxxx=$(".hide_conciliar").length;
                    console.log("cont debo conciliar "+exxxxx)
                    if(exxxxx!=total_conciliar)
                    {
                      Swal.fire(
                        'Error',
                        'Faltan conciliaciones 1',
                        'warning'
                      )
                      return false;
                    }

                    var error_conciliar=true;
                    var existe_r=null;

                    for(var ir = 1; ir < total_conciliar+1; ir++)
                    {
                      existe_r=$(".hide_"+ir).length;
                      if(existe_r==0)
                      {
                      }else{
                        console.log("fila "+ir)
                        let html_importe_dpa=$(".nohide_"+ir).find("td").html();
                        let html_importe_con=$(".hide_"+ir).find("td").html();
                        console.log(" pago "+html_importe_dpa)
                        console.log(" mov  "+html_importe_con)
                        //comparar importe
                        let importe_dpa=$(".nohide_"+ir).find("td").eq(3).html();
                        let importe_con=$(".hide_"+ir).find("td").eq(3).html();
                        console.log("importe dpa "+importe_dpa)
                        console.log("importe con "+importe_con)

                        if(importe_dpa!=importe_con)
                        {
                          let importe_dpa_n=parseFloat(importe_dpa);
                          let importe_dpa_ma=parseFloat(importe_dpa)+500;//limite era 3
                          let importe_dpa_me=parseFloat(importe_dpa)-500;//limite era 3
                          console.log(parseFloat(importe_dpa))
                          console.log(parseFloat(importe_dpa)+3)
                          console.log(parseFloat(importe_dpa)-3)
                          if( importe_con <=(importe_dpa_ma) )
                          {
                          }
                          else if(  importe_con >=(importe_dpa_me) )
                          {
                          }
                          else
                          {
                            Swal.fire(
                              'Error',
                              'Existen pagos que no coinciden en importe',
                              'warning'
                            )
                            error_conciliar=false;
                            return false;
                          }

                        }

                        let fecha_dpa=$(".nohide_"+ir).find("td").eq(5).html();
                        let fecha_con=$(".hide_"+ir).find("td").eq(5).html();
                        console.log("fecha_dpa "+fecha_dpa)
                        console.log("fecha_con "+fecha_con)

                        if(fecha_dpa!=fecha_con)
                        {
                          Swal.fire(
                            'Error',
                            'Existen pagos que no coinciden en fecha',
                            'warning'
                          )
                          error_conciliar=false;
                          return false;
                        }

                      }

                    }


                    if(error_conciliar===false)
                    {

                      Swal.fire(
                        'Error',
                        'Existen comprobantes sin conciliar o el importe no coincide con el movimiento',
                        'warning'
                      )
                      return false;
                    }

                    this.submit();
            }else{

  //validar  detalle pago con conciliaciones  que coincida los importes

              /* var cuenta = document.getElementById('cuenta').value; */
              cuenta = document.getElementsByName("cuenta[]");
              /* var titular = document.getElementById('titular').value; */
              titular = document.getElementsByName("titular[]");
              /* var fecha_deposito = document.getElementById('fecha_deposito').value; */
              fecha_deposito = document.getElementsByName("fecha_deposito[]");
              var condicion = document.getElementById('condicion').value;

              var filas_pagos=$(".table_pagos_realizados tbody tr.nohide").length;


              var cuent = [];
              var tit = [];
              var fec = [];

              for(var i=0;i<cuenta.length;i++){
                  cuent.push(cuenta[i].value);
                  tit.push(titular[i].value);
                  fec.push(fecha_deposito[i].value);
              }
              var tthis=this;
              console.info(cuent);
              console.info(tit);
              console.info(fec);
              {
                tthis.submit();
              }

            }




      });


    });


  </script>


  <script>
    //VALIDAR ANTES DE ENVIAR
    /*document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulssario").addEventListener('submit', validarFormulario);
    });*/

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
      var tthis=this;
      console.info(cuent);
      console.info(tit);
      console.info(fec);

      $("#condicion").val("PAGO").selectpicker("refresh");

      Swal.fire({
        title: "Esta seguro que desea continuar?",
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, continuar!'
      }).then((result) => {
        console.log(result);
        if (result.value==true)
        {
          tthis.submit();
        }
      });

      //seguro que desea continuar

      /*if (condicion == "ABONADO") {
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
      else*/ {

          }
    }
  </script>
@stop
