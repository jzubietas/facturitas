<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class MigrateSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:setting:table';

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
        $tablename = \Config::get('settings.table');
        $keyColumn = \Config::get('settings.keyColumn');
        $valueColumn = \Config::get('settings.valueColumn');

        if (!\Schema::hasTable($tablename)) {
            \Schema::create($tablename, function (Blueprint $table) use ($keyColumn, $valueColumn) {
                $table->increments('id');
                $table->string($keyColumn)->index();
                $table->text($valueColumn);
            });
        }

        \Schema::table('pedidos', function (Blueprint $table) {
            if (!\Schema::hasColumns('pedidos', ['path_adjunto_anular', 'path_adjunto_anular_disk', 'pendiente_anulacion', 'user_anulacion_id','fecha_anulacion'])) {
                $table->string("path_adjunto_anular")->nullable()->after('estado')->comment("archivo adjunto antes de analizar");
                $table->string("path_adjunto_anular_disk")->nullable()->after('path_adjunto_anular')->comment("disk archivo adjunto");
                $table->boolean("pendiente_anulacion")->default('0')->after('path_adjunto_anular_disk')->comment("estado para controlar la si esta pendiente de anulacion");
                $table->unsignedInteger("user_anulacion_id")->nullable()->after('pendiente_anulacion')->comment("Id del usuario que solicita la anulacion");
                $table->timestamp("fecha_anulacion")->nullable()->after('user_anulacion_id')->comment("Fecha de anulacion");
                $table->timestamp("fecha_anulacion_confirm")->nullable()->after('fecha_anulacion')->comment("Fecha de anulacion confirmada");
            }
        });
        return 0;
    }
}
