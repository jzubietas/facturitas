@extends('adminlte::page')

@section('title', 'Devoluciones')

@section('content_header')
    <h1>Devolver Pago a <b>{{$devolucion->cliente->nombre}}</b></h1>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-4">
                    @if(
    $devolucion->status != \App\Models\Devolucion::DEVUELTO&& auth()->user()->rol==\App\Models\User::ROL_ADMIN)
                        <div class="card-body">
                            {{Form::model($devolucion,['route'=>['pagos.devolucion.update',$devolucion],'files'=>true])}}
                            <div>
                                <ul class="list-group">
                                    <li class="list-group-item">Monto a devolver: <b>{{$devolucion->amount_format}}</b>
                                    </li>
                                    <li class="list-group-item">Cliente: <b>{{$devolucion->cliente->nombre}}</b></li>
                                    <li class="list-group-item">Banco: <b>{{$devolucion->bank_destino}}</b></li>
                                    <li class="list-group-item">N° de cuenta: <b>{{$devolucion->bank_number}}</b></li>
                                    <li class="list-group-item">Titular: <b>{{$devolucion->bank_titular}}</b></li>
                                    {{--
                                    <li class="list-group-item">
                                        <div class="form-group col-lg-12">
                                            {!! Form::label('num_operacion', 'Numero de operacion del voucher') !!}
                                            {!! Form::input('text','num_operacion', null , ['class' => 'form-control','required'=>'required']) !!}
                                        </div>
                                    </li>
                                    --}}
                                    <li class="list-group-item">
                                        <b></b>
                                        <br>
                                        <div class="form-group col-lg-12">
                                            {!! Form::label('voucher', 'Adjuntar Constancia') !!}
                                            {!! Form::file('voucher', ['class' => 'form-control','accept'=>'image/*','required'=>'required']) !!}
                                        </div>
                                        <div>
                                            <img id="voucher_img" style="display: none;max-width: 450px">
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="flex d-flex justify-content-between">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-save"></i>
                                                Marcar pago devuelto
                                            </button>
                                            <a href="{{route('pagos.show',$devolucion->pago)}}#section_devoluciones"
                                               target="_blank" class="btn btn-info">
                                                <i class="fa fa-link"></i>
                                                Ver Pago</a>
                                        </div>
                                    </li>
                                </ul>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            {{Form::close()}}
                        </div>
                    @else
                    <input type="hidden" name="devolucion_id" id="devolucion_id" value="{{$devolucion->id}}">
                        <div class="card-body">
                            <div>
                                <ul class="list-group">
                                    <li class="list-group-item">Monto a devolver: <b>{{$devolucion->amount_format}}</b>
                                    </li>
                                    <li class="list-group-item">Cliente: <b>{{$devolucion->cliente->nombre}}</b></li>
                                    <li class="list-group-item">Estado: <b class="text-{{$devolucion->estado_color}}">{{$devolucion->estado_text}}</b></li>
                                    <li class="list-group-item">Banco: <b>{{$devolucion->bank_destino}}</b></li>
                                    <li class="list-group-item">N° de cuenta: <b>{{$devolucion->bank_number}}</b></li>
                                    <li class="list-group-item">Titular: <b>{{$devolucion->bank_titular}}</b></li>
                                    <li class="list-group-item">
                                        <div>
                                            <img id="voucher_img"
                                                 src="{{ Storage::disk($devolucion->voucher_disk)->url($devolucion->voucher_path) }}"
                                                 style="max-width: 450px">
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div>
                                          <!-- (Condition) ? trueStatement : falseStatement -->
                                          <button class="btn btn-dark btnDescargaDevolucion" >
                                            <i class="fas fa-file-download"></i> Marcar como leído </button>

                                            <a href="{{route('pagos.show',$devolucion->pago)}}#section_devoluciones"
                                               target="_blank" class="btn btn-info">
                                                <i class="fa fa-link"></i>
                                                Ver Pago</a>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                Cliente <b>{{$devolucion->cliente->nombre}}</b>
                            </div>

                            <div class="card-body">
                                <div>
                                    <ul class="list-group">
                                        <li class="list-group-item">DNI: <b>{{$devolucion->cliente->dni}}</b></li>
                                        <li class="list-group-item">Celular: <b>{{$devolucion->cliente->celular}}</b>
                                        </li>
                                        <li class="list-group-item">Provincia:
                                            <b>{{$devolucion->cliente->provincia}}</b></li>
                                        <li class="list-group-item">Distrito: <b>{{$devolucion->cliente->distrito}}</b>
                                        </li>
                                        <li class="list-group-item">Dirección:
                                            <b>{{$devolucion->cliente->direccion}}</b></li>
                                        <li class="list-group-item">Referencia:
                                            <b>{{$devolucion->cliente->referencia}}</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div class="border rounded card-body border-secondary">
                            <div class="form-row">
                                <div class="form-group col-lg-12">
                                    <h3>PEDIDOS</h3>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">ITEM</th>
                                            <th scope="col">PEDIDO</th>
                                            <th scope="col">CODIGO</th>
                                            <th scope="col">ESTADO DE PAGO</th>
                                            <th scope="col">MONTO TOTAL</th>
                                            <th scope="col">ABONADO</th>
                                            <th scope="col"><span style="color:red;">DIFERENCIA</span></th>
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
                                                <td>PED000{{ $pagoPedido->pedidos }}</td>
                                                <td>{{ $pagoPedido->codigo }}</td>

                                                @if($devolucion->pago->condicion==\App\Models\Pago::ABONADO)
                                                    @if($pagoPedido->pagado == 1)
                                                        <td>ADELANTO ABONADO</td>
                                                    @else
                                                        <td>PAGADO ABONADO</td>
                                                    @endif
                                                @elseif($devolucion->pago->condicion==\App\Models\Pago::OBSERVADO)
                                                    @if($pagoPedido->pagado == 1)
                                                        <td>ADELANTO OBSERVADO</td>
                                                    @else
                                                        <td>PAGADO OBSERVADO</td>
                                                    @endif
                                                @elseif($devolucion->pago->condicion==\App\Models\Pago::PAGO)
                                                    @if($pagoPedido->pagado == 1)
                                                        <td>ADELANTO PAGO</td>
                                                    @else
                                                        <td>PAGADO PAGO</td>
                                                    @endif
                                                @endif

                                                <td>{{ $pagoPedido->condicion }}</td>
                                                <td>{{ $pagoPedido->total }}</td>
                                                <td style='font-weight: bolder;'>{{ $pagoPedido->abono }}</td>
                                                @if ($pagoPedido->total - $pagoPedido->abono < 3)
                                                    <td><span
                                                            style="color:black;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span>
                                                    </td>
                                                @else
                                                    <td><span
                                                            style="color:red;">{{ number_format($pagoPedido->total - $pagoPedido->abono, 2, '.', ' ') }}</span>
                                                    </td>
                                                @endif

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
                                            <td><?php echo number_format($sumPe, 2, '.', ' ') ?></td>
                                            <td><span
                                                    style="color:red;"><?php echo number_format($sumPe2, 2, '.', ' ') ?></span>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="border rounded card-body border-secondary">
                            <div class="form-row">
                                <div class="form-group col-lg-12">
                                    <h3>PAGOS @if($devolucion->pago->saldo>0)
                                            - SALDO A FAVOR DEL CLIENTE: {{ $devolucion->pago->saldo }}
                                        @endif</h3>
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
                                                <th scope="col">OBSERVACION</th>
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
                                                    <td>PAG000{{ $detallePago->id }}</td>
                                                    <td>{{ $detallePago->banco }}</td>
                                                    <td>@php echo number_format($detallePago->monto,2) @endphp</td>
                                                    <td>{{ $detallePago->fecha }}</td>
                                                    <td>{{ $detallePago->cuenta }}</td>
                                                    <td>{{ $detallePago->titular }}</td>
                                                    <td>{{ $detallePago->fecha_deposito }}</td>
                                                    <td>{{ $detallePago->observacion }}</td>
                                                    <td><a href="" data-target="#modal-imagen-{{ $detallePago->id }}"
                                                           data-toggle="modal">
                                                            <img
                                                                src="{{ asset('storage/pagos/' . $detallePago->imagen) }}"
                                                                alt="{{ $detallePago->imagen }}" height="200px"
                                                                width="200px"
                                                                class="img-thumbnail"></a>
                                                        <p><br><a
                                                                href="{{ route('pagos.descargarimagen', $detallePago->imagen) }}">
                                                                <button type="button" class="btn btn-secondary">
                                                                    Descargar
                                                                </button>
                                                            </a></p>
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
                                            <th style="text-align: center">TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th><h4><?php echo number_format($sumPa, 2, '.', ' ') ?></h4></th>
                                            <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script>
      $(document).ready(function () {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        console.log('OFDSODSFJOFSDKSDKL');
        /*document.getElementById('voucher').onchange = evt => {
            const [file] = evt.target.files
            if (file) {
                document.getElementById('voucher_img').src = URL.createObjectURL(file)
                document.getElementById('voucher_img').style.display = 'block'
            }
        }*/
        $(document).on("change", "#voucher", function (evt) {
          const [file] = evt.target.files
          if (file) {
            $('#voucher_img').attr('src',URL.createObjectURL(file));
            $('#voucher_img').css('display','block');
          }
        })

        $(document).on("click", ".btnDescargaDevolucion", function () {
          var devolucion_id=$('#devolucion_id').val();
          var formDev = new FormData();
          formDev.append('devolucion_id',devolucion_id);
          $.ajax({
            processData: false,
            contentType: false,
            data: formDev,
            type: 'POST',
            url: "{{ route('descargaDevolucion') }}",
            success: function (data) {
              console.log(data);

              Swal.fire('Alerta', 'Se procedio a marcar como leido correctamente.', 'success');
            }
          });

        })
      })
    </script>
@endsection

