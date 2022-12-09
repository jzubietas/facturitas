@extends('adminlte::page')

@section('title', 'Detalle de pagos')

@section('content_header')
  <h1>DETALLE DEL <b>PAGO</b>: PAG000{{ $pagos->id }}</h1>
     {{-- <a href="" data-target="#modal-historial-{{ $pago->id }}" data-toggle="modal"><button class="btn btn-info btn-sm">Historial</button></a> --}}
  {{-- @include('contratos.modals.modalHistorial') --}}
@stop

@section('content')

@include('pagos.movimientos')

@include('pagos.modals.revisarhistorial')
@include('pagos.modals.CambiarImagen')

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

                      @if($pagos->condicion=='ABONADO')
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO ABONADO</td>
                          @else
                          <td>PAGADO ABONADO</td>
                          @endif
                      @elseif($pagos->condicion=='OBSERVADO')
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO OBSERVADO</td>
                          @else
                          <td>PAGADO OBSERVADO</td>
                          @endif
                    @elseif($pagos->condicion=='PENDIENTE')
                          @if($pagoPedido->pagado == 1)
                          <td>ADELANTO PENDUIENTE</td>
                          @else
                          <td>PAGADO PENDUIENTE</td>
                          @endif
                      @elseif($pagos->condicion=='PAGO')
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
                    <th scope="col">ITEM</th>
                    <th scope="col">PAGO</th>
                    <th scope="col">BANCO</th>
                    <th scope="col">MONTO</th>
                    {{--<th scope="col">FECHA</th>--}}
                    {{--<th scope="col">CUENTA DESTINO</th>--}}
                    <th scope="col">TITULAR</th>
                    <th scope="col">FECHA DEPOSITO</th>

                    <th scope="col">IMAGEN</th>
                    <th scope="col">ACCION</th>
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
                      <td>DETPAG00{{ $detallePago->id }}

                      <input type="hidden" name="detalle_id[]" value="{{ $detallePago->id }}" class="form-control"></td>
                      <td>{{ $detallePago->banco }}</td>
                      <td>{{ $detallePago->monto }}</td>



                      <td>{{ $detallePago->titular }}</td>

                      <td>{{ $detallePago->fecha_deposito }}</td>
                      <td>

                        <p>
                          <br><a href="" data-target="#modal-imagen-{{ $detallePago->id }}" data-toggle="modal">
                          <img src="{{ asset('storage/pagos/' . $detallePago->imagen) }}" alt="{{ $detallePago->imagen }}" height="200px" width="200px" class="img-thumbnail" id="imagen_{{ $contPa + 1 }}">
                        </a>

                        </p>
                        <a href="" data-target="#modal-cambiar-imagen" data-toggle="modal" data-imagen="{{ $detallePago->imagen }}" data-conciliar="{{ $detallePago->id }}" data-item="{{ $contPa + 1 }}"><button class="btn btn-danger btn-md accion-cambiar-imagen">Cambiar</button></a>
                        <input type="hidden" value="" name="conciliar[]" class="conciliar_count" id="conciliar_{{ $contPa + 1 }}" >
                      </td>
                      <td>

                        <p>
                          <br>
                          <a href="{{ route('pagos.descargarimagen', $detallePago->imagen) }}" class="text-center"><button type="button" class="btn btn-secondary btn-md accion-descargar"> Descargar</button></a>

                          <a href="" data-target="#modal-conciliar-get" data-toggle="modal" data-conciliar="{{ $detallePago->id }}" data-item="{{ $contPa + 1 }}"><button class="btn btn-danger btn-md accion_conciliar">Conciliar</button></a>
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
                    <td colspan="5" style="text-align: center; padding-top: 5%;"><b>OBSERVACION</b></td>
                    <td colspan="3">
                      {!! Form::textarea('observacion', $pagos->observacion, ['class' => 'form-control', 'rows' => '7', 'placeholder' => 'Ingrese observaciones']) !!}
                    </td>
                  </tr>
                  <tr>
                    <th style="text-align: center">TOTAL:</th>
                    <th></th>

                    <th><h4><?php echo number_format($sumPa, 2, '.', ' ')?></h4></th>
                    <th></th>
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
    <div class="card-footer text-center" id="guardar">
      <button type="button" id="aprobarrbtn" class="btn btn-success btn-lg"><i class="fas fa-save"></i> APROBAR</button>
      <button type="button" id="observarbtn" class="btn btn-info btn-lg"><i class="fas fa-save"></i> OBSERVAR</button>


    </div>
    <div class="card-footer" >
      <button type="submit" class="btn btn-success btn-lg d-none"><i class="fas fa-save"></i> GUARDAR</button>
      <a href="{{ route('administracion.porrevisar') }}" class="btn btn-danger btn-lg d-none"><i class="fas fa-times-circle"></i> CANCELAR</a>
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
              '<tr style="background-color:#ff7800;" class="hide_'+iitem+' oculto">'+
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
            '<tr style="background-color:#1973B8;" class="hide_'+iitem+' oculto">'+
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
            '<tr class="bg-success hide_'+iitem+' oculto">'+
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
            '<tr style="background-color:#6f42c1;" class="hide_'+iitem+' oculto">'+
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
            '<tr style="background-color:#0693e3;" class="hide_'+iitem+' oculto" >'+
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
        console.log(button)
        var imagen = button.data('imagen');
        var conciliar = button.data('conciliar');
        console.log("conciliar "+conciliar)
        var itemcount = button.data('item');

        var rowcambiarimagen=$(button).parents('tr');
        var titularcambiarimagen=rowcambiarimagen.find('td').eq(4).html();
        var bancocambiarimagen=rowcambiarimagen.find('td').eq(2).html();
        var fechacambiarimagen=rowcambiarimagen.find('td').eq(5).html();
        var montocambiarimagen=rowcambiarimagen.find('td').eq(3).html();

        console.log("bancocambiarimagen "+bancocambiarimagen);

        $("#modal-cambiar-imagen .modal-header ")

        $("#modal-cambiar-imagen .modalimagen_titular").val(titularcambiarimagen);
        $("#modal-cambiar-imagen .modalimagen_banco").val(bancocambiarimagen);
        $("#modal-cambiar-imagen .modalimagen_fecha ").val(fechacambiarimagen);
        $("#modal-cambiar-imagen .modalimagen_monto ").val(montocambiarimagen);


        //console.log(titularcambiarimagen);
        //console.log(" ---"+rowcambiarimagen)
        //var rowimagen=$(this).closest('tr').find("td").eq(3).find(":input").val(pedidosaldo.toFixed(2));

        //console.log("imagen "+imagen);
        //console.log("conciliar "+conciliar);
        //console.log("itemcount "+itemcount);

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
        console.log("idunico "+idunico)
        var iditem = button.data('item');
        console.log("item "+iditem)

        var rowcambiarimagen=$(button).parents('tr');
        var titularcambiarimagen=rowcambiarimagen.find('td').eq(4).html();
        var bancocambiarimagen=rowcambiarimagen.find('td').eq(2).html();
        var fechacambiarimagen=rowcambiarimagen.find('td').eq(5).html();
        var montocambiarimagen=rowcambiarimagen.find('td').eq(3).html();

        $("#modal-conciliar-get .modalimagen_titular").val(titularcambiarimagen);
        $("#modal-conciliar-get .modalimagen_banco").val(bancocambiarimagen);
        $("#modal-conciliar-get .modalimagen_fecha ").val(fechacambiarimagen);
        $("#modal-conciliar-get .modalimagen_monto ").val(montocambiarimagen);

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
       console.log(pasarExclusiones)
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
            'data': { "conciliar":idunico,"excluir":pasarExclusiones },
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
            {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            sWidth:'20%',
            render: function ( data, type, row, meta ) {
              data = data+''+
                            '<button class="btn btn-danger btn-sm button_conciliar" data-conciliar="'+row.id+'" data-item="'+iditem+'" data-importe="'+row.importe+'" data-titular='+row.titular+' data-banco="'+row.banco+'" data-fecha="'+row.fecha+'" data-fecha="'+row.fecha+'" data-tipo="'+row.tipo+'"><i class="fas fa-check-circle"></i></button>'+
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
        $("#condicion").val("ABONADO").selectpicker("refresh");
        $("#formulario").submit();

      });

      $(document).on("click","#observarbtn",function(){
        console.log("observar")
        $("#condicion").val("OBSERVADO").selectpicker("refresh");
        $("#formulario").submit();
      });


      $(document).on("submit","#formulario",function(event){
            event.preventDefault();
            console.log("form submit")  ;

            /* var cuenta = document.getElementById('cuenta').value; */
            cuenta = document.getElementsByName("cuenta[]");
            /* var titular = document.getElementById('titular').value; */
            titular = document.getElementsByName("titular[]");
            /* var fecha_deposito = document.getElementById('fecha_deposito').value; */
            fecha_deposito = document.getElementsByName("fecha_deposito[]");
            var condicion = document.getElementById('condicion').value;

            var filas_pagos=$(".table_pagos_realizados tbody tr.nohide").length;

            var campo_condicion = $("#condicion").val();
            var inputconciliar=0
            if(campo_condicion=='ABONADO')
            {
              inputconciliar=$(".conciliar_count").length;
              if(inputconciliar==0)
              {
                Swal.fire(
                    'Error',
                    'No existen conciliaciones relacionadas',
                    'warning'
                  )
                  return false;
              }else{

                //$('.conciliar_count').
                var estadovacioconciliar=0;
                $('.conciliar_count').each(function(){
                  if(this.value==0)
                  {
                    estadovacioconciliar=1;
                    return false;
                  }

                });
                if(estadovacioconciliar==1)
                {

                  Swal.fire(
                    'Error',
                    'Faltan conciliar pagos',
                    'warning'
                  )
                  return false;

                }
              }
            }




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
