@extends('adminlte::page')
@section('title', 'Estado del pedidos')
@section('content_header')
    <h1>Estado del pedidos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">

            <table id="tablaPrincipal" class="table table-striped">{{-- display nowrap  --}}
                <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Código</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Razón social</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Asesor</th>
                    <th scope="col">RUC</th>
                    <th scope="col">F. Registro</th>
                    <th scope="col">F. Actualizacion</th>
                    <th scope="col">Total (S/)</th>
                    <!--<th scope="col">Est. pedido</th> -->

                    <th scope="col">Est. pago</th>
                    <th scope="col">Con. pago</th>
                    <!--   <th scope="col">Est. sobre</th> -->
                    <th scope="col">Est. Sobre</th>
                    <!--  <th scope="col">Cond. Pago</th> -->
                    <!-- <th scope="col">Estado</th>-->
                    <th scope="col">Diferencia</th>
                    {{--<th scope="col">Resp. Pedido</th>--}}
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    @if (session('info') == 'registrado')
        <script>
            Swal.fire(
                'RUC {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@stop
