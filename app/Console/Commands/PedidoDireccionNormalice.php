<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class PedidoDireccionNormalice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidos:normalice_dirgroup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $data = [];
    protected $remove_gruop = [];
    protected $cod_empresa = [];

    protected $progress;

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

        DireccionGrupo::query()->activo()
            ->orderBy('created_at')
            ->chunk(1000, function ($grupos) {
                foreach ($grupos as $grupo) {
                    $codigos = collect(explode(',', $grupo->codigos))->map(fn($cod) => trim($cod))->filter()->unique()->values();
                    $pedidos = Pedido::whereIn('pedidos.codigo', $codigos)->select(['pedidos.codigo', 'detalle_pedidos.nombre_empresa'])
                        ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                        ->activo()
                        ->pluck('detalle_pedidos.nombre_empresa', 'pedidos.codigo');

                    foreach ($pedidos as $cod => $emp) {
                        $this->cod_empresa[$cod] = $emp;
                    }

                    if ($codigos->count() != $pedidos->count()) {
                        $dif = $codigos->diff($pedidos->keys());
                        $result = $codigos->filter(fn($codigo) => !in_array($codigo, $dif->all()));
                        if ($result->count() == 0) {
                            $this->remove_gruop[] = $grupo->id;
                            $this->error("Grupo [" . $grupo->id . "] -> [" . $pedidos->count() . "]  codigos:  " . $codigos->join(', ') . '   --> remove : ' . $dif->join(', ') . '   --> result : ' . $result->join(', '));
                            $grupo->update([
                                'estado' => 0
                            ]);
                        } else {
                            $grupo->update([
                                'codigos' => $result->join(','),
                                'producto' => $result->map(fn($cod) => $this->cod_empresa[$cod])->join(','),
                            ]);
                            $this->info("Grupo [" . $grupo->id . "] -> [" . $pedidos->keys()->join(', ') . "]  codigos:  " . $codigos->join(', ') . '   --> remove : ' . $dif->join(', ') . '   --> result : ' . $result->join(', '));
                        }
                    } else {
                        $grupo->update([
                            'codigos' => $codigos->join(','),
                            'producto' => $codigos->map(fn($cod) => $this->cod_empresa[$cod])->join(','),
                        ]);
                    }
                    foreach ($pedidos as $codigo => $empresa) {
                        $this->data[$codigo][$grupo->id] = [
                            'grupo' => $grupo->refresh(),
                            'cantidad' => $pedidos->count()
                        ];
                    }
                }
            });

        $newdata = [];
        foreach ($this->data as $codigo => $result) {
            foreach ($result as $grupoId => $cantidad) {
                $newdata[$codigo][] = [
                    'codigo' => $codigo,
                    'grupo_id' => $grupoId,
                    'grupo' => $cantidad['grupo'],
                    'cantidad' => $cantidad['cantidad'],
                ];
            }
        }
        foreach ($newdata as $codigo => $result) {
            if (count($result) > 1) {
                $max = 0;
                $maxItem = null;
                foreach ($result as $item) {
                    if (data_get($item, 'cantidad') > $max) {
                        $maxItem = $item;
                        $max = data_get($max, 'cantidad');
                    }
                }
                if ($maxItem) {
                    $codigos = collect(explode(',', $maxItem['grupo']->codigos))->map(fn($cod) => trim($cod))->filter()->unique()->values();
                    Pedido::whereIn('codigo', $codigos)->activo()->update([
                        'direccion_grupo' => $maxItem['grupo']->id
                    ]);
                }
                collect($result)
                    ->filter(fn($item) => data_get($item, 'grupo_id') != data_get($maxItem, 'grupo_id'))
                    ->each(function ($item) use ($maxItem) {
                        $codigos = $item['grupo']->codigos;
                        $codigos = collect(explode(',', $codigos))->map(fn($cod) => trim($cod))->filter()->filter(fn($c) => $c != $maxItem['codigo'])->unique()->values();
                        if ($codigos->count() > 0) {
                            $item['grupo']->update([
                                'codigos' => $codigos->join(','),
                                'producto' => $codigos->map(fn($cod) => $this->cod_empresa[$cod])->join(','),
                            ]);
                        } else {
                            $item['grupo']->update([
                                'estado' => 0
                            ]);
                        }
                    });
            }else{
                foreach ($result as $item) {
                    $codigos = collect(explode(',', $item['grupo']->codigos))->map(fn($cod) => trim($cod))->filter()->unique()->values();
                    Pedido::whereIn('codigo', $codigos)->activo()->update([
                        'direccion_grupo' => $item['grupo']->id
                    ]);
                }

            }
        }
        return 0;
    }
}
