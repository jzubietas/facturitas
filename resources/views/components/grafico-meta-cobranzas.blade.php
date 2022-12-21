<div class="card">
    <div class="card-body">
        <div id="chart"></div>
    </div>
</div>
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        (function () {
            var options = {{\Illuminate\Support\Js::from( $jsChart)}};

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        })()
    </script>
@endpush
