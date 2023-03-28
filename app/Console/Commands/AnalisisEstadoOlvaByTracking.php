<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class AnalisisEstadoOlvaByTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizaestado:indivual {tracking}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //$cliente_id=Cliente::where('celular',$this->argument('celular'))->first()->id;
        $pedido=Pedido::where('env_tracking',$this->argument('tracking'))->first();
        $direccionGrupos = DireccionGrupo::whereIn('condicion_envio_code', [
            Pedido::RECEPCIONADO_OLVA_INT,
            Pedido::EN_CAMINO_OLVA_INT,
            Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            Pedido::NO_ENTREGADO_OLVA_INT,
        ])->where('id',$pedido->direccion_grupo)->get();
        $progress = $this->output->createProgressBar($direccionGrupos->count());
        foreach ($direccionGrupos as $grupo) {
            /*$pedido=Pedido::where('direccion_grupo',$grupo->id)->first();*/

            /*if (isset($pedido->env_tracking)){*/
                if (strpos($pedido->env_tracking, '-') !== false) {
                    $trackings= collect(explode(',', $pedido->env_tracking))->trim()->filter()->values();
                    $numerotrack="";
                    $aniotrack="";
                    foreach ($trackings as $item =>  $tracking) {
                        $tracking = explode('-', $tracking);
                    }
                    if (count($tracking) == 2) {
                        $numerotrack=$tracking[0];
                        $aniotrack=$tracking[1];
                        if ($tracking[0]!="" && $tracking[1]!=""){
                            $datosolva=$this->getconsultaolva(trim($numerotrack),trim($aniotrack));
                            $estado = data_get($datosolva, 'data.general.nombre_estado_tracking');
                            $grupo->update([
                                'direccion' => trim($numerotrack) . '-' . trim($aniotrack),
                                'courier_sync_at' => now(),
                                'courier_estado' => $estado,
                                'courier_data' => $datosolva,
                                'courier_failed_sync_at' => null,
                                'add_screenshot_at' => null,
                            ]);

                            $pedido->update([
                                'env_tracking' => trim($numerotrack) . '-' . trim($aniotrack),
                                'courier_sync_at' => now(),
                                'courier_estado' => $estado,
                                'courier_data' => $datosolva,
                                'courier_failed_sync_at' => null,
                            ]);
                            $this->info("( ". trim($numerotrack)." & ".trim($aniotrack)." GRUPO=> ".$grupo->id." PEDIDO=> ".$pedido->id." Estado=>".$estado." )" );
                            $progress->advance();
                        }
                    }



                }
            /*}*/
        }
        $this->info("Finish Cargando ");
        $progress->finish();
        $this->info('FIN');
    }

    public function getconsultaolva(string $tracking, string  $year)
    {
        $result = get_olva_tracking(trim($tracking), trim($year));
        return $result;
    }

}