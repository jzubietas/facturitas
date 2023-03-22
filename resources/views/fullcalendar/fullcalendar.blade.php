@extends('adminlte::page')

@section('title', 'Agenda')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('css/toaster.min.css')}}">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/timegrid/main.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/list/main.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

    </style>
    <style>

    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Calendar</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@stop

@section('content')

    @include('fullcalendar.modal.agregar_evento')
    @include('fullcalendar.modal.eliminar_evento')
    @include('fullcalendar.modal.editar_evento')
    @include('fullcalendar.modal.form_evento')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="sticky-top mb-3">

                    {{--Draggable Events--}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Notas para asignar</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div id="external-events" style="margin-bottom:1em; height: 350px; overflow: auto;padding:1em" class="col-md-12">
                                    @foreach($uneventss as $eventunsigned)
                                        <div id="unsigned_{{ $eventunsigned["id"] }}"
                                             class="external-event btn btn-md d-flex justify-content-between rounded {{ $eventunsigned["colorfondo"] }}" data-titulo="{{ $eventunsigned["titulo"] }}"
                                             data-horafin="{{ $eventunsigned["horafin"] }}"
                                             data-horainicio="{{ $eventunsigned["horainicio"] }}"
                                             data-colorfondo="{{ $eventunsigned["colorfondo"] }}"
                                             data-colortexto="{{ $eventunsigned["colortexto"] }}"
                                             data-codigo="{{ $eventunsigned["id"] }}"
                                             style="border-color:{{ $eventunsigned["colorfondo"] }};color:{{ $eventunsigned["colortexto"] }};background-color:{{ $eventunsigned["colorfondo"] }};">
                                            <span clas="">{{ $eventunsigned["titulo"] }}</span>
                                            <span clas="">{{ $eventunsigned["descripcion"] }}</span>
                                            <button type="button" class="btn delete-unsigned-event btn btn-light btn-sm">
                                                <i class="fa fa-close text-danger"></i>
                                            </button>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    {{--Create Event--}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Crear tarea</h3>
                        </div>
                        <div class="card-body">
                            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                <ul class="fc-color-picker" id="color-chooser">
                                    <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                    <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                    <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                    <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                </ul>
                                {{--<input type="text" class="form-control colorpicker-input" value="#007bff">--}}
                                <input type="color" class="form-control form-control-color"  id="color-selector" value="#3c8dbc">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="new-event"></label>
                                        <input id="new-event" type="text" class="form-control" placeholder="Nombre de tarea">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="text-new-event" name="text-new-event"></textarea>
                                    </div>
                                </div>
                                <div class=" col-md-12">
                                    {!! Form::label('inputFilesEventU', 'Adjuntar Archivos') !!}
                                    {!! Form::file('inputFilesEventU[]', ['class' => 'form-control-file','multiple','id'=>'inputFilesEventU','accept'=>".png, .jpg,.jpeg,.pdf, .xlsx , .xls"]) !!}
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button id="add-new-event" type="button" class="btn btn-primary float-right">Agregar</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /btn-group -->
                            <div class="input-group">
                                <div class="input-group-append">
                                </div>
                                <!-- /btn-group -->
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary">
                    <div class="card-body p-0">
                        <div id="calendario1" style="width: 100%;padding:2px"></div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div><!-- /.container-fluid -->
@stop

@section('js')
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src=" {{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/interaction/main.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/timegrid/main.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/list/main.min.js"></script>

    <script src=" {{asset('js/toaster.min.js')}}"></script>
    <script src=" {{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>

    <script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>
    <script src='https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js'></script>

    <script>
        $(document).ready(function () {

            //$(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function ini_events(ele) {
                ele.each(function () {
                    let eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0,  //  original position after the drag
                        stop:function(event,ui)
                        {
                            //console.log(event);
                            //console.log($(ui.helper[0]).attr('id'));//unsigned_2
                            //let eventEliminar = contenerEliminar.attr('id').split('_')[1];
                        }
                    })

                })
            }

            ini_events($('#external-events div.external-event'));

            let agregar_evento_calendario = new bootstrap.Modal(document.getElementById('agregar_evento_calendario'), {keyboard: false})
            let editar_evento_calendario = new bootstrap.Modal(document.getElementById('editar_evento_calendario'), {keyboard: false})
            let eliminar_evento_calendario = new bootstrap.Modal(document.getElementById('eliminar_evento_calendario'), {keyboard: false})
            let form_evento_calendario = new bootstrap.Modal(document.getElementById('FormularioEventos'), {keyboard: false})

            let date = new Date()
            let d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()

            let Calendar = FullCalendar.Calendar;
            let Draggable = FullCalendar.Draggable;

            let containerEl = document.getElementById('external-events');
            //let checkbox = document.getElementById('drop-remove');
            let calendarEl = document.getElementById('calendario1');

            new FullCalendarInteraction.Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        id:eventEl.id,
                        title: eventEl.innerText.trim(),
                        backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                        create:false,
                    }
                }
            });

            $(document).on("change", "#inputFilesEventA", function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picturea").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            $(document).on("change", "#inputFilesEventE", function (event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picturee").setAttribute('src', event.target.result);
                };
                reader.readAsDataURL(file);
            });

            let calendario1 = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'timeGrid', 'interaction'],
                height: 800,
                droppable: true,
                locale: 'es',
                showNonCurrentDates: false,
                header: {
                    left: 'today,prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: true,
                eventColor: 'info',
                events:function(fetchInfo, successCallback, failureCallback) {
                    let formData = new FormData();
                    formData.append('type', 'load')
                    $.ajax({
                        url: "{{ route('fullcalendarAjax') }}",
                        type: 'POST',
                        dataType:'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            console.log(res);
                            let events = [];
                            res.forEach(function (evt) {
                                events.push({
                                    id:evt.id,
                                    description:evt.description,
                                    title: evt.title,
                                    start: evt.start,
                                    end: evt.end,
                                    color:evt.fondoEvento,
                                    textColor:evt.colorEvento,
                                    colorEvento:evt.colorEvento,
                                    fondoEvento:evt.fondoEvento,
                                    tipo:evt.tipo,
                                    frecuencia:evt.frecuencia,
                                    adjunto:evt.adjunto,
                                });
                            });
                            console.log(events);
                            successCallback(events);
                        }
                    });
                },
                dateClick: function(info) {
                    console.log("dateClick")
                    console.log(info.dateStr)
                    limpiarFormulario();
                    $("#calendario_start_evento").val(info.dateStr);
                    $("#calendario_end_evento").val(info.dateStr);
                    $('.btn-edit-check').addClass('d-none');
                    agregar_evento_calendario.show();
                },
                eventClick: function(info) {
                    console.log(info)
                    console.log("eventclick editar en evento")
                    $('#editar_evento_calendario .btn-edit i').removeClass('text-dark').addClass('text-warning');
                    $("#editar_evento").val(info.event.id);
                    $(".fecha_lectura_start").html(moment(info.event.start).format('YYYY-MM-DD hh:mm:ss'));
                    $(".fecha_lectura_end").html(moment(info.event.start).format('YYYY-MM-DD hh:mm:ss'));
                    $('#calendario_nombre_evento_editar').val(info.event.title);
                    $('#calendario_descripcion_evento_editar').val(info.event._def.extendedProps.description);
                    $('#picturee').attr('src',info.event._def.extendedProps.adjunto);
                    editar_evento_calendario.show();
                },
                eventResize: function(info) {
                    console.log("eventresize modificar en evento")
                },
                eventDrop: function(info) {
                    console.log("eventdrop soltar drop")
                },
                drop: function(info) {
                    console.log("drop")
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                    console.log(info.dateStr);
                    let contenerEliminar=info.draggedEl;
                    let eventEliminar = $(contenerEliminar).attr('id').split('_')[1];
                    let color=$(contenerEliminar).data("colorfondo");
                    /*console.log(color);return false;
                    switch(color)
                    {
                        case 'bg-primary':color='rgb(0, 86, 179)';break;
                        case 'bg-warning':color='rgb(186, 139, 0)';break;
                        case 'bg-success':color='rgb(25, 105, 44)';break;
                        case 'bg-danger':color='rgb(167, 29, 42)';break;
                        default:color='rgb(0, 86, 179)';break;
                    }*/

                    let titulo=$(contenerEliminar).data("titulo");
                    let descripcion=$(contenerEliminar).data("descripcion");
                    let start_=info.dateStr;
                    let end_=info.dateStr;
                    console.log(start_);
                    start_=moment(info.dateStr).format('YYYY-MM-DD hh:mm:ss');
                    end_=moment(info.dateStr).format('YYYY-MM-DD hh:mm:ss');

                    let formData = new FormData();
                    formData.append('eliminar_evento', eventEliminar)
                    formData.append('type', 'delete')
                    $.ajax({
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        url: "{{ route('fullcalendarAjaxUnsigned') }}",
                        success: function (data) {
                            let eventDeleteUnsigned = $("#unsigned_"+eventEliminar);
                            eventDeleteUnsigned.fadeOut("normal", function() {
                                $(this).remove();
                            });

                            //aqui registro el evento temporal a evento oficial
                            var formData = new FormData();
                            formData.append('calendario_nombre_evento', titulo);
                            formData.append('calendario_descripcion_evento_nuevo', descripcion);
                            formData.append('calendario_start_evento', start_);
                            formData.append('calendario_color_evento', color);
                            formData.append('colorTexto', color);
                            formData.append('colorBackground', color);
                            formData.append('calendario_end_evento', end_);
                            formData.append('calendario_frecuencia_evento', 'una_vez');
                            formData.append('calendario_tipo_evento', 'OTROS');
                            formData.append('id_unsigned_event', eventEliminar)
                            formData.append('type', 'add');
                            $.ajax({
                                url: "{{route('fullcalendarAjax')}}",
                                data: formData,
                                type: "POST",
                                processData: false,
                                contentType: false,
                                success: function (data) {
                                    agregar_evento_calendario.hide();
                                    displayMessage("Nota creada.");
                                    calendario1.refetchEvents();
                                }
                            });
                        }
                    });
                }
            });

            $(document).on('focus',"input[type=text]",function(){
                this.select()
            })

            function displayMessage(message) {
                toastr.success(message, 'Event');
            }

            calendario1.render();

            let currColor = '#3c8dbc'
            $('#new-event').css('background-color', currColor);
            $('#text-new-event').css('background-color', currColor);
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                // Save color
                currColor = $(this).css('color')
                /*console.log('currColor cuad:',currColor); return false;*/
                // Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                })
                $('#new-event').css('background-color', currColor);
                $('#text-new-event').css('background-color', currColor);

                switch(currColor)
                {
                    case 'rgb(153, 190, 230)':currColor='#007bff';break;
                    case 'rgb(230, 210, 153)':currColor='#ffc107';break;
                    case 'rgb(153, 230, 171)':currColor='#28a745';break;
                    case 'rgb(230, 153, 161)':currColor='#dc3545';break;
                    default:currColor=currColor;break;
                }
                console.log('currColor cuad:',currColor);
                $('#color-selector').val(currColor)

            })

            $('#color-selector').change(function() {
                var color = $(this).val();
                currColor =color;
                $('#new-event').css('background-color', currColor);
                $('#text-new-event').css('background-color', currColor);
            })

            $(document).on('click','.delete-unsigned-event',function(){
                let contenerEliminar=$(this).parents('div');
                let eventEliminar = contenerEliminar.attr('id').split('_')[1];
                console.log(eventEliminar)
                var formData = new FormData();
                formData.append('eliminar_evento', eventEliminar)
                formData.append('type', 'delete')
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('fullcalendarAjaxUnsigned') }}",
                    success: function (data) {
                        //eliminar_evento_calendario.hide();
                        let eventDeleteUnsigned = $("#unsigned_"+eventEliminar);
                        eventDeleteUnsigned.fadeOut("normal", function() {
                            $(this).remove();
                        });
                    }
                });

            });

            $('#add-new-event').click(function (e) {
                e.preventDefault()
                let val = $('#new-event').val()
                let valtext = $('#text-new-event').val()
                var files = document.getElementById('inputFilesEventU').files;
                var valColor = $('#color-selector').val();

                /*return false;*/
                if (val.length === 0) {
                    return
                }
                if (valColor!="#ffffff"){
                    currColor=valColor;
                }
                // Create events
                let event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('external-event btn btn-md rounded d-flex justify-content-between ')

                event.html('<span class="">'+val+'</span>'+
                    '<span>'+valtext+'</span>'+
                    '<button type="button" class="btn delete-unsigned-event btn btn-light btn-sm">'+
                    '<i class="fa fa-close text-danger"></i>'+
                    '</button>'
                )

                if (valColor=="#ffffff"){
                    switch(currColor)
                    {
                        case 'rgb(0, 86, 179)':currColor='bg-primary';break;
                        case 'rgb(186, 139, 0)':currColor='bg-warning';break;
                        case 'rgb(25, 105, 44)':currColor='bg-success';break;
                        case 'rgb(167, 29, 42)':currColor='bg-danger';break;
                        //case 'rgb(0, 123, 255)':currColor='bg-grey';break;
                        default:currColor='bg-info';break;
                    }
                }
                /*console.log('valColor= ',valColor,' currColor=',currColor); return false;*/
                let formUnsigned = new FormData();
                formUnsigned.append('calendario_nombre_evento', val);
                formUnsigned.append('calendario_descripcion_evento', valtext);
                formUnsigned.append('calendario_color_evento', currColor);
                formUnsigned.append('type', 'add');
                for (var i = 0; i < files.length; i++) {
                    formUnsigned.append('inputFilesEventU[]', files[i]);
                }
                $.ajax({
                    url: "{{route('fullcalendarAjaxUnsigned')}}",
                    data: formUnsigned,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        event.addClass(data.color);
                        event.attr('id','unsigned_'+data.id)
                        event.data('titulo',data.title)
                        event.data('descripcion',data.description)
                        event.data('horainicio',data.created_at)
                        event.data('horafin',data.updated_at)
                        event.data('colorfondo',data.color)
                        event.data('colortexto',data.color)
                        event.data('codigo',data.id)

                        $('#external-events').prepend(event)
                    }
                });

                ini_events(event);

                $('#new-event').val('')
                $('#text-new-event').val('')
                $('#new-event').css('background-color', '#3c8dbc');
                $('#text-new-event').css('background-color', '#3c8dbc');
                $('#color-selector').val('#3c8dbc')
                //window.location.reload();
            })

            $(document).on("submit", "#frm_add_evento_calendario", function (event) {
                event.preventDefault();
                var form = $(this)[0];
                var formData = new FormData(form);
                formData.append('type', 'add');
                $.ajax({
                    url: "{{route('fullcalendarAjax')}}",
                    data: formData,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        agregar_evento_calendario.hide();
                        displayMessage("Nota creada.");
                        calendario1.refetchEvents();
                    }
                });
            });

            $(document).on('click','#editar_evento_calendario .btn-edit',function(){
                $('i',this).removeClass('text-warning').addClass('text-dark');
                console.log("aa")
                $('#calendario_nombre_evento_editar').removeClass('border').removeClass('border-0');
                $('#calendario_descripcion_evento_editar').removeClass('border').removeClass('border-0');
                $("#calendario_descripcion_evento_editar").prop('readonly',false);
                $("#calendario_nombre_evento_editar").prop('readonly',false).focus();

                $('.btn-edit-check').removeClass('d-none');
            });

            $(document).on("click", "#frm_editar_evento_calendario .btn-edit-check", function (event) {
                event.preventDefault();
                let eleme=$(this).parents('form').attr('id');
                console.log(eleme);
                var form = $('#'+eleme)[0];
                var formData = new FormData(form);
                formData.append('type', 'modificar')
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('fullcalendarAjax') }}",
                    success: function (data) {
                        editar_evento_calendario.hide();
                        calendario1.refetchEvents();
                        //let eventDelete = calendar.getEventById(formData.get("eliminar_evento"))
                        //eventDelete.remove();
                    }
                });
            });

            $(document).on('click','.btn-delete',function(){
                event.preventDefault();
                let eleme="frm_editar_evento_calendario";
                var form = $('#'+eleme)[0];
                var formData = new FormData(form);
                formData.append('type', 'borrar')
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('fullcalendarAjax') }}",
                    success: function (data) {
                        editar_evento_calendario.hide();
                        calendario1.refetchEvents();
                    }
                });
            })

            $(document).on("submit", "#frm_eliminar_evento_calendario", function (event) {
                event.preventDefault();
                var form = $(this)[0];
                var formData = new FormData(form);
                formData.append('type', 'delete')
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('fullcalendarAjax') }}",
                    success: function (data) {
                        eliminar_evento_calendario.hide();
                        let eventDelete = calendario.getEventById(formData.get("eliminar_evento"))
                        eventDelete.remove();
                    }
                });
            });

            window.agregarEventoPredefinido=function(registro){
                $.ajax({
                    type: 'POST',
                    url: 'datoseventos.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        calendario1.removeAllEvents();
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hay un problema:" + error);
                    }
                });
            }

            window.limpiarFormulario=function(){
                $('#calendario_nombre_evento').val('');
                $('#calendario_start_evento').val('');
                $('#calendario_end_evento').val('');
                $('#calendario_fondo_evento').val('#3788D8');
                $('#calendario_color_evento').val('#ffffff');
            }

        })
    </script>
@endsection

