<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventsUnsigned;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        $eventss = [];
        $uneventss = [];

        $all_events = Event::all();

        foreach ($all_events as $event)
        {
            $eventss[] = [
                'id'=>$event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'color'=>$event->color,
                'textColor'=>$event->color,
                'backgroundColor'=>$event->color,
                'description' => 'description for All Day Event',
            ];
        }

        $all_eventsunsigned = EventsUnsigned::all();
        foreach ($all_eventsunsigned as $eventsunsigned)
        {
            $uneventss[] = [
                'id'=>$eventsunsigned->id,
                'titulo' => $eventsunsigned->title,
                'horainicio' => $eventsunsigned->created_at,
                'horafin' => $eventsunsigned->updated_at,
                'color'=>$eventsunsigned->color,
                'colortexto'=>$eventsunsigned->color,
                'colorfondo'=>$eventsunsigned->color,
                //'description' => 'description for All Day Event',
            ];
        }
        //dd($uneventss);
        return view('fullcalendar.fullcalendar', compact('eventss','uneventss'));

    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'load':
                $events = [];
                $all_events = Event::all();
                foreach ($all_events as $event)
                {
                    $events[] = [
                        'id'=>$event->id,
                        'title' => $event->title,
                        'start' => $event->start,
                        'end' => $event->end,
                        'color'=>$event->color,
                        'textColor'=>$event->color,
                        'backgroundColor'=>$event->color,
                        'description' => $event->description,
                    ];
                }
                //dd($events);
                return response()->json($events);
                break;
            case 'updatetitle':
                $event=Event::where('id',$request->editar_evento)->first();
                $event->update([
                    'title'=>$request->calendario_nombre_evento_editar,
                    'description'=>$request->calendario_descripcion_evento_editar,
                ]);
                return response()->json($event);
                break;
            case 'adddrop':
                //info de unsigned eventunsigned
                $eventUnsigned=EventsUnsigned::where('id',$request->eventunsigned)->first();
                $event = Event::create([
                    'title' => $eventUnsigned->title,
                    'start' => $request->dateStr,
                    'end' => $request->dateStr,
                    'color' => $eventUnsigned->color,
                ]);
                return response()->json($event);
            case 'add':
                $event = Event::create([
                    'title' => $request->calendario_nombre_evento,
                    'description' => $request->calendario_descripcion_evento_nuevo,
                    'start' => $request->calendario_start_evento,
                    'end' => $request->calendario_end_evento,
                    'color' => $request->calendario_color_evento,
                    'colorEvento' => $request->calendario_color_evento,
                    'colorBackground' => $request->calendario_fondo_evento,
                    'tipo'=>$request->calendario_tipo_evento,
                ]);
                return response()->json($event);
            case 'modificar':
                $event = Event::find($request->id)->update([
                    'title' => $request->title,
                    'description' => 'descripcion',
                    'start' => $request->start,
                    'end' => $request->end,
                    'color' => $request->calendario_color_evento,
                    'colorEvento' => $request->calendario_color_evento,
                    'colorBackground' => $request->calendario_color_evento,
                ]);
                return response()->json($event);
            case 'borrar':
                $event = Event::find($request->editar_evento)->delete();
                return response()->json($request->editar_evento);
            default:
                # code...
                break;
        }
        return 0;
    }

    public function eventsunsigned(Request $request)
    {
        $eventssunsigned = [];
        $all_eventsunsigned = EventsUnsigned::all();

        foreach ($all_eventsunsigned as $eventunsigned)
        {
            $eventssunsigned[] = [
                'id'=>$eventunsigned->id,
                'title' => $eventunsigned->title,
                'start' => $eventunsigned->start,
                'end' => $eventunsigned->end,
                'color'=>$eventunsigned->color,
                //'description' => 'description for All Day Event',
            ];
        }

        return response()->json($eventssunsigned);
    }

    public function ajaxunsigned(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = EventsUnsigned::create([
                    'title' => $request->calendario_nombre_evento,
                    //'start' => $request->calendario_start_evento,
                    //'end' => $request->calendario_start_evento,
                    'color' => $request->calendario_color_evento,
                ]);
                return response()->json($event);
            case 'update':
                $event = EventsUnsigned::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);
                return response()->json($event);
            case 'delete':
                $event = EventsUnsigned::find($request->eliminar_evento)->delete();
                return response()->json($request->eliminar_evento);
            default:
                # code...
                break;
        }
        return 0;
    }


}
