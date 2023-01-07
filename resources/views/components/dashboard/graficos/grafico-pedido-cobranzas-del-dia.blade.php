<div class="card">
    <div class="card-body">
        <div id="chart-ttm"></div>
    </div>
</div>
@push('js')
    <script>
        (function () {
            $(document).ready(function (){
                var options = {{\Illuminate\Support\Js::from($configChart)}};

                var chart = new ApexCharts(document.querySelector("#chart-ttm"), options);
                chart.render();
            })
        })()
    </script>
@endpush
