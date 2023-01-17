<html>
<head>
    <meta charset="utf-8">
    <style>
        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: absolute;
        }

        .header {
            top: 0px;
        }

        .footer {
            bottom: 0px;
        }

        .pagenum:before {
            content: counter(page);
        }
        .page{
            position: relative;
            height: 27cm;
        }
        @page {
            size: 21cm 29.7cm;
            margin: 7mm 7mm 7mm 7mm;
            /* change the margins as you want them to be. */
        }
    </style>
</head>
<body onload="window.print()">
@foreach($rotulos as $rotulo)
    <div class="page" style="@if($loop->index==1)
page-break-before: always;
page-break-after: always;
    @elseif($loop->index>1)
    page-break-after: always;
@endif">
        <table style="width: 100%">
            <tr>
                <td style="width: 50%;">

                </td>
                <td style="width: 50%;">

                    <table style="width: 100%">
                        @foreach(collect($rotulo['codigos'])->chunk(2) as $grupos)
                            <tr>
                                @foreach($grupos as $item)
                                    <td style="text-align: right;">
                                        <b>{{$item}}</b>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <img src="{{$rotulo['file']}}">
                </td>
            </tr>
        </table>
        <div class="footer" style="text-align: right">
            @foreach($rotulo['producto'] as $grupos)
                <b>{{$grupos}}</b><br>
            @endforeach
        </div>
    </div>
@endforeach
</body>
</html>
