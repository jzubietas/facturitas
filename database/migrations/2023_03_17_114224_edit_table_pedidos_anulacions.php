<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditTablePedidosAnulacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            $table->renameColumn('foto_solicitud','files_asesor_ids');
            $table->renameColumn('foto_sol_encargado','files_encargado_ids');
            $table->renameColumn('foto_sol_admin','filesadmin_ids');
            $table->renameColumn('foto_jefeop_admin','files_jefeop_ids');
            $table->integer('estado_aprueba_asesor')->default(0)->comment('0: Creado, 1: Aprobando')->change();
            $table->integer('estado_aprueba_encargado')->default(0)->comment('0: Creado, 1: Aprobando')->change();
            $table->integer('estado_aprueba_administrador')->default(0)->comment('0: Creado, 1: Aprobando')->change();
            $table->integer('estado_aprueba_jefeop')->default(0)->comment('0: Creado, 1: Aprobando')->change();
            $table->decimal('total_anular',10,2);
            $table->char('tipo',1)->comment('C: Pedido Completo, F: Factura-Monto a anular');
            /*$table->dropColumn('body');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            //
        });
    }
}
