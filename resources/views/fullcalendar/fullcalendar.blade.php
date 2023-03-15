@extends('adminlte::page')

@section('title', 'Pedidos - Bandeja de pedidos')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('css/toaster.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">

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
                            <h4 class="card-title">Draggable Events</h4>
                        </div>
                        <div class="card-body">
                            <!-- the events -->
                            <div id="external-events">
                                <div class="external-event bg-success">Lunch</div>
                                <div class="external-event bg-warning">Go home</div>
                                <div class="external-event bg-info">Do homework</div>
                                <div class="external-event bg-primary">Work on UI design</div>
                                <div class="external-event bg-danger">Sleep tight</div>
                                <div class="checkbox">
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
                            <h3 class="card-title">Create Event</h3>
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
                                <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                                <div class="input-group-append">
                                    <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                                </div>
                                <!-- /btn-group -->
                            </div>
                            <!-- /input-group -->
                            <button type="button" class="btn btn-lg btn-danger" data-toggle="popover"
                                    title="Popover title"
                                    data-content="And here's some amazing content. It's very engaging. Right?">Click to
                                toggle popover
                            </button>

                        </div>
                    </div>

                    {{--List Event--}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List Event</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <p>asd</p>
                                    <a class="text-decoration-none rounded px-3 py-2 bg-success d-flex justify-content-center align-items-center" href="#" style="width: 25px">
                                        <i class="fas fa-edit text-white" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <p>asd</p>
                                    <a class="text-decoration-none rounded px-3 py-2 bg-success d-flex justify-content-center align-items-center" href="#" style="width: 25px">
                                        <i class="fas fa-edit text-white" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <p>asd</p>
                                    <a class="text-decoration-none rounded px-3 py-2 bg-success d-flex justify-content-center align-items-center" href="#" style="width: 25px">
                                        <i class="fas fa-edit text-white" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
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
                        @include('fullcalendar.modal.eliminar_evento')


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
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            ini_events($('#external-events div.external-event'));

            //let date = new Date()
            /*let d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()*/

            let Calendar = FullCalendar.Calendar;
            let Draggable = FullCalendar.Draggable;

            let containerEl = document.getElementById('external-events');
            //let checkbox = document.getElementById('drop-remove');
            let calendarEl = document.getElementById('calendar');

            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function (eventEl) {
                    return {
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
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                weekNumbers: true,
                dateClick: function (info) {
                    //alert('clicked ' + info.dateStr);
                },
                select: function (info) {
                    console.log(info)
                    //alert('selected ' + info.startStr + ' to ' + info.endStr);
                    agregar_evento_calendario.show()

                    /*let event_name = prompt('Event Name:');
                    if(event_name)
                    {
                        let event_start = $.fullCalendar.formatDate(info.startStr, "Y-MM-DD HH:mm:ss");
                        let event_end = $.fullCalendar.formatDate(info.endStr, "Y-MM-DD HH:mm:ss");

                        $.ajax({
                            url: "{{--route('fullcalendarAjax')--}}",
                            data: {
                                event_name: event_name,
                                event_start: event_start,
                                event_end: event_end,
                                type: 'create'
                            },
                            type: "POST",
                            success: function (data) {
                                displayMessage("Event created.");
                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: event_name,
                                    start: event_start,
                                    end: event_end,
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                            }
                        });

                    }*/
                },
                eventDrop: function (info) {
                    console.log(info.event.title + " was dropped on " + info.event.start.toISOString());
                    /*if (!confirm("Are you sure about this change?")) {
                        info.revert();
                    }*/

                    /*let event_start = $.fullCalendar.formatDate(info.event.startStr, "Y-MM-DD");
                    let event_end = $.fullCalendar.formatDate(info.endStr, "Y-MM-DD");

                    $.ajax({
                        url: "{{--route('fullcalendarAjax')--}}",
                        data: {
                            title: info.name,
                            start: event_start,
                            end: event_end,
                            id: info.id,
                            type: 'edit',
                        },
                        type: "POST",
                        success: function (response) {
                            console.log(response)
                            displayMessage("Event updated");
                        }
                    });*/
                },
                eventClick: function (event) {
                    let identify = event.event.id;
                    eliminar_evento_calendario.show();
                    $("#eliminar_evento").val(identify);
                },
                locale: 'es',
                themeSystem: 'bootstrap',
                //events: @json($eventss),
                events: @json($eventss),
                editable: true,
                droppable: true,
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
                event.text(val)
                $('#external-events').prepend(event)

                ini_events(event)

                // Remove event from text input
                $('#new-event').val('')
            })

            /*$('#calendar').fullCalendar({
                themeSystem: 'jquery-ui',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                locale: 'es',
                weekNumbers: true,
                eventLimit: true,
                events: @json($eventss),
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
            });*/

            $('#agregar_evento_calendario').on('show.bs.modal', function (event) {
                event.preventDefault();
                //$('#color').colorpicker({});
            });

            $('#eliminar_evento_calendario').on('show.bs.modal', function (e) {
                //var idevento = $(e.relatedTarget).data('eliminaEvento');
                //$("#eliminar_evento").val(idevento);
                //$("#thanks").attr("src", img);
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
                        let eventDelete = calendar.getEventById(formData.get("eliminar_evento"))
                        eventDelete.remove();
                        //calendar.fullCalendar('removeEvents', formData.eliminar_evento );
                        //$('#tablaPrincipal').DataTable().ajax.reload();

                    }
                });
            });
            /*ADD*/
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
                        displayMessage("Event created.");
                        calendar.addEvent(
                            {
                                id: data.id,
                                title: calendario_nombre_evento,
                                start: calendario_start_evento,
                                end: calendario_start_evento,
                            }
                        );
                        calendar.refetchEvents();
                    }
                });
            });


        })
    </script>
@endsection

