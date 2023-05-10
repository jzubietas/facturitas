<div class="card">
    <div class="card-body">
        <div id="{{$genId}}"></div>
    </div>
</div>
@push('js')
    <script>
        (function () {
            $(document).ready(function () {
                var options = {{\Illuminate\Support\Js::from( $jsChart)}};

                var chart = new ApexCharts(document.querySelector("#{{$genId}}"), options);
                chart.render();
            })
        })()
    </script>
@endpush
