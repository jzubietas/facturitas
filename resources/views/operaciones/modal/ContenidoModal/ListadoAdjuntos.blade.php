@if(isset($imagenesasesores))
<input type="hidden" id="adjunto_total_attachment" value="{{count($imagenesasesores)}}">
<h5>Documentos</h5>
<table>
    @foreach($imagenesasesores as $img)
        <tr class="adjuntos" data-adjunto="{{ $img->filename }}">
            <td>
                <a target="_blank" download
                   href="{{ \Storage::disk('pstorage')->url( $img->filepath) }}">
                    <span class="text-primary">{{ $img->filename }}</span>
                </a>
            </td>
            <td>
                <a href="#" style="margin-left: 12px;" class="d-none" data-imgid="{{ $img->filename }}"
                   data-imgadjunto="{{ $img->filename }}"></a>
            </td>
        </tr>
    @endforeach
</table>
@endif

@if(isset($imagenesrespases))
    <input type="hidden" id="adjunto_total_attachment2" value="{{count($imagenesrespases)}}">
    <h5>Capturas</h5>
    <table>
        @foreach($imagenesrespases as $img2)
            <tr class="adjuntos" data-adjunto="{{ $img2->filename }}">
                <td>
                    <a target="_blank" download
                       href="{{ \Storage::disk('pstorage')->url( $img2->filepath) }}">
                        <span class="text-primary">{{ $img2->filename }}</span>
                    </a>
                </td>
                <td>
                    <a href="#" style="margin-left: 12px;" class="d-none" data-imgid="{{ $img2->filename }}"
                       data-imgadjunto="{{ $img2->filename }}"></a>
                </td>
            </tr>
        @endforeach
    </table>
@endif


