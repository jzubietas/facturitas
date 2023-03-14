<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        //if($request->ajax()) {
            //dd('ajax');
            $all_events = Event::all();

            $eventss = [];

            foreach ($all_events as $event)
            {
                $eventss[] = [
                    'title' => $event->title,
                    'start' => $event->start,
                    'end' => $event->end,
                    'color'=>'purple'
                ];
            }
        //dd($eventss);
            /*$data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);*/
            //dd($data);
            //return response()->json($data);
        //}
            return view('fullcalendar.fullcalendar', compact('eventss'));
        //return view('fullcalendar.fullcalendar');
    }

    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);
                return response()->json($event);
            case 'update':
                $event = Event::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);
                return response()->json($event);
            case 'delete':
                $event = Event::find($request->id)->delete();
                return response()->json($event);
            default:
                # code...
                break;
        }
        return 0;
    }

}
