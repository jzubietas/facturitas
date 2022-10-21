<a href="#" data-target="#modal-convertir-{{ $row->id }}" data-toggle="modal" class="edit btn btn-primary btn-sm"><button class="btn btn-info btn-sm">Convertir a cliente</button></a>
<a href="{{ route('clientes.editbf', $row) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
<!--<a href="#" class="edit btn btn-danger btn-sm">Eliminar</a>-->

<a href="" data-target="#modal-delete-{{ $row->id }}" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>'
       