@foreach($rotulos as $rotulo)
    <table style="@if($loop->index==1)
page-break-before: always;
page-break-after: always;
    @elseif($loop->index>1)
    page-break-after: always;
@endif">
        <tr>
            <td>

            </td>
            <td>
                <table style="width: 100%">
                    @foreach(collect($rotulo['codigos'])->chunk(2) as $grupos)
                        <tr>
                            @foreach($grupos as $item)
                                <td>
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
        <tr>
            <td>
            </td>
            <td>
                <table style="width: 100%">
                    @foreach(collect($rotulo['producto'])->chunk(2) as $grupos)
                        <tr>
                            @foreach($grupos as $item)
                                <td>
                                    <b>{{$item}}</b>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
@endforeach
