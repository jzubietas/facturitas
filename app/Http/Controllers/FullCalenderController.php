<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventsUnsigned;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FullCalenderController extends Controller
{
    public function indexcalendario(Request $request)
    {
        return view('fullcalendar.index');
    }

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

        $all_eventsunsigned = EventsUnsigned::orderBy('id','desc')->get();
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
            case 'validate':
                $json=array('ok'=>true);
                return response()->json();
                break;
            case 'load':
                $events = [];
                $all_events = Event::all();
                foreach ($all_events as $event)
                {
                    $events[] = [
                        'id'=>$event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'start' => $event->start,
                        'end' => $event->end,
                        'color'=>$event->color,
                        'colorEvento'=>$event->colorEvento,
                        'fondoEvento'=>$event->fondoEvento,
                        'tipo'=>$event->tipo,
                        'frecuencia'=>$event->frecuencia,
                    ];
                }
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
                $color='';$colorFondo='';
                if($request->colorBackground!='')
                {
                    $colorFondo=$request->colorBackground;$color='white';
                }else{
                    if($request->calendario_tipo_evento=='PAGO'){$colorFondo='#BA55D3';$color='white';}
                    else if($request->calendario_tipo_evento=='OTROS'){$colorFondo='#5F9F9F';$color="white";}
                }


                //analisis frecuencia
            $frecuencia_recorrido=null;
                switch($request->calendario_frecuencia_evento) {
                    case 'una_vez':
                        $frecuencia_recorrido = $request->calendario_start_evento;
                        $event = Event::create([
                            'title' => $request->calendario_nombre_evento,
                            'description' => $request->calendario_descripcion_evento_nuevo,
                            'start' => $request->calendario_start_evento,
                            'end' => $request->calendario_end_evento,
                            'color' => $colorFondo,
                            'colorEvento' => $color,
                            'fondoEvento' => $colorFondo,
                            'tipo' => $request->calendario_tipo_evento,
                            'frecuencia' => $request->calendario_frecuencia_evento,
                        ]);
                        break;
                    case 'diario':
                        $inidia = Carbon::parse($request->calendario_start_evento)->clone()->startOfDay();
                        $findia = Carbon::parse($request->calendario_start_evento)->clone()->endOfMonth()->endOfDay();
                        $difference = ($inidia->diff($findia)->days < 1)
                            ? 'today'
                            : $inidia->diffForHumans($findia);
                        for ($i = 0; $i <= $difference; $i++) {
                            //llevar al dia
                            $fecha = $inidia->clone()->addDays($i)->format('Y-m-d');

                            $event = Event::create([
                                'title' => $request->calendario_nombre_evento,
                                'description' => $request->calendario_descripcion_evento_nuevo,
                                'start' => $fecha,
                                'end' => $fecha,
                                'color' => $colorFondo,
                                'colorEvento' => $color,
                                'fondoEvento' => $colorFondo,
                                'tipo' => $request->calendario_tipo_evento,
                                'frecuencia' => $request->calendario_frecuencia_evento,
                            ]);

                        }
                        break;
                    case 'ini_mes':
                        $startDate = Carbon::parse($request->calendario_start_evento);
                        if (!$startDate->day == 1) {
                            $startDate->addMonth();
                        }
                        $endDate = $startDate->clone()->addYear()->startOfYear()->subDay();
                        //$monthsRemaining = 12 - $startDate->month + 1;
                        $firstDayOfNextMonth = $startDate->clone()->firstOfMonth();

                        for ($date = $firstDayOfNextMonth; $date->lte($endDate); $date->addMonthsNoOverflow())
                        {
                            $fullmes=$date->clone()->firstOfMonth();
                            Event::create([
                                'title' => $request->get('calendario_nombre_evento'),
                                'description' => $request->get('calendario_descripcion_evento_nuevo'),
                                'start' => $fullmes->startOfDay(),
                                'end' => $fullmes->endOfDay(),
                                'color' => $colorFondo,
                                'colorEvento' => $color,
                                'fondoEvento' => $colorFondo,
                                'tipo' => $request->get('calendario_tipo_evento'),
                                'frecuencia' => $request->get('calendario_frecuencia_evento'),
                            ]);
                        }
                        break;
                    case 'fin_mes':
                        $startDate = Carbon::parse($request->calendario_start_evento);
                        if (!$startDate->isLastOfMonth()) {
                            //$date->addMonth();
                            //$startDate=$startDate->clone()->lastOfMonth();
                        }
                        $endDate = $startDate->clone()->addYear()->startOfYear()->subDay();
                        $lastDayOfNextMonth = $startDate->clone()->lastOfMonth();

                        for ($date = $lastDayOfNextMonth; $date->lte($endDate); $date->addMonthsNoOverflow())
                        {
                            $fullmes=$date->clone()->lastOfMonth();
                            Event::create([
                                'title' => $request->get('calendario_nombre_evento'),
                                'description' => $request->get('calendario_descripcion_evento_nuevo'),
                                'start' => $fullmes->startOfDay(),
                                'end' => $fullmes->endOfDay(),
                                'color' => $colorFondo,
                                'colorEvento' => $color,
                                'fondoEvento' => $colorFondo,
                                'tipo' => $request->get('calendario_tipo_evento'),
                                'frecuencia' => $request->get('calendario_frecuencia_evento'),
                            ]);
                        }
                        break;
                    //return response()->json($event);
                }
                break;
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
        //return 0;
    }


}
