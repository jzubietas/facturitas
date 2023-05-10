@extends('adminlte::page')

@section('title', 'Lista de notificaciones')

@section('content_header')
    <h1>Lista de notificaciones</h1>
@stop

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Notificaciones sin leer</div>
                    <div class="card-body">

                        @if (auth()->user())
                            @foreach($devoluciones as $notification)
                              @if($notification->status==1)
                                <div class="alert alert-default-warning">
                                  Asunto: <a href="{{route('pagos.show',$notification->pago)}}" target="_blank"
                                             class="text-info">Pago</a>
                                  por devolver a <a href="{{route('clientes.show',$notification->cliente)}}"
                                                    target="_blank"
                                                    class="text-info">{{$notification->cliente->nombre}}</a>
                                  un valor de <b>{{$notification->amount_format}}</b>

                                  <a href="{{route('pagos.devolucion',$notification)}}"
                                     class="ml-4 btn btn-dark">Gestionar</a>

                                  <br>
                                </div>
                              @endif
                              @if($notification->status==2)
                                <div class="alert alert-default-primary">
                                  Asunto: <a href="{{route('pagos.show',$notification->pago)}}" target="_blank"
                                             class="text-info">Pago</a>
                                  <b>Devuelto</b> a <a href="{{route('clientes.show',$notification->cliente)}}"
                                                    target="_blank"
                                                    class="text-info">{{$notification->cliente->nombre}}</a>
                                  un valor de <b>{{$notification->amount_format}}</b>

                                  <a href="{{route('pagos.devolucion',$notification)}}"
                                     class="ml-4 btn btn-dark">Gestionar</a>

                                  <br>
                                </div>
                                @endif
                            @endforeach

                            @forelse ($postNotifications as $notification)
                                <div class="alert alert-default-warning" id="{{$notification->id}}">
                                    Asunto: {{ new \Illuminate\Support\HtmlString($notification->data['asunto']) }} <br>
                                    <hr class="my-2">
                                    {{ $notification->data['tipo'] }} <br>
                                    <hr class="my-1">
                                    Estado: {{ $notification->data['condicion'] }}
                                    <p>{{ $notification->created_at->diffForHumans() }}</p>

                                    @if(isset($notification->data['devolucion_id']))
                                        <a class="btn btm-sm btn-dark" href="{{ route('pagos.devolucion',[$notification->data['devolucion_id'],'read_notification'=>$notification->id]) }}"
                                           id="mark-all">Ver notificacion</a>
                                    @else
                                        <button type="submit" class="mark-as-read btn btn-sm btn-dark"
                                                data-id="{{ $notification->id }}">Marcar como leída
                                        </button>
                                    @endif
                                </div>
                                @if ($loop->last)
                                        <a href="{{ route('markAsRead') }}" id="mark-all">Marcar todas como leídas</a>
                                @endif

                            @empty
                                @if(count($devoluciones)==0)
                                    Usted no notificationes sin leer
                                @endif
                            @endforelse

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/datatables.js') }}"></script>

    @if (session('info') == 'registrado' || session('info') == 'eliminado' || session('info') == 'renovado')
        <script>
            Swal.fire(
                'Pago {{ session('info') }} correctamente',
                '',
                'success'
            )
        </script>
    @endif

    <script>
        function sendMarkRequest(id = null) {
            return $.ajax("{{ route('markNotification') }}", {
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id
                }
            });
        }

        $(function () {
            $('.mark-as-read').click(function () {
                let request = sendMarkRequest($(this).data('id'));
                request.done(() => {
                    $(this).parents('div.alert').remove();
                });
            });
            $('#mark-all').click(function () {
                let request = sendMarkRequest();
                request.done(() => {
                    $('div.alert').remove();
                })
            });
        });
    </script>
@stop
