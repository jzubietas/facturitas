
$('#modalcorreccion-corregir').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    console.log((button.data('correccion')))
    $("#confirmacion").val(button.data('correccion'))
    idunico=button.data('correccion');

    $.ajax({
        url: "{{ route('operaciones.cargarimagenes.correccion',':id') }}".replace(':id', idunico),
        data: idunico,
        method: 'POST',
        success: function (data) {
            console.log(data)
            console.log("obtuve las imagenes atencion del pedido " + idunico)
            $('#listado_adjuntos').html("");
            $('#listado_adjuntos_antes').html(data);
        }
    });
})

$(document).on("click", "#cerrarmodalatender", function (evento) {
    evento.preventDefault();
    console.log("no atender")
    var fd = new FormData();
    fd.append('hiddenAtender', $("#hiddenAtender").val());
    $.ajax({
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
        url: "{{ route('operaciones.atenderiddismiss') }}",
        success: function (data) {
            console.log(data);
            $("#modal-editar-atencion .textcode").text('');
            $("#modal-editar-atencion").modal("hide");
            $('#tablaPrincipal').DataTable().ajax.reload();
        }
    });
});

$(document).on("submit","#formcorreccion_corregir",function(event) {
    event.preventDefault();
    var formData = new FormData();
    formData.append("corregir", $("#corregir").val())
    $.ajax({
        type: 'POST',
        url: "{{ route('correccionconfirmacionRequest.post') }}",
        data: formData,
        processData: false,
        contentType: false,
    }).done(function (data) {
        $("#modalcorreccion-confirmacion").modal("hide");
        $('#tablaPrincipal').DataTable().ajax.reload();
    }).fail(function (err, error, errMsg) {
        console.log(arguments, err, errMsg)
    });
});
