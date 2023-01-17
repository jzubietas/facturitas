@foreach($rotulos as $rotulo)
<table>
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
