<?php

namespace App\Http\Controllers;

use App\Exports\BaseFriaPorAsesorExport;
use App\Exports\BasesFriasExport;
use App\Exports\ClientesAbandonosExport;
use App\Exports\ClientesExport;
use App\Exports\Clientesv2Export;
use App\Exports\EntregadosPorFechasExport;
use App\Exports\EstadoSobresExport;
use App\Exports\MisPagosExport;
use App\Exports\MisPedidosExport;
use App\Exports\MovimientosExport;
use App\Exports\PagosAbonadosExport;
use App\Exports\PagosAprobadosExport;
use App\Exports\PagosExport;
use App\Exports\PagosIncompletosExport;
use App\Exports\PagosObservadosExport;
use App\Exports\PagosPorAsesoresExport;
use App\Exports\PagosPorAsesorExport;
use App\Exports\PagosPorRevisarExport;
use App\Exports\PedidosAtendidosExport;
use App\Exports\PedidosEnAtencionExport;
use App\Exports\PedidosEntregadosExport;
use App\Exports\PedidosExport;
use App\Exports\PedidosOperacionesExport;
use App\Exports\PedidosPagadosExport;
use App\Exports\PedidosPagosGeneralExport;
use App\Exports\PedidosPorAsesorExport;
use App\Exports\PedidosPorAtenderExport;
use App\Exports\PedidosPorEnviarExport;
use App\Exports\PedidosPorEnviarPorFechasExport;
use App\Exports\PedidosSinPagosExport;
use App\Exports\SobresRutaEnvioExport;
use App\Exports\Templates\PlantillaExportaListadoOlva;
use App\Exports\Templates\PlantillaExportBasefriaMultiple;
use App\Exports\Templates\PlantillaExportClientescuatromesesMultiple;
use App\Exports\Templates\PlantillaExportClientesdosmesesMultiple;
use App\Exports\Templates\PlantillaExportClientesReporteMultiple;
use App\Exports\Templates\PlantillaExportClientsFallendDebtMultiple;
use App\Exports\Templates\PlantillaExportClientsFallendWithOutDebtMultiple;
use App\Exports\Templates\PlantillaExportMovimientosReporteMultiple;
use App\Exports\Templates\PlantillaExportMultipleLlamada;
use App\Exports\Templates\PlantillaExportPedidoMultiple;
use App\Exports\Templates\PlantillaExportPedidosPerdonarCourierReporteMultiple;
use App\Exports\Templates\PlantillaExportRutaenvioMultiple;
use App\Exports\Templates\PlantillaMotorizadoConfirmarMultiple;
use App\Exports\Templates\PlantillaRecepcionMotorizadoMultiple;
use App\Exports\UsuariosExport;
use App\Models\Pedido;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    // public function clientesExcel()
    // {
    //     return Excel::download(new ClientesExport, 'Lista de Clientes.xlsx');
    // }
    public function enviosRecepcionmotorizadoExcel(Request $request)
    {
        $condicion_envio = $request->condicion_envio;
        if ($request->has('historial')) {
            $condicion_envio = Pedido::RECEPCION_MOTORIZADO_INT;
        }
        return (new PlantillaRecepcionMotorizadoMultiple(
            $request->user_motorizado,
            $request->fecha_envio,
            $condicion_envio
        ))
            ->download('Lista de Recepcion Motorizado.xlsx');
    }

    public function enviosMotorizadoconfirmarExcel(Request $request)
    {
        return (new PlantillaMotorizadoConfirmarMultiple($request->user_motorizado, $request->fecha_envio))
            ->download('Lista de Motorizado Confirmar.xlsx');
    }

    public function analisisExcel(Request $request)
    {
        return (new PlantillaExportMultipleLlamada())
            ->download('Reporte Llamadas.xlsx');
    }

    public function sobresRutaEnvioLimaNorteExcel(Request $request)
    {
        return (new SobresRutaEnvioExport)
            ->pedidos($request)
            ->download('Lista de Sobres - Ruta de Envio Lima Norte.xlsx');
    }

    public function sobresRutaEnvioLimaCentroExcel(Request $request)
    {
        return (new SobresRutaEnvioExport)
            ->pedidos($request)
            ->download('Lista de Sobres - Ruta de Envio Lima Centro.xlsx');
    }

    public function sobresRutaEnvioLimaSurExcel(Request $request)
    {
        return (new SobresRutaEnvioExport)
            ->pedidos($request)
            ->download('Lista de Sobres - Ruta de Envio Lima Sur.xlsx');
    }

    public function sobresRutaEnvioProvinciaExcel(Request $request)
    {
        return (new SobresRutaEnvioExport)
            ->pedidos($request)
            ->download('Lista de Sobres - Ruta de Envio Provincia.xlsx');
    }


    public function sobresRutaEnvioExcel(Request $request)
    {
        return (new PlantillaExportRutaenvioMultiple($request->de))
            ->download('Lista de Sobres - Ruta de Envio_' . $request->de . '.xlsx');
    }

    public function porrevisarExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        return (new PagosPorRevisarExport)
            ->pagos($request)
            ->download('Lista de pagos por por revisar.xlsx');
    }

    /*public function porrevisarExcel(Request $request)
    {
        return (new PagosIncompletosExport)
                ->pagos($request)
                ->download('Lista de Pagos Incompletos.xlsx');
    }*/

    public function usuariosExcel(Request $request)
    {
        return (new UsuariosExport)
            //->clientes1($request)
            ->usuarios1($request)
            ->download('Lista de Usuarios.xlsx');
    }

    public function movimientosExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        return (new PlantillaExportMovimientosReporteMultiple($request->desde,$request->hasta))
            ->download('Lista de Movimientos.xlsx');
    }

    public function clientesExcel(Request $request)
    {
        return (new ClientesExport)
            ->clientes1($request)
            ->clientes2($request)
            ->download('Lista de Clientes.xlsx');
    }

    public function clientesv2Excel(Request $request)
    {
        return (new Clientesv2Export)
            ->clientes1($request)
            ->download('Lista de Clientes Situacion.xlsx');
    }

    public function clientessituacionExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        //dd($request);
        return (new ClientesAbandonosExport)
            ->clientes($request)
            ->anioa($request)
            ->aniop($request)
            ->download('Lista de Clientes_Situacion_' . $request->anio . '.xlsx');
    }
    public function clientespedidosExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        return (new PlantillaExportClientesReporteMultiple('','2022'))
            ->download('Lista de Clientes_pedidos_' . $request->anio . '.xlsx');
    }

    public function pedidosPerdonarCourierExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        return (new PlantillaExportPedidosPerdonarCourierReporteMultiple('','2022'))
            ->download('Lista de Clientes_pedidos_' . $request->anio . '.xlsx');
    }

    public function clientesTwoMonthAgoExcel(Request $request)
    {
        return (new PlantillaExportClientesdosmesesMultiple())
            ->download('Lista de Clientes_pedidos_2_meses.xlsx');
    }

    public function clientsFallenDebtExcel(Request $request)
    {
        return (new PlantillaExportClientsFallendDebtMultiple())
            ->download('Clientes Caidos con Deuda.xlsx');
    }

    public function clientsFallenWithOutDebtExcel(Request $request)
    {
        return (new PlantillaExportClientsFallendWithOutDebtMultiple())
            ->download('Clientes Caidos sin Deuda.xlsx');
    }





    public function basefriaAllAsesorExcel(Request $request)
    {
        if(!$request->user_id)
        {
            return (new BaseFriaPorAsesorExport)
                ->clientes($request)
                ->download('Lista Base Fria por todos los Asesores.xlsx');
        }
        else
        {
            return (new BaseFriaPorAsesorExport)
                ->clientes($request)
                ->download('Lista Base Fria por Asesor - USER' . $request->user_id . '.xlsx');
        }
    }

    public function clientesReporteMultipleExcel(Request $request)
    {
        //return $request->all();
        ini_set('memory_limit', '-1');
        set_time_limit(3000000);
        return (new PlantillaExportClientesReporteMultiple($request->situacion,'2022'))
            ->download('Lista de Clientes Reporte.xlsx');
    }

    public function clientesFourMonthAgoExcel(Request $request)
    {
        return (new PlantillaExportClientescuatromesesMultiple())
            ->download('Lista de Clientes_pedidos_4_meses.xlsx');
    }

    public function excelBaseFriaExportar(Request $request)
    {
        return (new PlantillaExportBasefriaMultiple())
            ->download('Lista de Base Fria - Asesor.xlsx');
    }
    public function basefriaExcel(Request $request)
    {
        return (new BasesFriasExport)
            ->base_fria($request)
            ->download('Lista Base Fria.xlsx');
    }

    public function basefriaporasesorExcel(Request $request)
    {
        return (new BaseFriaPorAsesorExport)
            ->clientes($request)
            ->download('Lista Base Fria por Asesor - USER' . $request->user_id . '.xlsx');
    }

    // public function pagosaprobadosExcel()
    // {
    //     return Excel::download(new PagosAprobadosExport, 'Lista de Pagos Aprobados.xlsx');
    // }
    public function pagosaprobadosExcel(Request $request)
    {
        return (new PagosAprobadosExport)
            ->pagos($request)
            ->download('Lista de Pagos Aprobados.xlsx');
    }

    // public function pagosExcel()
    // {
    //     return Excel::download(new PagosExport, 'Lista de Pagos.xlsx');
    // }
    // public function mispagosExcel()
    // {
    //     return Excel::download(new MisPagosExport, 'Lista de Mis Pagos.xlsx');
    // }
    // public function pagosincompletosExcel()
    // {
    //     return Excel::download(new PagosIncompletosExport, 'Lista de Pagos Incompletos.xlsx');
    // }
    // public function pagosobservadosExcel()
    // {
    //     return Excel::download(new PagosObservadosExport, 'Lista de Pagos Observados.xlsx');
    // }
    public function pagosExcel(Request $request)
    {
        return (new PagosExport)
            ->pagos($request)
            ->download('Lista de Pagos.xlsx');
    }

    public function mispagosExcel(Request $request)
    {
        return (new MisPagosExport)
            ->pagos($request)
            ->download('Lista de Mis Pagos.xlsx');
    }

    public function pagosincompletosExcel(Request $request)
    {
        return (new PagosIncompletosExport)
            ->pagos($request)
            ->download('Lista de Pagos Incompletos.xlsx');
    }

    public function pagosobservadosExcel(Request $request)
    {
        return (new PagosObservadosExport)
            ->pagos($request)
            ->download('Lista de Pagos Observados.xlsx');
    }

    public function pagosabonadosExcel(Request $request)
    {
        return (new PagosAbonadosExport)
            ->pagos($request)
            ->download('Lista de Pagos Abonado Parcial.xlsx');
    }

    // public function pedidosExcel()
    // {
    //     return Excel::download(new PedidosExport, 'Lista de Pedidos.xlsx');
    // }
    // public function mispedidosExcel()
    // {
    //     return Excel::download(new MisPedidosExport, 'Lista de Mis Pedidos.xlsx');
    // }
    // public function pedidospagadosExcel()
    // {
    //     return Excel::download(new PedidosPagadosExport, 'Lista de Pedidos Pagados.xlsx');
    // }
    // public function pedidossinpagosExcel()
    // {
    //     return Excel::download(new PedidosSinPagosExport, 'Lista de Pedidos Sin Pagos.xlsx');
    // }
    public function pedidosExcel(Request $request)
    {
        return (new PedidosExport)
            ->pedidos($request)
            /* ->pedidos2($request) */
            ->download('Lista de Pedidos.xlsx');
    }

    public function mispedidosExcel(Request $request)
    {
        return (new MisPedidosExport)
            ->pedidos($request)
            /* ->pedidos2($request) */
            ->download('Lista de Mis Pedidos.xlsx');
    }

    public function pedidospagadosExcel(Request $request)
    {
        return (new PedidosPagadosExport)
            ->pedidos($request)
            ->download('Lista de Pedidos Pagados.xlsx');
    }

    public function pedidossinpagosExcel(Request $request)
    {
        return (new PedidosSinPagosExport)
            ->pedidos($request)
            ->download('Lista de Pedidos Sin Pagos.xlsx');
    }

    // public function pedidosporatenderExcel()
    // {
    //     return Excel::download(new PedidosPorAtenderExport, 'Lista de Pedidos por Atender.xlsx');
    // }
    // public function pedidosenatencionExcel()
    // {
    //     return Excel::download(new PedidosEnAtencionExport, 'Lista de Pedidos en Atencion.xlsx');
    // }
    // public function pedidosatendidosExcel()
    // {
    //     return Excel::download(new PedidosAtendidosExport, 'Lista de Pedidos Atendidos.xlsx');
    // }
    public function pedidosporatenderExcel(Request $request)
    {
        return (new PedidosPorAtenderExport)
            ->pedidos($request)
            ->download('Lista de Pedidos por Atender.xlsx');
    }

    public function pedidosenatencionExcel(Request $request)
    {
        return (new PedidosEnAtencionExport)
            ->pedidos($request)
            ->download('Lista de Pedidos en Atencion.xlsx');
    }

    public function pedidosatendidosExcel(Request $request)
    {
        return (new PedidosAtendidosExport)
            ->pedidos($request)
            ->download('Lista de Pedidos Atendidos.xlsx');
    }

    public function pedidosentregadosExcel(Request $request)
    {
        return (new PedidosEntregadosExport)
            ->pedidos($request)
            ->download('Lista de Pedidos Entregados.xlsx');
    }

    // public function pedidosporenviarExcel()
    // {
    //     return Excel::download(new PedidosPorEnviarExport, 'Lista de Pedidos por Enviar.xlsx');
    // }
    public function pedidosporenviarExcel(Request $request)
    {
        return (new PedidosPorEnviarExport)
            ->pedidosLima($request)
            ->download('Lista de Pedidos por Enviar.xlsx');
    }

    //REPORTES
    public function pedidosgeneralexcel(Request $request)//REPORTE GENERAL
    {
        return (new PedidosPagosGeneralExport)
            ->pedidos($request)
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
            ->download('Lista de pedidos operaciones.xlsx');
    }

    // NUEVO: PEDIDOS POR ENVIAR POR FECHAS
    public function pedidosporenviarporfechasexcel(Request $request)//ENTREGADOS POR FECHAS
    {
        return (new PedidosPorEnviarPorFechasExport)
            ->pedidosLima($request)
            ->pedidosProvincia($request)
            ->download('Lista de Pedidos por Enviar.xlsx');
    }

    public function entregadosporfechasexcel(Request $request)//ENTREGADOS POR FECHAS
    {
        return (new EntregadosPorFechasExport)
            ->pedidosLima($request)
            ->pedidosProvincia($request)
            ->download('reporte pedidos entregados.xlsx');
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


    public function estadosobresExcel(Request $request)
    {
        return (new EstadoSobresExport)
            ->pedidosLima($request)
            ->download('Estado de Sobres.xlsx');
    }

    public function listadoEnviosTiendaOlva(Request $request)
    {

        return (new PlantillaExportaListadoOlva)
            ->download('Lista de Envio tieda Olva.xlsx');
    }
}
