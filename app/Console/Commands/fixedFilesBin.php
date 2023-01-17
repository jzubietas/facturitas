<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class fixedFilesBin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixed:bin';

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
        $pedidos = DireccionGrupo::query()->activo()->where('observacion', 'like', '%.bin')->get();
        foreach ($pedidos as $pedido) {
            $file = $pedido->observacion;
            if (\Storage::disk('pstorage')->exists($file)) {
                $new = \Str::replace(".bin", '.pdf', $file);
                \Storage::disk('pstorage')->move($file, $new);
                $pedido->update([
                    'observacion' => $new
                ]);
            }
        }
        return 0;
    }
}
