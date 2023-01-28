var tabla_historial_cliente = null;

var modal_1 = null;

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //btn_componente-1
    $('#modal-annuncient-1').on('show.bs.modal', function (event) {
        console.log("aaa")
        //
        $("#opciones_modal1")
            .html("")
            .append( $('<option/>').attr({ 'value': 'op1_modal1' }).text('Base fria y referido') )
            .append( $('<option/>').attr({ 'value': 'op2_modal1' }).text('Autorizacion para subir pedido') )
            .append( $('<option/>').attr({ 'value': 'op3_modal1' }).text('Eliminar Pago') )
            .selectpicker("refresh")
        //var button = $(event.relatedTarget)
        //var idunico = button.data('delete')

    })

});
