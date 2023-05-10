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
        $grupos = DireccionGrupo::query()->activo()->where('observacion', 'like', '%.bin')->get();
        $pedidos = Pedido::query()->activo()->where('env_rotulo', 'like', '%.bin')->get();
        $this->fixedName($grupos, 'grupos', 'observacion');
        $this->fixedName($pedidos, 'pedidos', 'env_rotulo');
        return 0;
    }

    public function fixedName($pedidos, $type, $key)
    {
        $this->warn("Cargando " . $type);
        $progress = $this->output->createProgressBar($pedidos->count());
        foreach ($pedidos as $pedido) {
            $file = $pedido->$key;
            if (\Storage::disk('pstorage')->exists($file)) {
                $new = \Str::replace(".bin", '.pdf', $file);
                \Storage::disk('pstorage')->move($file, $new);
                $pedido->update([
                    $key => $new
                ]);
            } else {
                $new = \Str::replace(".bin", '.pdf', $file);
                if (\Storage::disk('pstorage')->exists($new)) {
                    $pedido->update([
                        $key => $new
                    ]);
                }
            }
            $progress->advance();
        }
        $this->info("Finish Cargando " . $type);
        $progress->finish();
    }
}
