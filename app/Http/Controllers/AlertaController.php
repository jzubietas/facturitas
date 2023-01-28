<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{

    public function index()
    {

    }

    public function confirmar(Request $request)
    {
        $action = $request->action;
        if ($action == 'aceptar') {
            $alerta = Alerta::findOrFail($request->alerta_id);
            $alerta->update([
                'finalized_at' => now()
            ]);
            return $alerta;
        } elseif ($action == 'cancelar') {
            $alerta = Alerta::findOrFail($request->alerta_id);
            $alerta->update([
                'read_at' => now()
            ]);
            return $alerta;
        }
        return response();
    }

    public function store(Request $request)
    {
        $alerta = Alerta::create([
            'user_id' => auth()->id(),
            'subject' => $request->title,
            'message' => $request->nota,
        ]);
        return $alerta;
    }
}
