<div class="text-center mb-4" style="font-family:'Times New Roman', Times, serif">
    <h2>
        <p>
            Bienvenido <b>{{ Auth::user()->name }}</b> al software empresarial de Ojo Celeste, eres el
            <b>{{ Auth::user()->rol }} del sistema</b>
        </p>
    </h2>

    {{--    <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog- modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img alt="Dia de la mujer" src="{{ asset('/img/diaMujer.jpg') }}" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin Modal -->--}}
</div>

<div class="row">
    @include('dashboard.widgets.buscar_cliente')
</div>
{{-- @include('dashboard.modal.alerta') --}}

@section('js-datatables')
    <script>
        $('#exampleModalCenter').modal('show');
    </script>
@endsection
