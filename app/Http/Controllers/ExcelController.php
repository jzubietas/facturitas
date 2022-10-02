<?php

namespace App\Http\Controllers;

use App\Exports\ArticulosExport;
use App\Exports\BaseFriaPorAsesorExport;
use App\Exports\BasesFriasExport;
use App\Exports\ClientesExport;
use App\Exports\ClientesPedidosExport;
use App\Exports\MisPagosExport;
use App\Exports\MisPedidosExport;
use App\Exports\PagosAprobadosExport;
use App\Exports\PagosExport;
use App\Exports\PagosIncompletosExport;
use App\Exports\PagosObservadosExport;
use App\Exports\PagosPorAsesoresExport;
use App\Exports\PagosPorAsesorExport;
use App\Exports\PedidosAtendidosExport;
use App\Exports\PedidosEnAtencionExport;
use App\Exports\PedidosExport;
use App\Exports\PedidosOperacionesExport;
use App\Exports\PedidosPagosGeneralExport;
use App\Exports\PedidosPagadosExport;
use App\Exports\PedidosPorAsesoresExport;
use App\Exports\PedidosPorAsesorExport;
use App\Exports\PedidosPorAtenderExport;
use App\Exports\PedidosPorEnviarExport;
use App\Exports\PedidosPorFechasExport;
use App\Exports\PedidosSinPagosExport;
use App\Models\Pedido;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExcelController extends Controller
{   
    public function clientesExcel()
    {
        return Excel::download(new ClientesExport, 'Lista de Clientes.xlsx');
    }

    public function clientespedidosExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);

        return (new ClientesPedidosExport)
                ->clientes($request)
                ->anioa($request)
                ->aniop($request)
                ->download('Lista de Clientes_pedidos_'.$request->anio.'.xlsx');

        /* return Excel::download(new ClientesPedidosExport, 'Lista de Clientes_pedidos.xlsx'); */
    }

    public function basefriaExcel()
    {
        return Excel::download(new BasesFriasExport, 'Lista Base Fria.xlsx');
    }

    public function basefriaporasesorExcel(Request $request)
    {
        return (new BaseFriaPorAsesorExport)
                ->clientes($request)
                ->download('Lista Base Fria por Asesor - USER'.$request->user_id.'.xlsx');
    }

    public function pagosaprobadosExcel()
    {
        return Excel::download(new PagosAprobadosExport, 'Lista de Pagos Aprobados.xlsx');
    }

    public function pagosExcel()
    {
        return Excel::download(new PagosExport, 'Lista de Pagos.xlsx');
    }
    public function mispagosExcel()
    {
        return Excel::download(new MisPagosExport, 'Lista de Mis Pagos.xlsx');
    }
    public function pagosincompletosExcel()
    {
        return Excel::download(new PagosIncompletosExport, 'Lista de Pagos Incompletos.xlsx');
    }
    public function pagosobservadosExcel()
    {
        return Excel::download(new PagosObservadosExport, 'Lista de Pagos Observados.xlsx');
    }

    public function pedidosExcel()
    {
        return Excel::download(new PedidosExport, 'Lista de Pedidos.xlsx');
    }
    public function mispedidosExcel()
    {
        return Excel::download(new MisPedidosExport, 'Lista de Mis Pedidos.xlsx');
    }
    public function pedidospagadosExcel()
    {
        return Excel::download(new PedidosPagadosExport, 'Lista de Pedidos Pagados.xlsx');
    }
    public function pedidossinpagosExcel()
    {
        return Excel::download(new PedidosSinPagosExport, 'Lista de Pedidos Sin Pagos.xlsx');
    }

    public function pedidosporatenderExcel()
    {
        return Excel::download(new PedidosPorAtenderExport, 'Lista de Pedidos por Atender.xlsx');
    }
    public function pedidosenatencionExcel()
    {
        return Excel::download(new PedidosEnAtencionExport, 'Lista de Pedidos en Atencion.xlsx');
    }
    public function pedidosatendidosExcel()
    {
        return Excel::download(new PedidosAtendidosExport, 'Lista de Pedidos Atendidos.xlsx');
    }

    public function pedidosporenviarExcel()
    {
        return Excel::download(new PedidosPorEnviarExport, 'Lista de Pedidos por Enviar.xlsx');
    }

    //REPORTES
    public function pedidosgeneralexcel(Request $request)//REPORTE GENERAL
    {
        return (new PedidosPagosGeneralExport)
                ->pedidos($request)
                ->pedidos2($request)
                ->download('reporte general de pedidos y pagos.xlsx');
    }
    
    public function pedidosporasesorExcel(Request $request)//REPORTE MIS ASESORES
    {
        return (new PedidosPorAsesorExport)
                ->pedidos($request)
                ->pedidos2($request)
                ->download('reporte pedidos y pagos de mis asesores.xlsx');
    }
    
    public function pedidosoperacionesexcel(Request $request)//REPORTE OPERACIONES
    {
        return (new PedidosOperacionesExport)
                ->pedidos($request)
                ->pedidos2($request)
                ->download('Lista de pedidos operaciones.xlsx');
    }

    public function pedidosporfechasExcel(Request $request)
    {
        return (new PedidosPorFechasExport)
                ->pedidos($request)
                ->pedidos2($request)
                ->download('reporte pedidos y pagos.xlsx');
    }
    public function pagosporasesorExcel(Request $request)
    {
        return (new PagosPorAsesorExport)
                ->pagos($request)
                ->download('Lista de pagos por usuario.xlsx');
    }
    public function pagosporasesoresExcel(Request $request)
    {
        return (new PagosPorAsesoresExport)
                ->pagos($request)
                ->download('Lista de pagos por asesores.xlsx');
    }
}
