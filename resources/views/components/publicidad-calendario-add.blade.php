@push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush
<div class="card">
    <div class="card-header">
        <div class="card-title">Publicidad agregar calendario</div>
    </div>
    <div class="card-body">

        <form id="formpublicidadcalendarioadd" name="formpublicidadcalendarioadd">

            <div class="row">

            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        {!! Form::Label('item_id', 'Item:') !!}
                        {!! Form::select('item_id', $publicidad, '', ['class' => 'form-control select2']) !!}
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {!! Form::Label('CalendarDateTime', 'Fecha:') !!}
                        <input type="text" value="" id="CalendarDateTime" name="CalendarDateTime" class="form-control">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        {!! Form::Label('amount', 'Cantidad:') !!}
                        {!! Form::input('number', 'amount', null, ['class' => 'form-control number']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>

        </form>



    </div>
    <!-- An unexamined life is not worth living. - Socrates -->

</div>

@push('js')
    <!-- Include Bootstrap Datepicker -->

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $("#CalendarDateTime").datepicker({});
            //$('#CalendarDateTime').datetimepicker();

            $('#item_id').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $("#modal-publicidad-calendario-add")
            });

            $(".number").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
                {
                    //$("#errmsg").html("Number Only").stop().show().fadeOut("slow");
                    return false;
                }
            });

            $(document).on("submit","#formpublicidadcalendarioadd",function(event) {
                event.preventDefault();
                let formData = new FormData();
                //let formData = $("#formpublicidadcalendarioadd").serialize();
                formData.append('item_id',$('#item_id').val());
                formData.append('CalendarDateTime',$('#CalendarDateTime').val());
                formData.append('amount',$('#amount').val());
                console.log(formData);

                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('dashboard.publicidad.calendario.add') }}",
                    success: function (data) {
                        console.log("grabado");
                        $("#modal-publicidad-calendario-add").modal("hide");
                    }
                })

            });
        });
    </script>
@endpush
