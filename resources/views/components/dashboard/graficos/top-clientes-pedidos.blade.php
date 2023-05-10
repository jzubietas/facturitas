<div class="card">
    <div class="card-body">
        <div id="{{$genId}}" class="w-100" style="height: {{$height}}px"></div>
    </div>
</div>
@push("js")
    <script>
        (function () {
            window.$widgets = window.$widgets || []

            var options = {{\Illuminate\Support\Js::from($settings)}};
            window.$widgets['{{$genId}}_config'] = options;

            function generateWidget() {
                $("#{{$genId}}").CanvasJSChart(options);
            }

            window.$widgets['{{$genId}}'] = generateWidget;
            $(document).ready(function () {
                generateWidget()
            })
        })()
    </script>

@endpush
