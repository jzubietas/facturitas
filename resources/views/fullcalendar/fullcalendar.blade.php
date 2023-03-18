@extends('adminlte::page')

@section('title', 'Pedidos - Bandeja de pedidos')

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
        /*.fc .fc-col-header-cell-cushion {
            display: inline-block;
            padding: 2px 4px;
        }*/
        /*#calendar {
            max-width: 900px;
            margin: 40px auto;
            background-color: #0c84ff !important;
        }*/
    </style>
    <style>

        /*
        i wish this required CSS was better documented :(
        https://github.com/FezVrasta/popper.js/issues/674
        derived from this CSS on this page: https://popper.js.org/tooltip-examples.html
        */

        .popper,
        .tooltip {
            position: absolute;
            z-index: 9999;
            background: #FFC107;
            color: black;
            width: 150px;
            border-radius: 3px;
            box-shadow: 0 0 2px rgba(0,0,0,0.5);
            padding: 10px;
            text-align: center;
        }
        .style5 .tooltip {
            background: #1E252B;
            color: #FFFFFF;
            max-width: 200px;
            width: auto;
            font-size: .8rem;
            padding: .5em 1em;
        }
        .popper .popper__arrow,
        .tooltip .tooltip-arrow {
            width: 0;
            height: 0;
            border-style: solid;
            position: absolute;
            margin: 5px;
        }

        .tooltip .tooltip-arrow,
        .popper .popper__arrow {
            border-color: #FFC107;
        }
        .style5 .tooltip .tooltip-arrow {
            border-color: #1E252B;
        }
        .popper[x-placement^="top"],
        .tooltip[x-placement^="top"] {
            margin-bottom: 5px;
        }
        .popper[x-placement^="top"] .popper__arrow,
        .tooltip[x-placement^="top"] .tooltip-arrow {
            border-width: 5px 5px 0 5px;
            border-left-color: transparent;
            border-right-color: transparent;
            border-bottom-color: transparent;
            bottom: -5px;
            left: calc(50% - 5px);
            margin-top: 0;
            margin-bottom: 0;
        }
        .popper[x-placement^="bottom"],
        .tooltip[x-placement^="bottom"] {
            margin-top: 5px;
        }
        .tooltip[x-placement^="bottom"] .tooltip-arrow,
        .popper[x-placement^="bottom"] .popper__arrow {
            border-width: 0 5px 5px 5px;
            border-left-color: transparent;
            border-right-color: transparent;
            border-top-color: transparent;
            top: -5px;
            left: calc(50% - 5px);
            margin-top: 0;
            margin-bottom: 0;
        }
        .tooltip[x-placement^="right"],
        .popper[x-placement^="right"] {
            margin-left: 5px;
        }
        .popper[x-placement^="right"] .popper__arrow,
        .tooltip[x-placement^="right"] .tooltip-arrow {
            border-width: 5px 5px 5px 0;
            border-left-color: transparent;
            border-top-color: transparent;
            border-bottom-color: transparent;
            left: -5px;
            top: calc(50% - 5px);
            margin-left: 0;
            margin-right: 0;
        }
        .popper[x-placement^="left"],
        .tooltip[x-placement^="left"] {
            margin-right: 5px;
        }
        .popper[x-placement^="left"] .popper__arrow,
        .tooltip[x-placement^="left"] .tooltip-arrow {
            border-width: 5px 0 5px 5px;
            border-top-color: transparent;
            border-right-color: transparent;
            border-bottom-color: transparent;
            right: -5px;
            top: calc(50% - 5px);
            margin-left: 0;
            margin-right: 0;
        }

        .btn-custon-calendario:hover {
            text-decoration: none !important;
        }

        .bg-transparent{;
            background-color: transparent !important;
        }

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="sticky-top mb-3">

                    {{--Draggable Events--}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Eventos para asignar</h4>
                        </div>
                        <div class="card-body">
                            <!-- the events -->
                            <div id='external-events' style="margin-bottom:1em; height: 350px; border: 1px solid #000; overflow: auto;padding:1em">
                                <h4 class="text-center">Eventos predefinidos</h4>
                                <div id='listaeventospredefinidos'>

                                </div>
                                @foreach($all_eventsunsigned as $eventunsigned)
                                    <div id="unsigned_{{ $eventunsigned->id }}"
                                         class="fc-event" data-titulo="{{ $eventunsigned->titulo }}"
                                         data-horafin="{{ $eventunsigned->horafin }}"
                                         data-horainicio="{{ $eventunsigned->horainicio }}"
                                         data-colorfondo="{{ $eventunsigned->colorfondo }}"
                                         data-colortexto="{{ $eventunsigned->colortexto }}"
                                         data-codigo="{{ $eventunsigned->codigo }}"
                                         style="border-color:{{ $eventunsigned->colorfondo }};color:{{ $eventunsigned->colortexto }};background-color:{{ $eventunsigned->colorfondo }};margin:10px">
                                        {{ $eventunsigned->titulo }}  [" . {{substr($eventunsigned->horainicio, 0, 5)}} . " a " . {{substr($eventunsigned->horafin, 0, 5)}} . "]
                                        <button type="button" class="delete-unsigned-event bg-white btn btn-custon-calendario btn-light float-right">
                                            <i class="fa fa-close text-danger"></i>
                                        </button>
                                    </div>
                                @endforeach

                                <div style="text-align:center">
                                    <button type="button" id="BotonEventosPredefinidos" class="btn btn-success">
                                        Administrar eventos predefinidos
                                    </button>
                                </div>

                                <div class="checkbox d-none">
                                    <label for="drop-remove">
                                        <input type="checkbox" id="drop-remove">
                                        remove after drop
                                    </label>
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
                                    <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                                </ul>
                            </div>
                            <!-- /btn-group -->
                            <div class="input-group">
                                <label for="new-event"></label>
                                <input id="new-event" type="text" class="form-control" placeholder="Nombre de tarea">

                                <div class="input-group-append">
                                    <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
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
                        <!-- THE CALENDAR -->
                        <div id="calendario1" style="width: 100%;border: 1px solid #000;padding:2px"></div>
                        @include('fullcalendar.modal.agregar_evento')
                        @include('fullcalendar.modal.eliminar_evento')
                        @include('fullcalendar.modal.editar_evento')


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
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
    <script src=" {{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>

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


            /*let agregar_evento_calendario = new bootstrap.Modal(document.getElementById('agregar_evento_calendario'), {
                keyboard: false
            })*/
            /*let eliminar_evento_calendario = new bootstrap.Modal(document.getElementById('eliminar_evento_calendario'), {
                keyboard: false
            })*

            /*let editar_evento_calendario = new bootstrap.Modal(document.getElementById('editar_evento_calendario'), {
                keyboard: false
            })*/

            //agregar_evento_calendario.addEventListener('hidden.bs.modal', function (event) {
            // do something...
            //console.log("465")
            //});

            //eliminar_evento_calendario.addEventListener('hidden.bs.modal', function (event) {
            // do something...
            //console.log("123")
            //});



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
                itemSelector: '.fc-event',
                eventData: function(eventEl) {
                    return {
                        id:eventEl.id,
                        title: eventEl.innerText.trim(),
                        backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                    }
                }
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
                events: @json($eventss),
                dateClick: function(info) {

                    //agregar_evento_calendario.show()

                    /*limpiarFormulario();
                    $('#BotonAgregar').show();
                    $('#BotonModificar').hide();
                    $('#BotonBorrar').hide();
                    if (info.allDay) {
                        $('#FechaInicio').val(info.dateStr);
                        $('#FechaFin').val(info.dateStr);
                    } else {
                        let fechaHora = info.dateStr.split("T");
                        $('#FechaInicio').val(fechaHora[0]);
                        $('#FechaFin').val(fechaHora[0]);
                        $('#HoraInicio').val(fechaHora[1].substring(0, 5));
                    }
                    $("#FormularioEventos").modal();*/
                },
                eventClick: function(info) {
                    /*$('#BotonModificar').show();
                    $('#BotonBorrar').show();
                    $('#BotonAgregar').hide();
                    $('#Codigo').val(info.event.id);
                    $('#Titulo').val(info.event.title);
                    $('#Descripcion').val(info.event.extendedProps.descripcion);
                    $('#FechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                    $('#FechaFin').val(moment(info.event.end).format("YYYY-MM-DD"));
                    $('#HoraInicio').val(moment(info.event.start).format("HH:mm"));
                    $('#HoraFin').val(moment(info.event.end).format("HH:mm"));
                    $('#ColorFondo').val(info.event.backgroundColor);
                    $('#ColorTexto').val(info.event.textColor);
                    $("#FormularioEventos").modal();*/
                },
                eventResize: function(info) {
                    /*$('#Codigo').val(info.event.id);
                    $('#Titulo').val(info.event.title);
                    $('#FechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                    $('#FechaFin').val(moment(info.event.end).format("YYYY-MM-DD"));
                    $('#HoraInicio').val(moment(info.event.start).format("HH:mm"));
                    $('#HoraFin').val(moment(info.event.end).format("HH:mm"));
                    $('#ColorFondo').val(info.event.backgroundColor);
                    $('#ColorTexto').val(info.event.textColor);
                    $('#Descripcion').val(info.event.extendedProps.descripcion);
                    let registro = recuperarDatosFormulario();
                    modificarRegistro(registro);*/
                },
                eventDrop: function(info) {
                    /*$('#Codigo').val(info.event.id);
                    $('#Titulo').val(info.event.title);
                    $('#FechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                    $('#FechaFin').val(moment(info.event.end).format("YYYY-MM-DD"));
                    $('#HoraInicio').val(moment(info.event.start).format("HH:mm"));
                    $('#HoraFin').val(moment(info.event.end).format("HH:mm"));
                    $('#ColorFondo').val(info.event.backgroundColor);
                    $('#ColorTexto').val(info.event.textColor);
                    $('#Descripcion').val(info.event.extendedProps.descripcion);
                    let registro = recuperarDatosFormulario();
                    modificarRegistro(registro);*/
                },
                drop: function(info) {
                    /*limpiarFormulario();
                    $('#ColorFondo').val(info.draggedEl.dataset.colorfondo);
                    $('#ColorTexto').val(info.draggedEl.dataset.colortexto);
                    $('#Titulo').val(info.draggedEl.dataset.titulo);
                    let fechaHora = info.dateStr.split("T");
                    $('#FechaInicio').val(fechaHora[0]);
                    $('#FechaFin').val(fechaHora[0]);
                    if (info.allDay) { //verdadero si el calendario esta en vista de mes
                        $('#HoraInicio').val(info.draggedEl.dataset.horainicio);
                        $('#HoraFin').val(info.draggedEl.dataset.horafin);
                    } else {
                        $('#HoraInicio').val(fechaHora[1].substring(0, 5));
                        $('#HoraFin').val(moment(fechaHora[1].substring(0, 5)).add(1, 'hours'));
                    }
                    let registro = recuperarDatosFormulario();
                    agregarEventoPredefinido(registro);*/
                }
            });

            function displayMessage(message) {
                toastr.success(message, 'Event');
            }

            calendario1.render();



            let currColor = '#3c8dbc'
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                // Save color
                currColor = $(this).css('color')
                // Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                })
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
                // Get value and make sure it is not null
                let val = $('#new-event').val()
                if (val.length === 0) {
                    return
                }

                // Create events
                let event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('external-event')
                event.html('<h4 class="d-inline-block">'+val+'</h4>'+
                    '<button type="button" class="delete-unsigned-event bg-white btn btn-custon-calendario btn-light float-right">'+
                    '<i class="fa fa-close text-danger"></i>'+
                    '</button>'
                )

                switch(currColor)
                {
                    case 'rgb(0, 86, 179)':currColor='bg-info';break;
                    case 'rgb(186, 139, 0)':currColor='bg-warning';break;
                    case 'rgb(25, 105, 44)':currColor='bg-success';break;
                    case 'rgb(167, 29, 42)':currColor='bg-danger';break;
                    case 'rgb(0, 123, 255)':currColor='bg-grey';break;
                    default:currColor='bg-info';break;
                }

                var formUnsigned = new FormData();
                formUnsigned.append('calendario_nombre_evento', val);
                formUnsigned.append('calendario_color_evento', currColor);
                formUnsigned.append('type', 'add');
                $.ajax({
                    url: "{{route('fullcalendarAjaxUnsigned')}}",
                    data: formUnsigned,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#external-events').prepend(event)
                    }
                });


                ini_events(event)

                // Remove event from text input
                $('#new-event').val('')
            })

            $(document).on("submit", "#frm_add_evento_calendario", function (event) {
                event.preventDefault();
                var form = $(this)[0];
                var formData = new FormData(form);
                console.log(formData.get("calendario_color_evento"));
                formData.append('type', 'add');
                $.ajax({
                    url: "{{route('fullcalendarAjax')}}",
                    data: formData,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        let dateStr = moment(data.start).format('YYYY-MM-DD');
                        let dateEnd = moment(data.end).format('YYYY-MM-DD');
                        agregar_evento_calendario.hide();
                        displayMessage("Event created.");
                        calendario.addEvent({
                            id: data.id,
                            title: data.title,
                            start: dateStr,
                            end: dateEnd,
                            color: data.color
                        });
                    }
                });
            });

            $(document).on('click','#editar_evento_calendario .btn-edit',function(){
                $('i',this).removeClass('text-warning').addClass('text-dark');
                console.log("aa")
                $('#calendario_nombre_evento_editar').removeClass('border').removeClass('border-0');
                $("#calendario_nombre_evento_editar").prop('readonly',false).focus();
                $('.btn-edit-check').removeClass('d-none');
            });

            $(document).on("click", "#frm_editar_evento_calendario .btn-edit-check", function (event) {
                event.preventDefault();
                let eleme=$(this).parents('form').attr('id');
                console.log(eleme);
                var form = $('#'+eleme)[0];
                var formData = new FormData(form);
                //console.log(formData.get("eliminar_evento"));
                formData.append('type', 'updatetitle')
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url: "{{ route('fullcalendarAjax') }}",
                    success: function (data) {
                        editar_evento_calendario.hide();
                        var events = [ ];
                        calendario.addEventSource(events);
                        calendario.refetchEvents();
                        //let eventDelete = calendar.getEventById(formData.get("eliminar_evento"))
                        //eventDelete.remove();
                    }
                });
            });

            $(document).on("submit", "#frm_eliminar_evento_calendario", function (event) {
                event.preventDefault();
                var form = $(this)[0];
                var formData = new FormData(form);
                //console.log(formData.get("eliminar_evento"));
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

            window.agregarRegistro=function(registro){
                $.ajax({
                    type: 'POST',
                    url: 'datoseventos.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hay un problema:" + error);
                    }
                });
            };

            window.modificarRegistro=function(registro){
                $.ajax({
                    type: 'POST',
                    url: 'datoseventos.php?accion=modificar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hay un problema:" + error);
                    }
                });
            }

            window.borrarRegistro=function(registro){
                $.ajax({
                    type: 'POST',
                    url: 'datoseventos.php?accion=borrar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hay un problema:" + error);
                    }
                });
            }

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
                $('#Codigo').val('');
                $('#Titulo').val('');
                $('#Descripcion').val('');
                $('#FechaInicio').val('');
                $('#FechaFin').val('');
                $('#HoraInicio').val('');
                $('#HoraFin').val('');
                $('#ColorFondo').val('#3788D8');
                $('#ColorTexto').val('#ffffff');
            }

            window.recuperarDatosFormulario=function(){
                let registro = {
                    codigo: $('#Codigo').val(),
                    titulo: $('#Titulo').val(),
                    descripcion: $('#Descripcion').val(),
                    inicio: $('#FechaInicio').val() + ' ' + $('#HoraInicio').val(),
                    fin: $('#FechaFin').val() + ' ' + $('#HoraFin').val(),
                    colorfondo: $('#ColorFondo').val(),
                    colortexto: $('#ColorTexto').val()
                };
                return registro;
            }

        })
    </script>
@endsection

