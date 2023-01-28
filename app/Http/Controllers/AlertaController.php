<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\User;
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
        $tipo = $request->tipo;
        if (!in_array($tipo, ['notice', 'success', 'info', 'error'])) {
            $tipo = 'notice';
        }
        $users = [auth()->id()];
        if ($request->user_add_role) {
            $users = User::rol($request->user_add_role)->activo()->pluck('id');
        }
        $alertas = [];
        foreach ($users as $id) {
            $alertas [] = Alerta::create([
                'user_id' => $id,
                'tipo' => $tipo,
                'subject' => $request->title,
                'message' => $request->nota,
                'date_at' => $request->fecha,
            ]);
        }
        return $alertas;
    }
}
