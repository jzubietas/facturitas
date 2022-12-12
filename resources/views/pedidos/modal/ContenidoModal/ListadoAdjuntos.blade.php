<table>
    @foreach($imagenes as $img)
        @if ($img->pedido_id == $pedido->id)
            <tr class="adjuntos" data-adjunto="{{ $img->adjunto }}">
                <td><a href="{{ route('pedidos.descargaradjunto', $img->adjunto) }}">{{ $img->adjunto }}</a></td>
                <td><a href="#" style="margin-left: 12px;" data-imgid="{{ $img->pedido_id }}" data-imgadjunto="{{ $img->adjunto }}">
                        <button class="btn-delete-adjunto btn btn-danger btn-sm" data-imgid="{{ $img->pedido_id }}" data-imgadjunto="{{ $img->adjunto }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </a>
                </td>
            </tr>
        @endif
        @include('pedidos.modal.DeleteAdjuntoid')
    @endforeach
</table>

