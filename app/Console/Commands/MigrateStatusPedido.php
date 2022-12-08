<?php

namespace App\Console\Commands;

use App\Models\Pago;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class MigrateStatusPedido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pedido:status';

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
        $migrateCondiciones = [
            'PAGO' => 1,
            'ABONADO' => 2,
            'ABONADO_PARCIAL' => 3,
            'ADELANTO' => 4,
            'PENDIENTE' => 5,
            'OBSERVADO' => 6,
        ];

        foreach ($migrateCondiciones as $status => $value) {
            Pago::query()->where('condicion', '=', $status)->update([
                "condicion" => $value
            ]);
        }
        \Schema::table('pagos', function (Blueprint $table) {
            $table->integer('condicion')->nullable()->change();
        });
        return 0;
    }
}
