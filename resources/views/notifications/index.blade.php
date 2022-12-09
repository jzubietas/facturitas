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
                            {{--
                            @foreach($devoluciones as $notification)
                                <div class="alert alert-default-warning">
                                    Asunto: Pago por devolver a {{$notification->cliente->nombre}} <br>

                                </div>
                                @if ($loop->last)
                                    <a href="{{ route('markAsRead') }}" id="mark-all">Marcar todas como leídas</a>
                                @endif
                            @endforeach
                            --}}
                        @forelse ($postNotifications as $notification)
                                <div class="alert alert-default-warning">
                                    Asunto: {{ $notification->data['asunto'] }} <br>
                                    {{ $notification->data['tipo'] }} <br>
                                    Estado: {{ $notification->data['condicion'] }}
                                    <p>{{ $notification->created_at->diffForHumans() }}</p>
                                    <button type="submit" class="mark-as-read btn btn-sm btn-dark"
                                            data-id="{{ $notification->id }}">Marcar como leída
                                    </button>
                                </div>
                                @if ($loop->last)
                                    <a href="{{ route('markAsRead') }}" id="mark-all">Marcar todas como leídas</a>
                                @endif

                            @empty
                                Usted no notificationes sin leer
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
