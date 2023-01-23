<input type="hidden" id="adjunto_total_attachment" value="{{count($imagenes)}}">

<table>
    @foreach($imagenes as $img)
        <tr class="adjuntos" data-adjunto="{{ $img->adjunto }}">
            <td>
                <a target="_blank" download
                   href="{{ \Storage::disk('pstorage')->url('adjuntos/'. $img->adjunto) }}">
                    <span class="text-primary">{{ $img->adjunto }}</span>
                </a>
            </td>
            <td>
                <a href="#" style="margin-left: 12px;" class="d-none" data-imgid="{{ $img->pedido_id }}"
                   data-imgadjunto="{{ $img->adjunto }}"></a>
            </td>
        </tr>
    @endforeach
</table>

