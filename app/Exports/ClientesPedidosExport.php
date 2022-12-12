<?php

namespace App\Exports;

use App\Models\ListadoResultado;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Porcentaje;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromCollection;


class ClientesPedidosExport implements FromCollection
{

    public function collection($request)
    {
        return Cliente::all();
    }
    //use Exportable;
    
    
}