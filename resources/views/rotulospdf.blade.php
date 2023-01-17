@foreach($rotulos as $rotulo)
<table style="@if($loop->index==1)
page-break-before: always;
page-break-after: always;
    @elseif($loop->index>1)
    page-break-after: always;
@endif">
    <tr>
        <td>
            {{join(',',$rotulo['codigos'])}}
        </td>
    </tr>
    <tr>
        <td>
            <img src="{{$rotulo['file']}}">
        </td>
    </tr>
    <tr>
        <td>
            {{join(',',$rotulo['producto'])}}
        </td>
    </tr>
</table>
@endforeach
