<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class OlvaTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:olva-tracking-format';

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
        $grupos = DireccionGrupo::query()->inOlva()->get();
        foreach ($grupos as $grupo) {
            $code = $grupo->direccion;
            if(\Str::contains($code,'-')){
                if($grupo->courier_failed_sync_at!=null){
                    $grupo->update([
                        'courier_failed_sync_at' => null
                    ]);
                }
                continue;
            }
            $numreg = $grupo->referencia;
            if ($numreg && $code) {
                if (strlen($numreg) < strlen($code)) {
                    $code=$grupo->referencia;
                    $numreg=$grupo->direccion;
                    $grupo->update([
                       'referencia' => $numreg,
                        'direccion' => $code
                    ]);
                    $grupo->pedidos()->update([
                        'env_tracking' => $code,
                        'env_numregistro' => $numreg,
                    ]);
                }
            }
            $year = (int)substr($code, strlen($code) - 2, 2);
            $code = substr($code, 0, strlen($code) - strlen($year));
            $cyear = (int)now()->format('y');
            if (strlen($code) >= 4 && $year > 19 && $year <= $cyear) {
                $this->info("$code - $year");
                $grupo->update([
                    'direccion' => $code . '-' . $year,
                    'courier_failed_sync_at' => null
                ]);
                $grupo->pedidos()->update([
                    'env_tracking' => $grupo->direccion,
                ]);
            }else{
                $grupo->update([
                    'courier_failed_sync_at' => null
                ]);
            }
        }
        return 0;
    }
}
