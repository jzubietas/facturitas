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

        \Schema::table('pagos', function (Blueprint $table) {
            $table->integer('condicion_code')->nullable();
        });

        foreach (Pago::$migrateCondiciones as $status => $code) {
            Pago::query()->where('condicion', '=', $status)->update([
                "condicion" => $status,
                "condicion_code" => $code,
            ]);
        }

        \Schema::create('devolucions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("pago_id");
            $table->unsignedInteger("client_id");
            $table->unsignedInteger("asesor_id")->comment("id usuario asesor");
            $table->string("bank_destino")->nullable();
            $table->string("bank_number")->nullable();
            $table->string("num_operacion")->nullable();

            $table->float("amount")->comment("monto a devolver");
            $table->integer("status")->default(\App\Models\Devolucion::PENDIENTE);
            $table->string("voucher_disk")->nullable();
            $table->text("voucher_path")->nullable();
            $table->timestamp("returned_at")->nullable();
            $table->timestamps();
        });

        return 0;
    }
}
