@if(isset($imagenesasesores))
<input type="hidden" id="adjunto_total_attachment" value="{{count($imagenesasesores)}}">
<h5>Imagenes Asesores</h5>
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

@if(isset($imagenesencargad))
    <input type="hidden" id="adjunto_total_attachment2" value="{{count($imagenesencargad)}}">
    <h5>Imagenes Encargado</h5>
    <table>
        @foreach($imagenesencargad as $img2)
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

@if(isset($imagenesadminist))
<input type="hidden" id="adjunto_total_attachment3" value="{{count($imagenesadminist)}}">
<h5>Imagenes Administrador</h5>
<table>
    @foreach($imagenesadminist as $img3)
        <tr class="adjuntos" data-adjunto="{{ $img3->filename }}">
            <td>
                <a target="_blank" download
                   href="{{ \Storage::disk('pstorage')->url( $img3->filepath) }}">
                    <span class="text-primary">{{ $img3->filename }}</span>
                </a>
            </td>
            <td>
                <a href="#" style="margin-left: 12px;" class="d-none" data-imgid="{{ $img3->filename }}"
                   data-imgadjunto="{{ $img3->filename }}"></a>
            </td>
        </tr>
    @endforeach
</table>
@endif
