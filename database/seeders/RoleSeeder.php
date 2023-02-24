<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'Administrador']);
        $asesor = Role::create(['name' => 'Asesor']);
        $superasesor = Role::create(['name' => 'Super asesor']);
        $encargado = Role::create(['name' => 'Encargado']);
        $operario = Role::create(['name' => 'Operario']);
        $jefe = Role::create(['name' => 'Jefe de operaciones']);
        $administracion = Role::create(['name' => 'Administracion']);
        $logistica = Role::create(['name' => 'Logística']);
        $jefellamadas = Role::create(['name' => 'Jefe de llamadas']);

        //MODULO PEDIDOS
        Permission::create(['name' => 'pedidos.modulo', 'description' => 'MODULO PEDIDOS', 'modulo' => 'moduloPedidos'])->assignRole($admin);

        // PEDIDOS
        Permission::create(['name' => 'pedidos.index', 'description' => 'Ver Bandeja de Pedidos', 'modulo' => 'BandejaPedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.create', 'description' => 'Crear Pedido', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.show', 'description' => 'Detalle Pedido', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.edit', 'description' => 'Editar Pedido', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.destroy', 'description' => 'Eliminar Pedido', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.pedidosPDF', 'description' => 'Ver PDF Pedido', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.mispedidos', 'description' => 'Ver mis Pedidos', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.pagados', 'description' => 'Ver Pedidos Pagados', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.sinpagos', 'description' => 'Ver Pedidos Sin Pagos', 'modulo' => 'Pedidos'])->assignRole($admin);
        Permission::create(['name' => 'pedidos.exportar', 'description' => 'Exportar Pagos', 'modulo' => 'Pedidos'])->assignRole($admin);

        //MODULO OPERACION
        Permission::create(['name' => 'operacion.modulo', 'description' => 'MODULO OPERACION', 'modulo' => 'moduloOperacion'])->assignRole($admin);

        //OPERACIONES
        Permission::create(['name' => 'operacion.poratender', 'description' => 'Ver Pedidos Por Atender', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.enatencion', 'description' => 'Ver Pedidos En Atencion', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.atendidos', 'description' => 'Ver Pedidos Atendidos', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.atender', 'description' => 'Atender', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.editatender', 'description' => 'Editar Atencion', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.PDF', 'description' => 'Ver PDF', 'modulo' => 'Operacion'])->assignRole($admin);
        Permission::create(['name' => 'operacion.enviar', 'description' => 'Enviar', 'modulo' => 'Operacion'])->assignRole($admin);

        //MODULO ENVIOS
        Permission::create(['name' => 'envios.modulo', 'description' => 'MODULO ENVIOS', 'modulo' => 'moduloEnvio'])->assignRole($admin);

        //ENVIOS
        Permission::create(['name' => 'envios.index', 'description' => 'Ver Envios', 'modulo' => 'BandejaEnvio'])->assignRole($admin);//envios.index
        Permission::create(['name' => 'envios.enviados', 'description' => 'Ver Pedidos Enviados', 'modulo' => 'Envio'])->assignRole($admin);
        Permission::create(['name' => 'envios.verenvio', 'description' => 'Ver Envio', 'modulo' => 'Envio'])->assignRole($admin);
        Permission::create(['name' => 'envios.enviar', 'description' => 'Enviar Pedido', 'modulo' => 'Envio'])->assignRole($admin);
        Permission::create(['name' => 'envios.PDF', 'description' => 'Ver PDF', 'modulo' => 'Envio'])->assignRole($admin);

        //MODULO PAGOS
        Permission::create(['name' => 'pagos.modulo', 'description' => 'MODULO PAGOS', 'modulo' => 'moduloPagos'])->assignRole($admin);

        // PAGOS
        Permission::create(['name' => 'pagos.index', 'description' => 'Ver Bandeja de Pagos', 'modulo' => 'BandejaPagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.create', 'description' => 'Crear Pago', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.show', 'description' => 'Detalle Pago', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.edit', 'description' => 'Editar Pago', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.destroy', 'description' => 'Eliminar Pago', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.mispagos', 'description' => 'Ver Mis Pagos', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.pagosincompletos', 'description' => 'Ver Pagos Incompletos', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.pagosobservados', 'description' => 'Ver Pagos Observados', 'modulo' => 'Pagos'])->assignRole($admin);
        Permission::create(['name' => 'pagos.exportar', 'description' => 'Exportar Pagos', 'modulo' => 'Pagos'])->assignRole($admin);

        //MODULO ADMINISTRACION
        Permission::create(['name' => 'administracion.modulo', 'description' => 'MODULO ADMINISTRACION', 'modulo' => 'moduloAdministracion'])->assignRole($admin);

        //ADMINISTRACIONES
        Permission::create(['name' => 'administracion.porrevisar', 'description' => 'Ver Pagos Por Revisar', 'modulo' => 'Administracion'])->assignRole($admin);
        Permission::create(['name' => 'administracion.aprobados', 'description' => 'Ver Pagos Aprobados', 'modulo' => 'Administracion'])->assignRole($admin);
        Permission::create(['name' => 'administracion.show', 'description' => 'Detalle pago', 'modulo' => 'Administracion'])->assignRole($admin);
        Permission::create(['name' => 'administracion.revisar', 'description' => 'Revisar Pago', 'modulo' => 'Administracion'])->assignRole($admin);
        Permission::create(['name' => 'administracion.destroy', 'description' => 'Eliminar Pago', 'modulo' => 'Administracion'])->assignRole($admin);
        Permission::create(['name' => 'administracion.editpago', 'description' => 'Editar Pago', 'modulo' => 'Administracion'])->assignRole($admin);

        //MODULO PERSONAS
        Permission::create(['name' => 'personas.modulo', 'description' => 'MODULO PERSONAS', 'modulo' => 'moduloPersonas'])->assignRole($admin);

        // CLIENTES
        Permission::create(['name' => 'clientes.index', 'description' => 'Ver Clientes', 'modulo' => 'BandejaClientes'])->assignRole($admin);
        Permission::create(['name' => 'clientes.create', 'description' => 'Crear Cliente', 'modulo' => 'clientes'])->assignRole($admin);
        Permission::create(['name' => 'clientes.edit', 'description' => 'Editar Cliente', 'modulo' => 'clientes'])->assignRole($admin);
        Permission::create(['name' => 'clientes.destroy', 'description' => 'Eliminar Cliente', 'modulo' => 'clientes'])->assignRole($admin);
        Permission::create(['name' => 'clientes.exportar', 'description' => 'Exportar Cliente', 'modulo' => 'clientes'])->assignRole($admin);

        // BASE FRIA
        Permission::create(['name' => 'base_fria.index', 'description' => 'Ver Base fria', 'modulo' => 'BandejaBasefria'])->assignRole($admin);
        Permission::create(['name' => 'base_fria.create', 'description' => 'Crear Base fria', 'modulo' => 'Basefria'])->assignRole($admin);
        Permission::create(['name' => 'base_fria.edit', 'description' => 'Editar Base fria', 'modulo' => 'Basefria'])->assignRole($admin);
        Permission::create(['name' => 'base_fria.destroy', 'description' => 'Eliminar Base fria', 'modulo' => 'Basefria'])->assignRole($admin);
        Permission::create(['name' => 'base_fria.updatebf', 'description' => 'Convertir Base fria', 'modulo' => 'Basefria'])->assignRole($admin);
        Permission::create(['name' => 'base_fria.exportar', 'description' => 'Exportar Base fria', 'modulo' => 'Basefria'])->assignRole($admin);

        //MODULO REPORTES
        Permission::create(['name' => 'reporte.modulo', 'description' => 'MODULO REPORTES', 'modulo' => 'moduloReportes'])->assignRole($admin);

        // REPORTES
        Permission::create(['name' => 'reportes.index', 'description' => 'Ver Reporte General', 'modulo' => 'BandejaReportes'])->assignRole($admin);
        Permission::create(['name' => 'reportes.misasesores', 'description' => 'Ver Reporte de Mis Asesores', 'modulo' => 'Reportes'])->assignRole($admin);
        Permission::create(['name' => 'reportes.operaciones', 'description' => 'Ver Reporte de Operaciones', 'modulo' => 'Reportes'])->assignRole($admin);

        //MODULO CONFIGURACION
        Permission::create(['name' => 'configuracion.modulo', 'description' => 'MODULO CONFIGURACION', 'modulo' => 'moduloConfiguracion'])->assignRole($admin);

        // ROLES
        Permission::create(['name' => 'roles.index', 'description' => 'Ver Roles', 'modulo' => 'BandejaRoles'])->assignRole($admin);
        Permission::create(['name' => 'roles.create', 'description' => 'Crear Rol', 'modulo' => 'Roles'])->assignRole($admin);
        Permission::create(['name' => 'roles.edit', 'description' => 'Editar Rol', 'modulo' => 'Roles'])->assignRole($admin);
        Permission::create(['name' => 'roles.destroy', 'description' => 'Eliminar Rol', 'modulo' => 'Roles'])->assignRole($admin);

        // USUARIOS
        Permission::create(['name' => 'users.index', 'description' => 'Ver Usuarios', 'modulo' => 'BandejaUsuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.create', 'description' => 'Crear Usuario', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.edit', 'description' => 'Editar Usuario', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.destroy', 'description' => 'Eliminar Usuario', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.reset', 'description' => 'Resetear Contraseña Usuario', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.asesores', 'description' => 'Asignar Asesores A Supervisor', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.misasesores', 'description' => 'Asesores Asignados', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.encargados', 'description' => 'Ver Encargados', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.operarios', 'description' => 'Ver Operarios', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.misoperarios', 'description' => 'Operarios Asignados', 'modulo' => 'Usuarios'])->assignRole($admin);
        Permission::create(['name' => 'users.jefes', 'description' => 'Ver Jefes', 'modulo' => 'Usuarios'])->assignRole($admin);
        //Permission::create(['name' => 'users.mipersonal', 'description' => 'Ver Personal', 'modulo' => 'Usuarios'])->assignRole($admin);
    }
}
