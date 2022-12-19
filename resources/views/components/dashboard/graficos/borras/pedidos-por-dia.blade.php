<div class="card">
    <div class="card-body">
        <div id="{{$genId}}" class="w-100" style="height: {{$height}}px"></div>
    </div>
</div>
@push("js")
    <script>
        (function () {
            window.$widgets = window.$widgets || []

            function generateWidget() {
                var options = {
                    animationEnabled: true,
                    title: {
                        text: "{{$title}}"
                    },
                    axisY: {
                        title: "{{$labelY}}",
                        suffix: ""
                    },
                    axisX: {
                        title: "{{$labelX}}",
                        prefix: "Asesor "
                    },
                    data: [{
                        type: "column",
                        //yValueFormatString: "#,##0.0#"%"",
                        dataPoints: {{\Illuminate\Support\Js::from($dataChart)}}
                    }]
                };

                $("#{{$genId}}").CanvasJSChart(options);
            }
            window.$widgets['{{$genId}}'] = generateWidget;
            $(document).ready(function () {
                generateWidget()
            })
        })()
    </script>

@endpush
