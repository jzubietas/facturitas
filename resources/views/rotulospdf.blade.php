<html>
<head>
    <meta charset="utf-8">
    <style>
        .header,
        .footer {
            width: 100%;
            text-align: center;
            /*position: absolute;*/
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

        .page {
            /*position: relative;*/
            height: 20cm;
        }

        @page {
            size: 29.7cm 21cm ;
            margin: 1mm 1mm 4mm 10mm;
            /* change the margins as you want them to be. */
        }
    </style>
</head>
<body onload="window.print()">
@foreach($rotulos as $rotulo)
    <div class="page" style="@if($loop->index==1)
page-break-after: always;
    @elseif($loop->index>1 && $loop->index<count($rotulos)-1)
    page-break-after: always;
@endif">
        <table style="width: 100%; max-width: 600px;">
            <tr>
                <td colspan="2">
                    <img src="{{$rotulo['file']}}">
                </td>
                <td>

                    <table style="width: 100%;padding-right: 15mm">
                        @foreach(collect($rotulo['codigos'])->reverse()->chunk(2)->reverse() as $grupos)
                            <tr>
                                @foreach($grupos as $item)
                                    <td style="text-align: right;">
                                        <b>{{$item}}</b><br>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
        <div class="footer" style="text-align: left; padding-left: 8mm; width:100%; max-width: 480px; white-space: normal">
            <ul>
                @foreach($rotulo['producto'] as $grupos)
                    <li syle="font-size:20px;">{{$grupos}}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach
</body>
</html>
