@extends('adminlte::page')

@section('title', 'Pedidos - Bandeja de pedidos')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('css/toaster.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">

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
                            <div id="external-events">
                                 @foreach($all_eventsunsigned as $eventunsigned)
                                    <div id="unsigned_{{ $eventunsigned->id }}" class="external-event {{ $eventunsigned->color }}">
                                        <h4 class="d-inline-block">{{ $eventunsigned->title }}</h4>
                                        <button type="button" class="delete-unsigned-event bg-white btn btn-custon-calendario btn-light float-right">
                                            <i class="fa fa-close text-danger"></i>
                                        </button>
                                    </div>
                                 @endforeach

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
                        <div id="calendar" style="width: 100%; display: inline-block;"></div>
                        @include('fullcalendar.modal.agregar_evento')
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
    <script src=" {{asset('plugins/fullcalendar/main.js')}}"></script>
    <script src=" {{asset('plugins/fullcalendar/locales/es.js')}}"></script>
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


            let agregar_evento_calendario = new bootstrap.Modal(document.getElementById('agregar_evento_calendario'), {
                keyboard: false
            })
            let eliminar_evento_calendario = new bootstrap.Modal(document.getElementById('eliminar_evento_calendario'), {
                keyboard: false
            })

            //agregar_evento_calendario.addEventListener('hidden.bs.modal', function (event) {
                // do something...
                //console.log("465")
            //});

            //eliminar_evento_calendario.addEventListener('hidden.bs.modal', function (event) {
                // do something...
                //console.log("123")
            //});

            $('#agregar_evento_calendario').on('hidden.bs.modal', function () {
                console.log('hidden event fired!');
            })



            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (https://fullcalendar.io/docs/event-object)
                    // it doesn't need to have a start or end
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
            let calendarEl = document.getElementById('calendar');

            let draggable=new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function (eventEl) {
                    return {
                        id:$(eventEl).attr("id").split('_')[1],
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                    };
                }
            });

            let calendar = new Calendar(calendarEl, {
                dayMaxEventRows: true,
                views: {
                    timeGrid: {
                        dayMaxEventRows: 6 // adjust to 6 only for timeGridWeek/timeGridDay
                    }
                },
                initialDate: '2023-03-02',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                weekNumbers: true,
                eventColor: 'green',
                events: @json($eventss),
                eventDidMount: function(info) {
                    var tooltip = new Tooltip(info.el, {
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                },
                eventDisplay: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                //selectHelper:true,
                select: function (info) {
                    console.log("nuevo evento")
                    agregar_evento_calendario.show()

                },
                editable: true,
                drop:function(info){

                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                    /*var eventData = {
                        title: info.event.title,
                        start: info.event.start,
                        end: info.event.end
                    };*/



                    //if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        //$(this).remove();
                    //}
                },
                eventDragStop:function(event){
                    //event//jsEvent//view
                    //console.log("eventDragStop")
                    //console.log(info)
                    console.log("eventDragStop")
                    console.log(event);
                    calendar.fullCalendar('removeEvents', event.event.id);
                },
                eventReceive:function(info){
                    console.log("eventreceive")
                    //calendar.fullCalendar('removeEvents', event.event.id);
                },
                eventDrop: function (info) {
                    console.log("eventDrop")
                    /*let uielement=info.draggedEl;
                    let uiid=$(uielement).attr("id").split('_')[1];
                    var formData = new FormData();
                    formData.append('eventunsigned', uiid);
                    formData.append('dateStr', info.dateStr);
                    formData.append('type', 'adddrop');
                    $.ajax({
                        url: "{{route('fullcalendarAjax')}}",
                        data: formData,
                        type: "POST",
                        processData: false,
                        contentType: false,
                        success: function (data) {
                        }
                    });*/
                },
                eventClick: function (event) {
                    console.log("eliminar evento")
                    console.log(event);
                    let identify = event.event.id;
                    eliminar_evento_calendario.show();
                    $("#eliminar_evento").val(identify);
                },
                locale: 'es',
                themeSystem: 'bootstrap',
                droppable: true,
                displayEventTime:false,
            });

            function displayMessage(message) {
                //toastr.success(message, 'Event');
            }

            calendar.render();

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
                        let eventDelete = calendar.getEventById(formData.get("eliminar_evento"))
                        eventDelete.remove();

                    }
                });
            });
            /*ADD*/
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


                        let dateStr = moment(data.start).format('YYYY-MM-DD');//calendar.formatDate(data.start, "YYYY-MM-DDTHH:mm:ss");
                        let dateEnd = moment(data.end).format('YYYY-MM-DD');/*calendar.formatDate(data.end, {
                            month: '2-digit',
                            year: 'numeric',
                            day: '2-digit'
                        });*/

                        //let dateStr = FullCalendar.formatDate(data.start, "Y-MM-DD HH:mm:ss");
                        //let dateEnd = FullCalendar.formatDate(data.end  , "Y-MM-DD HH:mm:ss");

                        console.log(dateStr);
                        console.log(dateEnd);
                        agregar_evento_calendario.hide();
                        displayMessage("Event created.");
                        calendar.addEvent({
                            //start: '2020-08-08T10:30:00',
                            id: data.id,
                            title: data.title,
                            start: dateStr,
                            end: dateEnd,
                            color: data.color
                        });
                    }
                });
            });

        })
    </script>
@endsection

