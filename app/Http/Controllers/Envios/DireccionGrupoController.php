<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\DireccionGrupo;

class DireccionGrupoController extends Controller
{

    public function get_sustentos_adjuntos(DireccionGrupo $grupo)
    {
        return response()->json($grupo->motorizadoHistories);
    }
}
