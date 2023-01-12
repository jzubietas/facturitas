<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class DoreccionGrupoNormalizar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'direcciongrupo:noramalizar';

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
        $pedidos = Pedido::query()->with('direcciongrupo')
            ->select('pedidos.*')
            ->join('direccion_grupos', 'direccion_grupos.id', 'pedidos.direccion_grupo')
            ->where('pedidos.estado', '0')
            ->where('direccion_grupos.estado', '1')
            ->get();

        $unico = [];
        foreach ($pedidos as $pedido) {
            $grupo = $pedido->direcciongrupo->pedidos()->count();
            if ($grupo == 1) {
                $pedido->direcciongrupo->update([
                    'estado' => 0
                ]);
            } elseif ($grupo > 1) {
                foreach ($pedido->direcciongrupo->pedidos()->activo(0)->get() as $p) {
                    $grupo = DireccionGrupo::desvincularPedido($pedido->direcciongrupo, $p, null, 0);
                    $grupo->update([
                        'estado' => 0
                    ]);
                }
            }else{
                $unico[]=$pedido->direcciongrupo->toArray();
            }
        }
        $this->info("Ped: " . count($unico));
/*
        $dirgroup=DireccionGrupo::query()
            ->leftJoin('pedidos','pedidos.direccion_grupo','direccion_grupos.id')
            ->groupBy('direccion_grupo.id')
            ->having()
            */
        return 0;
    }
}
