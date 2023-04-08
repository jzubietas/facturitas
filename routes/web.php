<?php

use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AnulacionController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CourierRegistrosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Envios\DireccionGrupoController;
use App\Http\Controllers\Envios\DistribucionController;
use App\Http\Controllers\Envios\MotorizadoController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\OlvaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PedidoHistoryController;
use App\Http\Controllers\Pedidos\PedidoStatusController;
use App\Http\Controllers\PedidosAnulacionController;
use App\Http\Controllers\RecojoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\BasefriaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\OperacionController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\EscaneoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified', 'auth.redirect.is_disabled'])->group(function () {
    Route::post('alertas/confirmar', [AlertaController::class, 'confirmar'])->name('alertas.confirmar');
    Route::resource('alertas', AlertaController::class);
    Route::any('alertas/cargarstore', [AlertaController::class, 'cargarstore'])->name('cargarstore');


    Route::post('escaneo/envio.escaneoqr/{id}', [EscaneoController::class, 'EscaneoQR'])->name('escaneo/envio.escaneoqr');
    Route::post('escaneo.estado_pedidos', [EscaneoController::class, 'EstadoSobresScan'])->name('escaneo.estado_pedidos');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/widgets-fetch', [DashboardController::class, 'widgets'])->name('dashboard.widgets');

    Route::get('/setting/administrador', [SettingsController::class, 'settingAdmin'])->name('settings.admin-settings');
    Route::post('/setting/administrador', [SettingsController::class, 'settingAdminStore'])->name('settings.store-admin-settings');
    Route::post('/setting/administrador/time-clientes', [SettingsController::class, 'settingTimeClienteStore'])->name('settings.store-time-clientes');

    Route::post('/setting/store', [SettingsController::class, 'settingStore'])->name('settings.store-setting');
    Route::post('/setting/store/agenda', [SettingsController::class, 'settingStoreAgenda'])->name('settings.store.agenda-setting');
    Route::post('/setting/authorizar/motorizado/{user}', [SettingsController::class, 'authorizationMotorizado'])->name('settings.authorization-motorizado');


    Route::get('/search/cliente', [DashboardController::class, 'searchCliente'])->name('dashboard.search-cliente');
    Route::get('/search/ruc', [DashboardController::class, 'searchRuc'])->name('dashboard.search-ruc');

    //Route::get('dashboard.graficoMetaTable', [DashboardController::class, 'graficoMetaTable'])->name('dashboard.graficoMetaTable');
    Route::any('dashboard.viewMetaTable', [DashboardController::class, 'viewMetaTable'])->name('dashboard.viewMetaTable');
    Route::any('dashboard.viewMetaTable.Recurrente.Activo', [DashboardController::class, 'viewMetaTableRecurrenteActivo'])->name('dashboard.viewMetaTable.Recurrente.Activo');

    Route::any('dashboard.viewAnalisis', [PdfController::class, 'Analisisgrafico'])->name('dashboard.viewAnalisis');
    Route::any('dashboard.graficoSituacionClientes', [PdfController::class, 'SituacionClientes'])->name('dashboard.graficoSituacionClientes');
    Route::any('dashboard.graficoCobranzasGeneral', [PdfController::class, 'CobranzasGeneral'])->name('dashboard.graficoCobranzasGeneral');


//Route::get('image-upload-preview', [PagoController::class, 'indexpreview'])->name('image-upload-preview');
//Route::post('upload-image', [PagoController::class, 'storeimage'])->name('upload-image');
//Route::resource('clientes.recurrentes', [ClienteController::class, 'Recurrentes'])->name('clientes.recurrentes');
//Route::get('recurrentestabla', [ClienteController::class, 'tablarecurrentes'])->name('recurrentestabla');

    /*Controller Clientes*/
    Route::get('clientes/deudas_copy', [ClienteController::class, 'deudasCopyAjax'])->name('clientes.deudas_copy');


    Route::resource('clientes', ClienteController::class)->names('clientes');

    /* agregando rutas para permisos en opciones del modulo clientes (editar cada secciòn) */

    Route::get('clientes.edit.recuperado/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.recuperado');
    Route::get('clientes.edit.recuperado.reciente/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.recuperado.reciente');
    Route::get('clientes.edit.nuevo/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.nuevo');
    Route::get('clientes.edit.abandono/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.abandono');
    Route::get('clientes.edit.abandono.reciente/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.abandono.reciente');
    Route::get('clientes.edit.recurrente/{cliente}/edit2', [ClienteController::class, 'edit'])->name('clientes.edit.recurrente');


    Route::get('pedidosenvioclientetabla', [ClienteController::class, 'pedidosenvioclientetabla'])->name('cargar.pedidosenvioclientetabla');
    Route::get('clientescreatepago', [ClienteController::class, 'clientedeasesorpagos'])->name('clientescreatepago');


    Route::get('recojolistclientes', [PedidoController::class, 'recojolistclientes'])->name('cargar.recojolistclientes');
    //Route::get('clientes.createbf', [BasefriaController::class, 'createbf'])->name('clientes.createbf');
    //Route::post('clientes.storebf', [BasefriaController::class, 'storebf'])->name('clientes.storebf');

    Route::post('cliente.edit.celularduplicado', [ClienteController::class, 'celularduplicado'])->name('cliente.edit.celularduplicado');


    Route::middleware('authorize.pedido.anulled')
        ->post('clientedeleteRequest', [ClienteController::class, 'destroyid'])
        ->name('clientedeleteRequest.post');

    Route::post('correccionconfirmacionRequest', [OperacionController::class, 'correccionconfirmacion'])->name('correccionconfirmacionRequest.post');
    Route::post('correccionrechazoRequest', [OperacionController::class, 'correccionrechazo'])->name('correccionrechazoRequest.post');

    //Route::post('clientedeleteRequest', [ClienteController::class, 'destroyid'])->name('clientedeleteRequest.post');
    Route::any('clientedeasesor', [ClienteController::class, 'clientedeasesor'])->name('cargar.clientedeasesor');
    Route::get('clientedeasesorparapagos', [ClienteController::class, 'clientedeasesor'])->name('cargar.clientedeasesorparapagos');

    Route::get('clientestabla', [ClienteController::class, 'indextabla'])->name('clientestabla');
    Route::get('pedidos.recoger.clientes', [ClienteController::class, 'clienteslistarecoger'])->name('pedidos.recoger.clientes');
    Route::get('pedidos.recoger.clientes.pedidos', [ClienteController::class, 'pedidosclienteslistarecoger'])->name('pedidos.recoger.clientes.pedidos');
    Route::get('pedidos.recoger.clientes.pedidos.historial', [ClienteController::class, 'historialrecoger'])->name('pedidos.recoger.clientes.pedidos.historial');
    Route::get('clientestablasituacion', [ClienteController::class, 'clientestablasituacion'])->name('clientestablasituacion');
    Route::get('listtablecontactos', [ClienteController::class, 'listtablecontactos'])->name('listtablecontactos');
    Route::post('alertas/guardado', [ClienteController::class, 'guardado'])->name('alertas.guardado');
    Route::post('alertas/confirmado', [ClienteController::class, 'confirmado'])->name('alertas.confirmado');
    Route::post('enviarconfirmado', [ClienteController::class, 'reconfirmado'])->name('enviarreconfirmado');
    Route::post('agregarcontactonuevo', [ClienteController::class, 'agregarcontactonuevo'])->name('agregarcontactonuevo');
    Route::post('solicitabloqueocliente', [ClienteController::class, 'solicitabloqueocliente'])->name('solicitabloqueocliente');
    Route::post('clientecomboagregarcontacto', [ClienteController::class, 'ClienteAgregarContacto'])->name('clientecomboagregarcontacto');
    Route::post('clientecomboagregarcontactobloqueo', [ClienteController::class, 'ClienteAgregarContactobloqueo'])->name('clientecomboagregarcontactobloqueo');
    Route::post('cambiarnombrecontacto', [ClienteController::class, 'cambiarnombrecontacto'])->name('cambiarnombrecontacto');
    Route::post('cambiarnumerocontacto', [ClienteController::class, 'cambiarnumerocontacto'])->name('cambiarnumerocontacto');
    Route::get('listcontadorescontactos', [ClienteController::class, 'listcontadorescontactos'])->name('listcontadorescontactos');

    Route::get('pedidos.recojo', [PedidoController::class, 'pedidosrecojo'])->name('pedidos.recojo');
    Route::get('pedidosrecojotabla', [PedidoController::class, 'indexrecojotabla'])->name('pedidosrecojotabla');//actualizado para serverside

    Route::get('pedidos.anulaciones', [AnulacionController::class, 'pedidosanulaciones'])->name('pedidos.anulaciones');

    Route::any('pedidosanulaciones.modal.agregaranulacion_pc.save', [AnulacionController::class, 'modalsAnulacionPCSave'])->name('pedidosanulaciones.modal.agregaranulacion_pc.save');
    Route::any('pedidosanulaciones.modal.agregaranulacion_f.save', [AnulacionController::class, 'modalsAnulacionFSave'])->name('pedidosanulaciones.modal.agregaranulacion_f.save');

    Route::get('registros.asesor.lista', [EnvioController::class, 'registrosasesor'])->name('registros.asesor.lista');

    Route::get('clientes.abandonos', [ClienteController::class, 'indexabandono'])->name('clientes.abandonos');

    Route::get('clientes.abandonos.recientes', [ClienteController::class, 'indexRecientes'])->name('clientes.abandonos.recientes');

    Route::get('clientes.abandonos.recientes.abandono', [ClienteController::class, 'indexRecientesIntermedio'])->name('clientes.abandonos.recientes.abandono');

    Route::get('clientesabandonotabla', [ClienteController::class, 'indexabandonotabla'])->name('clientesabandonotabla');
    Route::get('clientesabandonointermediotabla', [ClienteController::class, 'indexabandonointermediotabla'])->name('clientesabandonointermediotabla');

    Route::get('clientes.recurrentes', [ClienteController::class, 'indexrecurrente'])->name('clientes.recurrentes');
    Route::get('clientesrecurrentetabla', [ClienteController::class, 'indexrecurrentetabla'])->name('clientesrecurrentetabla');

    Route::get('clientes.activos', [ClienteController::class, 'indexactivo'])->name('clientes.activos');
    Route::get('clientesactivotabla', [ClienteController::class, 'indexactivotabla'])->name('clientesactivotabla');

    Route::get('clientes.levantados', [ClienteController::class, 'indexlevantado'])->name('clientes.levantados');
    Route::get('clienteslevantadotabla', [ClienteController::class, 'indexlevantadotabla'])->name('clienteslevantadotabla');

    Route::get('clientes.nuevos', [ClienteController::class, 'indexnuevo'])->name('clientes.nuevos');
    Route::get('clientesnuevotabla', [ClienteController::class, 'indexnuevotabla'])->name('clientesnuevotabla');

    Route::get('clientes.recuperados', [ClienteController::class, 'indexrecuperado'])->name('clientes.recuperados');
    Route::get('clientes.recuperados.recientes', [ClienteController::class, 'indexRecuperadoRecientes'])->name('clientes.recuperados.recientes');
    Route::get('recuperadostabla', [ClienteController::class, 'indexrecuperadotabla'])->name('recuperadostabla');

    Route::get('clientes.nulos', [ClienteController::class, 'indexnulo'])->name('clientes.nulos');
    Route::get('nulostabla', [ClienteController::class, 'indexnulotabla'])->name('nulostabla');

    /*Controller Cliente*/

    /*Controller Basefria*/

    Route::resource('basefria', BasefriaController::class)->names('basefria');
    Route::post('basefria.edit.celularduplicado', [BasefriaController::class, 'celularduplicado'])->name('basefria.edit.celularduplicado');
    Route::get('clientes.editbf/{cliente}/edit2', [BasefriaController::class, 'editbf'])->name('clientes.editbf');


    Route::middleware('authorize.pedido.anulled')
        ->post('basefriadeleteRequest', [BasefriaController::class, 'destroyid'])
        ->name('basefriadeleteRequest.post');


    Route::post('basefriacliente/{cliente}', [BasefriaController::class, 'updatebf'])->name('updatebf');

    Route::post('basefriaclienteRequest', [BasefriaController::class, 'updatebfpost'])->name('basefriaRequest.post');

    Route::get('basefria', [BasefriaController::class, 'index'])->name('basefria');//actualizado para serverside

    Route::get('basefriatabla', [BasefriaController::class, 'indextabla'])->name('basefriatabla');//actualizado para serverside
    Route::get('basefria.cargarid', [BasefriaController::class, 'cargarid'])->name('basefria.cargarid');
    /*Controller Basefria*/

    /*Controller User*/
    Route::resource('users', UserController::class)->names('users');

    Route::get('users.profile', [UserController::class, 'profile'])->name('users.profile');
    Route::get('users.miperfil', [UserController::class, 'miperfil'])->name('users.miperfil');
    Route::post('users.updateprofile', [UserController::class, 'updateprofile'])->name('users.updateprofile');
    Route::post('users.updateimage', [UserController::class, 'updateimage'])->name('users.updateimage');
    //Route::get('users.misasesores', [UserController::class, 'MisAsesores'])->name('users.misasesores');

    Route::any('correccionesJson', [OperacionController::class, 'correccionesJson'])->name('correccionesJson');

    Route::post('user.reset', [UserController::class, 'reset'])->name('user.reset');
    Route::post('user.cambiarestado', [UserController::class, 'cambiarestado'])->name('user.cambiarestado');

    Route::post('asesorcombomodal', [UserController::class, 'AsesorcomboModal'])->name('asesorcombomodal');
    Route::post('asesorcombo', [UserController::class, 'Asesorcombo'])->name('asesorcombo');
    Route::post('asesorcombopago', [UserController::class, 'Asesorcombopago'])->name('asesorcombopago');
    Route::get('users.asesores', [UserController::class, 'Asesores'])->name('users.asesores');
    Route::get('users.asesorestabla', [UserController::class, 'Asesorestabla'])->name('users.asesorestabla');////asesores
    Route::post('users.asesorestabla/{user}', [UserController::class, 'AsesoresTablaMeta'])->name('users.asesorestabla.updatemeta');////asesores
    Route::any('lstusuariosvidas', [UserController::class, 'lstusuariosvidas'])->name('lstusuariosvidas');
    Route::post('quitarvidasusuario', [UserController::class, 'quitarvidasusuario'])->name('quitarvidasusuario');
    Route::get('getvidasusuario', [UserController::class, 'getvidasusuario'])->name('getvidasusuario');
    Route::get('resetllamadaatencionsusuario', [UserController::class, 'resetllamadaatencionsusuario'])->name('resetllamadaatencionsusuario');
    Route::get('getComboAsesor', [UserController::class, 'getComboAsesor'])->name('getComboAsesor');
    Route::get('getComboCliente', [UserController::class, 'getComboCliente'])->name('getComboCliente');
    Route::get('getComboRuc', [UserController::class, 'getComboRuc'])->name('getComboRuc');
    Route::get('getComboClientes', [UserController::class, 'getComboClientes'])->name('getComboClientes');
    Route::post('updateNameEmpresa', [UserController::class, 'updateNameEmpresa'])->name('updateNameEmpresa');
    Route::post('updateRuc', [UserController::class, 'updateRuc'])->name('updateRuc');
    Route::post('getPorcClientes', [UserController::class, 'getPorcClientes'])->name('getPorcClientes');
    Route::post('uptPorcClientes', [UserController::class, 'uptPorcClientes'])->name('uptPorcClientes');
    Route::post('getComboNuevoCliente', [ClienteController::class, 'getComboNuevoCliente'])->name('getComboNuevoCliente');
    Route::post('getRelacionNuevoCliente', [ClienteController::class, 'getRelacionNuevoCliente'])->name('getRelacionNuevoCliente');
    Route::post('setDatosNuevoClientes', [ClienteController::class, 'setDatosNuevoClientes'])->name('setDatosNuevoClientes');

    Route::get('users.llamadas', [UserController::class, 'Llamadas'])->name('users.llamadas');////llamadas
    Route::get('users.llamadastabla', [UserController::class, 'Llamadastabla'])->name('users.llamadastabla');////llamadas

    Route::post('users.asignarsupervisor/{user}', [UserController::class, 'AsignarSupervisor'])->name('users.asignarsupervisor');
    Route::post('users.asignarasesorpost', [UserController::class, 'AsignarAsesorpost'])->name('users.asignarasesorpost');////
    Route::post('users.asignarencargadopost', [UserController::class, 'AsignarEncargadopost'])->name('users.asignarencargadopost');////
    Route::post('users.asignarjefellamadaspost', [UserController::class, 'AsignarJefellamadaspost'])->name('users.asignarjefellamadaspost');////
    Route::post('users.asignarllamadaspost', [UserController::class, 'AsignarLlamadaspost'])->name('users.asignarllamadaspost');


    Route::post('users.asignaroperario/{user}', [UserController::class, 'AsignarOperario'])->name('users.asignaroperario');
    Route::post('users.asignaroperariopost', [UserController::class, 'AsignarOperariopost'])->name('users.asignaroperariopost');

    Route::post('users.asignarsupervisorpost', [UserController::class, 'AsignarSupervisorpost'])->name('users.asignarsupervisorpost');
    Route::post('users.asignarasesorpost', [UserController::class, 'AsignarAsesorpost'])->name('users.asignarasesorpost');////

    Route::post('users.asignarjefe/{user}', [UserController::class, 'AsignarJefe'])->name('users.asignarjefe');
    Route::get('users.misasesores', [UserController::class, 'MisAsesores'])->name('users.misasesores');
    Route::post('users.asignarmetaasesor/{user}', [UserController::class, 'AsignarMetaAsesor'])->name('users.asignarmetaasesor');

    Route::post('users.asignarmetaasesorPost', [UserController::class, 'AsignarMetaAsesor'])->name('users.asignarmetaasesorPost');
    Route::post('users.asignarmetallamadaPost', [UserController::class, 'AsignarMetaLlamada'])->name('users.asignarmetallamadaPost');

    Route::post('users.getmetallamadas', [UserController::class, 'ConsultarMetaLlamada'])->name('users.getmetallamadas');


    Route::get('users.encargados', [UserController::class, 'Encargados'])->name('users.encargados');
    Route::post('users.asignarmetaencargado/{user}', [UserController::class, 'AsignarMetaEncargado'])->name('users.asignarmetaencargado');


    Route::get('users.operarios', [UserController::class, 'Operarios'])->name('users.operarios');
    Route::get('users.misoperarios', [UserController::class, 'MisOperarios'])->name('users.misoperarios');
    Route::get('users.jefes', [UserController::class, 'Jefes'])->name('users.jefes');
    Route::get('users.mipersonal', [UserController::class, 'MiPersonal'])->name('users.mipersonal');
    Route::get('personaltablahistorial', [UserController::class, 'personaltablahistorial'])->name('personaltablahistorial');//actualizado para serverside
    Route::get('indextablapersonal', [UserController::class, 'indextablapersonal'])->name('indextablapersonal');
    Route::get('tableUsuarios', [UserController::class, 'tableUsuarios'])->name('tableUsuarios');
    /*Controller User*/

    /*Controller Pedido*/

    Route::get('pedidos/estados/anulados', [PedidoStatusController::class, 'anulados'])->name('pedidos.estados.anulados');
    Route::get('pedidos/estados/{pedido}/detalle_atencion', [PedidoStatusController::class, 'pedidoDetalleAtencion'])->name('pedidos.estados.detalle-atencion');
    Route::post('pedidos/estados/{pedido}/detalle_atencion', [PedidoStatusController::class, 'pedidoDetalleAtencionConfirm']);

    Route::get('pedidos/estados', [PedidoStatusController::class, 'index'])->name('pedidos.estados.index');

    Route::get('pedidos/estados/porAtender', [PedidoStatusController::class, 'PorAtender'])->name('pedidos.estados.poratender');
    Route::get('pedidos/estados/Atendidos', [PedidoStatusController::class, 'Atendidos'])->name('pedidos.estados.atendidos');

    Route::post('pedidos.confirm.anulled', [PedidoController::class, 'ConfirmarAnular'])->name('pedidos.confirmar.anular');

    Route::post('pedidos/histories/store', [PedidoHistoryController::class, 'store'])->name('pedidos.store.save-history');
    Route::post('pedidos/histories/delete', [PedidoHistoryController::class, 'delete'])->name('pedidos.store.delete-history');
    Route::get('pedidos/histories', [PedidoHistoryController::class, 'index'])->name('pedidos.histories.index');

    Route::resource('pedidos', PedidoController::class)->names('pedidos');
    Route::post('pedidoss.store', [PedidoController::class, 'pedidosstore'])->name('pedidoss.store');//actualizado para serverside
    Route::get('pedidostabla', [PedidoController::class, 'indextabla'])->name('pedidostabla');//actualizado para serverside
    Route::post('validarpedido', [PedidoController::class, 'validadContenidoPedido'])->name('validarpedido');

    Route::get('pedidosperdonarcurrier', [PedidoController::class, 'indexperdonarcurrier'])->name('pedidosperdonarcurrier');
    Route::get('pedidosperdonarcurriertabla', [PedidoController::class, 'indexperdonarcurriertabla'])->name('pedidosperdonarcurriertabla');//actualizado para serverside

    Route::get('pedidostablahistorial', [PedidoController::class, 'indextablahistorial'])->name('pedidostablahistorial');//actualizado para serverside
    Route::get('deudoresoncreate', [PedidoController::class, 'deudoresoncreate'])->name('deudoresoncreate');//actualizado para serverside
    Route::get('mispedidostabla', [PedidoController::class, 'mispedidostabla'])->name('mispedidostabla');//actualizado para serverside
    Route::get('ruc', [PedidoController::class, 'ruc'])->name('cargar.ruc');
    Route::get('rucnombreempresa', [PedidoController::class, 'rucnombreempresa'])->name('rucnombreempresa');
    Route::post('pedidos.infopdf', [PedidoController::class, 'infopdf'])->name('pedidos.infopdf');
    Route::middleware('authorize.pedido.anulled')
        ->post('pedidodeleteRequest', [PedidoController::class, 'destroyid'])
        ->name('pedidodeleteRequest.post');
    Route::post('getContadoresOlva', [PedidoController::class, 'getContadoresOlva'])->name('getContadoresOlva');

//Route::get('pedidos.destroyid', [PedidoController::class, 'destroyid'])->name('pedidos.destroyid');

    Route::post('pedidorestaurarRequest', [PedidoController::class, 'Restaurarid'])->name('pedidorestaurarRequest.post');
    Route::post('pedidos.restaurar/{pedido}', [PedidoController::class, 'Restaurar'])->name('pedidos.restaurar');


    Route::get('cliente', [PedidoController::class, 'cliente'])->name('cargar.cliente');

    Route::get('asesortiempo', [PedidoController::class, 'asesortiempo'])->name('asesortiempo');

    Route::get('clientedeasesordeuda', [PedidoController::class, 'clientedeasesordeuda'])->name('cargar.clientedeasesordeuda');
    Route::get('clientedeudaparaactivar', [PedidoController::class, 'clientedeudaparaactivar'])->name('cargar.clientedeudaparaactivar');
    Route::get('clientemodal1', [PedidoController::class, 'clientemodal1'])->name('cargar.clientemodal1');
    Route::get('tipobanca', [PedidoController::class, 'tipobanca'])->name('cargar.tipobanca');
    Route::post('pedidos.agregarruc', [PedidoController::class, 'AgregarRuc'])->name('pedidos.agregarruc');
    Route::get('pedidos.mispedidos', [PedidoController::class, 'MisPedidos'])->name('pedidos.mispedidos');
    Route::get('pedidos.pagados', [PedidoController::class, 'Pagados'])->name('pedidos.pagados');
    Route::get('pedidos.pagadostabla', [PedidoController::class, 'Pagadostabla'])->name('pedidos.pagadostabla');
    Route::get('pedidos.sinpagos', [PedidoController::class, 'SinPagos'])->name('pedidos.sinpagos');
    Route::get('pedidos.sinpagostabla', [PedidoController::class, 'SinPagostabla'])->name('pedidos.sinpagostabla');

    Route::get('datatable.cargaratendidos', [PedidoController::class, 'cargarAtendidos'])->name('datatable.cargaratendidos');
    Route::post('pedidos.destino/{pedido}', [PedidoController::class, 'Destino'])->name('pedidos.destino');
    Route::post('pedidos/eliminarAdjunto/{id}', [PedidoController::class, 'eliminarAdjunto'])->name('pedidos.eliminarAdjunto');
    Route::get('pedidos/{adjunto}/descargaradjunto', [PedidoController::class, 'DescargarAdjunto'])->name('pedidos.descargaradjunto');
    Route::get('pedidos/{adjunto}/descargargastos', [PedidoController::class, 'DescargarGastos'])->name('pedidos.descargargastos');

    Route::get('pedidos/envios/get_direccion_grupo', [EnvioController::class, 'getDireccionEnvio'])->name('pedidos.envios.get-direccion');
    Route::post('pedidos/envios/update_direccion_grupo', [EnvioController::class, 'updateDireccionGrupo'])->name('pedidos.envios.update-direccion');

    Route::post('envios.direccion', [EnvioController::class, 'DireccionEnvio'])->name('envios.direccion');
    Route::post('envios.desvincular', [SobreController::class, 'EnvioDesvincular'])->name('envios.desvincular');

    Route::post('registrar_recojer_pedido', [RecojoController::class, 'RegistrarRecojo'])->name('registrar_recojer_pedido');

    Route::get('envios.createdireccion/{pedido}', [EnvioController::class, 'createDireccion'])->name('envios.createdireccion');
    Route::post('envios.updatedireccion/{direccion}', [EnvioController::class, 'UpdateDireccionEnvio'])->name('envios.updatedireccion');


    Route::post('pedidos/eliminarFoto1/{pedido}', [PedidoController::class, 'eliminarFoto1'])->name('pedidos.eliminarFoto1');
    Route::post('pedidos/eliminarFoto2/{pedido}', [PedidoController::class, 'eliminarFoto2'])->name('pedidos.eliminarFoto2');
    Route::get('envios/{imagen}/descargarimagen', [PedidoController::class, 'DescargarImagen'])->name('envios.descargarimagen');
    Route::post('validarrelacionruc', [PedidoController::class, 'validarrelacionruc'])->name('validarrelacionruc');

    Route::post('pedidoobteneradjuntoRequest', [PedidoController::class, 'pedidoobteneradjuntoRequest'])->name('pedidoobteneradjuntoRequest');
    Route::post('pedidoobteneradjuntoOPRequest', [PedidoController::class, 'pedidoobteneradjuntoOPRequest'])->name('pedidoobteneradjuntoOPRequest');
    Route::post('correccionobteneradjuntoRequest', [PedidoController::class, 'correccionobteneradjuntoRequest'])->name('correccionobteneradjuntoRequest');

    /*Controller Pedido */

    /*Envios */
    Route::get('envios.recepcionmotorizado', [MotorizadoController::class, 'Enviosrecepcionmotorizado'])->name('envios.recepcionmotorizado');
    Route::get('envios.recepcionmotorizado/{grupo}/pedidos', [MotorizadoController::class, 'getPedidos'])->name('envios.recepcionmotorizado.pedidos');
    Route::get('envios.recepcionmotorizadotabla', [MotorizadoController::class, 'Enviosrecepcionmotorizadotabla'])->name('envios.recepcionmotorizadotabla');
    Route::get('envios.recepcionmotorizadotablageneral', [MotorizadoController::class, 'EnviosrecepcionmotorizadotablaGeneral'])->name('envios.recepcionmotorizadotablageneral');

    Route::get('envios.porconfirmar', [EnvioController::class, 'Enviosporconfirmar'])->name('envios.porconfirmar');
    Route::get('envios.porconfirmartabla', [EnvioController::class, 'Enviosporconfirmartabla'])->name('envios.porconfirmartabla');

    Route::get('envios.porrecibir', [EnvioController::class, 'Enviosporrecibir'])->name('envios.porrecibir');
    Route::get('envios.porrecibirtabla', [EnvioController::class, 'Enviosporrecibirtabla'])->name('envios.porrecibirtabla');
    Route::get('envios.rutaenvio', [EnvioController::class, 'Enviosrutaenvio'])->name('envios.rutaenvio');
    Route::get('envios.rutaenviotabla', [EnvioController::class, 'Enviosrutaenviotabla'])->name('envios.rutaenviotabla');
    Route::get('envios.rutaenvio/merge_routulos', [EnvioController::class, 'downloadRotulosEnviosrutaenvio'])->name('envios.rutaenvio.merge-routulos');

    Route::get('envios.condireccion', [EnvioController::class, 'Envioscondireccion'])->name('envios.condireccion');
    Route::get('envios.condirecciontabla', [EnvioController::class, 'Envioscondirecciontabla'])->name('envios.condirecciontabla');
    Route::get('envios.sindireccion', [EnvioController::class, 'Enviossindireccion'])->name('envios.sindireccion');
    Route::get('envios.sindirecciontabla', [EnvioController::class, 'Enviossindirecciontabla'])->name('envios.sindirecciontabla');

    Route::get('envios.parareparto', [EnvioController::class, 'Enviosparareparto'])->name('envios.parareparto');
    Route::get('envios.pararepartotabla', [EnvioController::class, 'Enviospararepartotabla'])->name('envios.pararepartotabla');


    Route::get('envios.enreparto', [EnvioController::class, 'Enviosenreparto'])->name('envios.enreparto');
    Route::get('envios.enrepartotabla', [EnvioController::class, 'Enviosenrepartotabla'])->name('envios.enrepartotabla');


    Route::get('envios.matchrotulos', [EnvioController::class, 'MatchRotulos'])->name('envios.matchrotulos');
    Route::get('envios.matchRotulostabla', [EnvioController::class, 'MatchRotulostabla'])->name('envios.matchRotulostabla');


    Route::get('envios/olva', [OlvaController::class, 'index'])->name('envios.olva.index');
    Route::post('envios/olva/{grupo}', [OlvaController::class, 'store'])->name('envios.olva.store');
    Route::get('envios/olva/datatable', [OlvaController::class, 'table'])->name('envios.olva.table');
    Route::get('envios/olva/olvanoentregado', [OlvaController::class, 'volvanoentregado'])->name('envios.olva.olvanoentregado');
    Route::get('envios/olva/olvaextraviado', [OlvaController::class, 'volvaextraviado'])->name('envios.olva.olvaextraviado');
    Route::get('envios/olva/noentregado', [OlvaController::class, 'tblolvanoentregado'])->name('envios.olva.noentregado');
    Route::get('envios/olva/extraviado', [OlvaController::class, 'tblolvaextraviado'])->name('envios.olva.extraviado');
    Route::post('gettimeline_estados_olva', [OlvaController::class, 'gettimelineestadosolva'])->name('gettimeline_estados_olva');

    Route::get('envios.seguimientoprovincia', [OlvaController::class, 'Seguimientoprovincia'])->name('envios.seguimientoprovincia');
    Route::get('envios.seguimientoprovinciatabla', [OlvaController::class, 'Seguimientoprovinciatabla'])->name('envios.seguimientoprovinciatabla');
    Route::post('envios.seguimientoprovincia.update', [OlvaController::class, 'SeguimientoprovinciaUpdate'])->name('envios.seguimientoprovincia.update');
    Route::get('envios.seguimientoprovincia/{grupo}/history_encargado', [OlvaController::class, 'SeguimientoprovinciaHistoryEncargado'])
        ->name('envios.seguimientoprovincia.history_encargado');


    Route::get('envios.entregados', [EnvioController::class, 'Entregados'])->name('envios.entregados');
    Route::get('envios.entregadostabla', [EnvioController::class, 'Entregadostabla'])->name('envios.enviadostabla');

    Route::post('envio.escaneoqr/{id}', [EnvioController::class, 'EscaneoQR'])->name('envio.escaneoqr');
    Route::post('envio.recibirpedidomotorizado', [EnvioController::class, 'RecibirPedidoMotorizado'])->name('envio.recibirpedidomotorizado');


    Route::get('operaciones/recojos', [RecojoController::class, 'index'])->name('operaciones.recojos.index');


    Route::get('envios/motorizados', [MotorizadoController::class, 'index'])->name('envios.motorizados.index');
    Route::get('envios/motorizados/confirmar', [MotorizadoController::class, 'confirmar'])->name('envios.motorizados.confirmar');
    Route::get('envios/motorizados/confirmar-cliente', [MotorizadoController::class, 'confirmar_cliente'])->name('envios.motorizados.confirmar.cliente');
    Route::post('envios/motorizados/{grupo}/revertir', [MotorizadoController::class, 'revertir'])->name('envios.motorizados.revertir');
    Route::post('envios/motorizados/{grupo}/reprogramar', [MotorizadoController::class, 'reprogramar'])->name('envios.motorizados.reprogramar');

    Route::get('envios.devueltos', [MotorizadoController::class, 'devueltos'])->name('envios.devueltos');
    Route::get('envios.datasobresdevueltos', [MotorizadoController::class, 'devueltos_datatable'])->name('envios.datasobresdevueltos');
    Route::post('envios.devueltos/{pedido}/recibir', [MotorizadoController::class, 'devueltos_recibir'])->name('envios.devueltos.recibir');


    /*Envios routes */

    /*Operaciones*/
    Route::get('operaciones.poratender', [OperacionController::class, 'PorAtender'])->name('operaciones.poratender');
    Route::get('operaciones.poratendertabla', [OperacionController::class, 'PorAtendertabla'])->name('operaciones.poratendertabla');

    Route::get('operaciones.enatencion', [OperacionController::class, 'EnAtencion'])->name('operaciones.enatencion');
    Route::get('operaciones.enatenciontabla', [OperacionController::class, 'EnAtenciontabla'])->name('operaciones.enatenciontabla');

    Route::get('operaciones.atendidos', [OperacionController::class, 'Atendidos'])->name('operaciones.atendidos');
    Route::get('operaciones.atendidostabla', [OperacionController::class, 'Atendidostabla'])->name('operaciones.atendidostabla');
    Route::get('operaciones.entregados', [OperacionController::class, 'Entregados'])->name('operaciones.entregados');
    Route::get('operaciones.entregadostabla', [OperacionController::class, 'Entregadostabla'])->name('operaciones.entregadostabla');
    Route::get('operaciones.terminados', [OperacionController::class, 'Terminados'])->name('operaciones.terminados');
    Route::get('operaciones.terminadostabla', [OperacionController::class, 'Terminadostabla'])->name('operaciones.terminadostabla');
    Route::get('operaciones.bancarizacion', [OperacionController::class, 'Bancarizacion'])->name('operaciones.bancarizacion');
    Route::get('operaciones.bancarizaciontabla', [OperacionController::class, 'Bancarizaciontabla'])->name('operaciones.bancarizaciontabla');
    Route::post('operaciones.atenderid', [OperacionController::class, 'Atenderid'])->name('operaciones.atenderid');
    Route::post('operaciones.correccionajax', [OperacionController::class, 'CorreccionAccion'])->name('operaciones.correccionajax');

    Route::get('operaciones.correcciones', [OperacionController::class, 'Correcciones'])->name('operaciones.correcciones');
    Route::get('operaciones.correccionestabla', [OperacionController::class, 'Correccionestabla'])->name('operaciones.correccionestabla');

    Route::post('envios.recepcionmotorizado.iniciar_ruta_masiva', [EnvioController::class, 'IniciarRutaMasiva'])->name('envios.recepcionmotorizado.iniciar_ruta_masiva');

    Route::post('operaciones.cargarimagenes.correccion/{pedido}', [OperacionController::class, 'cargarImagenCorreccion'])->name('operaciones.cargarimagenes.correccion');

    Route::post('operaciones.correccioncerrarmodal', [OperacionController::class, 'CerarModalCorreccion'])->name('operaciones.correccioncerrarmodal');

    Route::any('cliente.consultasaldo', [ClienteController::class, 'consultarSaldoCliente'])->name('cliente.consultasaldo');

    Route::post('operaciones.atenderiddismiss', [OperacionController::class, 'Atenderiddismiss'])->name('operaciones.atenderiddismiss');
    Route::post('operaciones.corregircerrar', [OperacionController::class, 'Corregircerrar'])->name('operaciones.corregircerrar');
    Route::get('operaciones.editatender/{pedido}', [OperacionController::class, 'editAtender'])->name('operaciones.editatender');
    Route::post('operaciones.editatencion/{pedido}', [OperacionController::class, 'editAtencion'])->name('operaciones.editatencion');
    Route::post('operaciones.veratencion/{pedido}', [OperacionController::class, 'verAtencion'])->name('operaciones.veratencion');
    Route::post('operaciones.editatencionsinconfirmar/{pedido}', [OperacionController::class, 'editatencionsinconfirmar'])->name('operaciones.editatencionsinconfirmar');
    Route::post('operaciones.datossubidaadj/{pedido}', [OperacionController::class, 'DatosSubidaAdjunto'])->name('operaciones.datossubidaadj');
    Route::post('operaciones.eliminaradjunto', [OperacionController::class, 'eliminarAdjuntoOperaciones'])->name('operaciones.eliminaradjunto');
    Route::post('operaciones.veratencionanulacion/{pedido}', [OperacionController::class, 'verAtencionAnulacion'])->name('operaciones.veratencionanulacion');
    Route::post('operaciones.veratencionanulacion.show', [OperacionController::class, 'verAtencionAnulacionShow'])->name('operaciones.veratencionanulacion.show');
    Route::post('operaciones.veradjuntos_operaciones/{pedido}', [OperacionController::class, 'verAdjuntosOperaciones'])->name('operaciones.veradjuntos_operaciones');

    Route::post('operaciones.subircorreccionsinconfirmar', [OperacionController::class, 'subircorreccionsinconfirmar'])->name('operaciones.subircorreccionsinconfirmar');

    Route::post('operaciones.updateatender/{pedido}', [OperacionController::class, 'updateAtender'])->name('operaciones.updateatender');
    Route::post('operaciones.updateatendersinconfirmar/{pedido}', [OperacionController::class, 'updateatendersinconfirmar'])->name('operaciones.updateatendersinconfirmar');
    Route::post('operaciones.updateatenderid/{pedido}', [OperacionController::class, 'updateAtenderId'])->name('operaciones.updateatenderid');
    Route::get('operaciones.showatender/{pedido}', [OperacionController::class, 'showAtender'])->name('operaciones.showatender');
    Route::post('operaciones.recepcionid', [EnvioController::class, 'confirmarRecepcionID'])->name('operaciones.recepcionid');
    Route::post('operaciones.atender_pedido_op', [EnvioController::class, 'AtenderPedidoOP'])->name('operaciones.atender_pedido_op');
    Route::post('operaciones.recibir_pedido_op', [EnvioController::class, 'RecibirPedidoOP'])->name('operaciones.recibir_pedido_op');
    Route::post('operaciones.envioid', [EnvioController::class, 'Enviarid'])->name('operaciones.envioid');
    Route::post('operaciones.revertirenvioid', [OperacionController::class, 'Revertirenvio'])->name('operaciones.revertirenvioid');
    Route::post('operaciones.revertirenvioidporatender', [OperacionController::class, 'Revertirenvioporatender'])->name('operaciones.revertirenvioidporatender');
    Route::post('operaciones.revertirajefeop', [OperacionController::class, 'Revertirajefeop'])->name('operaciones.revertirajefeop');
    Route::post('operaciones.revertirasindireccion', [OperacionController::class, 'Revertirasindireccion'])->name('operaciones.revertirasindireccion');

    Route::post('operaciones.revertiraenviocourier', [PedidoController::class, 'Revertiraenviocourier'])->name('operaciones.revertiraenviocourier');


    Route::post('operaciones.sinenvioid', [EnvioController::class, 'SinEnviarid'])->name('operaciones.sinenvioid');
    Route::post('operaciones.revertirhaciaatendido', [OperacionController::class, 'Revertirhaciaatendido'])->name('operaciones.revertirhaciaatendido');

    /*Operaciones*/




    //Route::post('pedidos.atender/{pedido}', [PedidoController::class, 'Atender'])->name('pedidos.atender');

    //Route::post('pedidos.envio/{pedido}', [PedidoController::class, 'Enviar'])->name('pedidos.envio');


    /*Controller Pagos*/

    Route::post('pagos.store.validate', [PagoController::class, 'validadContenidoPago'])->name('pagos.store.validate');
    Route::resource('pagos', PagoController::class)->names('pagos');
    Route::post('pagos.perdonardeuda', [PagoController::class, 'perdonardeuda'])->name('pagos.perdonardeuda');
    Route::post('titulares.banco', [PagoController::class, 'TitularesBanco'])->name('titulares.banco');
    Route::get('pagos/devolucion/{devolucion}', [PagoController::class, 'devolucion'])->name('pagos.devolucion');
    Route::post('pagos/devolucion/{devolucion}', [PagoController::class, 'devolucionUpdate'])->name('pagos.devolucion.update');
    Route::post('getDataPagoReciente', [PagoController::class, 'getDataPagoReciente'])->name('getDataPagoReciente');

    Route::get('pagostabla', [PagoController::class, 'indextabla'])->name('pagostabla');//actualizado para serverside
    Route::get('pagostablahistorial', [PagoController::class, 'indextablahistorial'])->name('pagostablahistorial');//actualizado para serverside
    Route::get('MisPagosTabla', [PagoController::class, 'MisPagosTabla'])->name('MisPagosTabla');//actualizado para serverside
    Route::get('pedidoscliente', [PagoController::class, 'pedidoscliente'])->name('cargar.pedidoscliente');
    Route::any('pedidosclientetabla', [PagoController::class, 'pedidosclientetabla'])->name('cargar.pedidosclientetabla');
    Route::post('pagodetalleUpdate', [PagoController::class, 'pagodetalleUpdate'])->name('pagodetalleUpdate');

    Route::post('pagodeleteRequest', [PagoController::class, 'destroyid'])->name('pagodeleteRequest.post');
    Route::post('pagodesabonarRequest', [PagoController::class, 'desabonarid'])->name('pagodesabonarRequest.post');

    Route::post('pagos.addImgTemp', [PagoController::class, 'addImgTemp'])->name('pagos.addImgTemp');//actualizado para serverside
    Route::post('pagos.changeImg', [PagoController::class, 'changeImg'])->name('pagos.changeImg');

    Route::post('pagos.addImgTempPagoPerdonar', [PagoController::class, 'addImgTempPagoPerdonar'])->name('pagos.addImgTempPagoPerdonar');
    Route::post('pago/eliminarPedido/{id}/{pago}', [PagoController::class, 'eliminarPedido'])->name('pago.eliminarPedido');
    Route::post('pago/eliminarPago/{id}/{pago}', [PagoController::class, 'eliminarPago'])->name('pago.eliminarPago');
    Route::get('pagos.mispagos', [PagoController::class, 'MisPagos'])->name('pagos.mispagos');
    Route::get('pagos.pagosincompletos', [PagoController::class, 'PagosIncompletos'])->name('pagos.pagosincompletos');
    Route::get('pagos.pagosobservados', [PagoController::class, 'PagosObservados'])->name('pagos.pagosobservados');
    Route::get('administracion.revisarpago', [PagoController::class, 'Revisarpago'])->name('administracion.revisarpago');//agregado para detalle de revisar
    Route::post('administracion.updaterevisar/{pago}', [PagoController::class, 'updateRevisar'])->name('administracion.updaterevisar');
    Route::post('administracion.updaterevisar.post', [PagoController::class, 'updateRevisarpost'])->name('administracion.updaterevisar.post');
    Route::get('pagos/{imagen}/descargarimagen', [PagoController::class, 'DescargarImagen'])->name('pagos.descargarimagen');
    Route::get('pagos/descargarimgdev', [PagoController::class, 'DescargarImgDevolucion'])->name('pagos.descargarimgdev');

    Route::get('asesorespago', [PagoController::class, 'asesorespago'])->name('asesorespago');


    /*Controller Pagos*/

    //Route::post('operaciones.sinenvio/{pedido}', [PedidoController::class, 'SinEnviar'])->name('operaciones.sinenvio');


/////////

    /*Controller Sobres*/
    Route::get('carga.distritos', [SobreController::class, 'cargadistritos'])->name('carga.distritos');

    Route::get('sobres.porenviar', [SobreController::class, 'Sobresporenviar'])->name('sobres.porenviar');
    Route::get('sobres.porenviartabla', [SobreController::class, 'Sobresporenviartabla'])->name('sobres.porenviartabla');

    Route::get('sobres.pedidosporenviar', [SobreController::class, 'Pedidosporenviar'])->name('sobres.pedidosporenviar');
    Route::get('sobres.pedidosporenviartabla', [SobreController::class, 'Pedidosporenviartabla'])->name('sobres.pedidosporenviartabla');

    Route::get('pedidosgrupotabla', [SobreController::class, 'pedidosgrupotabla'])->name('cargar.pedidosgrupotabla');
    Route::post('sobres.desvinculargrupo', [SobreController::class, 'EnvioDesvincular'])->name('sobres.desvinculargrupo');
    Route::post('relacionar_envio_courierregistro', [CourierRegistrosController::class, 'Relacionar'])->name('relacionar_envio_courierregistro');
    Route::get('sobreenvioshistorial', [SobreController::class, 'sobreenvioshistorial'])->name('sobreenvioshistorial');
    /*Controller Sobres*/

    Route::any('ajax_modal1_response', [ModalController::class, 'ajax_modal1_response'])->name('ajax_modal1_response');
    Route::any('ajax_modal_correccionpedidos', [ModalController::class, 'ajax_modal_correccionpedidos'])->name('ajax_modal_correccionpedidos');

    //Route::post('envios.recibir/{pedido}', [PedidoController::class, 'Recibir'])->name('envios.recibir');
    //Route::post('envios.enviar/{pedido}', [PedidoController::class, 'EnviarPedido'])->name('envios.enviar');

    /*Controller Envio*/
    Route::post('envios.recibirid', [EnvioController::class, 'Recibirid'])->name('envios.recibirid');
    Route::post('envios.recibiridlog', [EnvioController::class, 'RecibiridLog'])->name('envios.recibiridlog');
    Route::post('envios.quitardireccion', [EnvioController::class, 'actionQuitarDireccion'])->name('envios.quitardireccion');
    Route::post('envios.recepcionarmotorizado', [EnvioController::class, 'RecibirMotorizado'])->name('envios.recepcionarmotorizado');
    Route::post('envios.enviarid', [EnvioController::class, 'EnviarPedidoid'])->name('envios.enviarid');

    Route::post('motorizado.recojo', [MotorizadoController::class, 'MotorizadoRecojo'])->name('motorizado.recojo');
    Route::post('envios.confirmar-recepcion-recojo', [EnvioController::class, 'ConfirmarRecepcionRecojo'])->name('envios.confirmar-recepcion-recojo');


    Route::post('envios.distribuirid', [EnvioController::class, 'DistribuirEnvioid'])->name('envios.distribuirid');
    Route::post('envios.changeImg', [EnvioController::class, 'changeImg'])->name('envios.changeImg');

    Route::get('envios.distribuirsobres', [DistribucionController::class, 'index'])->name('envios.distribuirsobres');
    Route::get('envios.distribuirsobres/datatable', [DistribucionController::class, 'datatable'])->name('envios.distribuirsobrestabla');
    Route::post('envios.distribuirsobres/asignarzona', [DistribucionController::class, 'asignarZona'])->name('envios.distribuirsobres.asignarzona');
    Route::post('envios.distribuirsobres/agrupar', [DistribucionController::class, 'agrupar'])->name('envios.distribuirsobres.agrupar');
    Route::delete('envios.distribuirsobres/desagrupar', [DistribucionController::class, 'desagrupar'])->name('envios.distribuirsobres.desagrupar');

    Route::get('envios.estadosobres', [EnvioController::class, 'Estadosobres'])->name('envios.estadosobres');
    Route::get('envios.estadosobrestabla', [EnvioController::class, 'Estadosobrestabla'])->name('envios.estadosobrestabla');
    Route::post('envios.estadosobresexcel', [ExcelController::class, 'estadosobresExcel'])->name('estadosobresexcel');

    Route::post('envios.verificarzona', [EnvioController::class, 'VerificarZona'])->name('envios.verificarzona');
    Route::post('pedidos.cargardistritos', [PedidoController::class, 'jsonDistritos'])->name('pedidos.cargardistritos');


    /*Controller Envio*/

    //Route::get('pagos/create/{cliente}', [PagoController::class, 'create'])->name('pagos.create');

    //Route::post('pago/create/{id}', [PagoController::class, 'create2'])->name('pago.create');


    /* Administracion */
    Route::get('administracion.porrevisar', [AdministracionController::class, 'PorRevisar'])->name('administracion.porrevisar');
    Route::get('administracion.porrevisartabla', [AdministracionController::class, 'PorRevisartabla'])->name('administracion.porrevisartabla');//agregado para serverside
    Route::get('administracion.revisar/{pago}', [AdministracionController::class, 'Revisar'])->name('administracion.revisar');

    Route::get('administracion.pendientes', [AdministracionController::class, 'Administracionpendientes'])->name('administracion.pendientes');
    Route::get('administracion.pendientestabla', [AdministracionController::class, 'Administracionpendientestabla'])->name('administracion.pendientestabla');
    Route::get('administracion.revisarpendiente/{pago}', [AdministracionController::class, 'Revisarpendiente'])->name('administracion.revisarpendiente');

    Route::get('administracion.observados', [AdministracionController::class, 'Observados'])->name('administracion.observados');//de pagos por revisar index
    Route::get('administracion.observadostabla', [AdministracionController::class, 'Observadostabla'])->name('administracion.observadostabla');//agregado para serverside
    Route::get('administracion.revisarobservado/{pago}', [AdministracionController::class, 'Revisarobservado'])->name('administracion.revisarobservado');

    Route::get('administracion.abonados', [AdministracionController::class, 'Abonados'])->name('administracion.abonados');//de pagos por revisar index
    Route::get('administracion.abonadostabla', [AdministracionController::class, 'Abonadostabla'])->name('administracion.abonadostabla');//agregado para serverside

    Route::get('administracion.aprobados', [AdministracionController::class, 'Aprobados'])->name('administracion.aprobados');
    Route::get('administracion.aprobadostabla', [AdministracionController::class, 'Aprobadostabla'])->name('administracion.aprobadostabla');//agregado por zubieta
    /* Administracion */


    /* Movimiento */
    Route::resource('movimientos', MovimientoController::class)->names('movimientos');

    Route::get('movimientos.actualiza', [MovimientoController::class, 'actualiza'])->name('movimientos.actualiza');
    Route::get('movimientostabla', [MovimientoController::class, 'indextabla'])->name('movimientostabla');//actualizado para serverside


    Route::get('movimientostablaconciliar', [MovimientoController::class, 'indextablaconciliar'])->name('movimientostablaconciliar');//actualizado para serverside

    Route::get('tipomovimiento', [MovimientoController::class, 'tipomovimiento'])->name('cargar.tipomovimiento');
    Route::post('validar_repetido', [MovimientoController::class, 'repeat'])->name('validar_repetido');

    Route::post('envios.validacion_direccionenvio', [EnvioController::class, 'valida_direccionenvio'])->name('envios.validacion_direccionenvio');

    Route::post('register_movimiento', [MovimientoController::class, 'register'])->name('register_movimiento');

    Route::get('courierregistro', [CourierRegistrosController::class, 'indexi'])->name('courierregistro');
    Route::get('courierregistrotabla', [CourierRegistrosController::class, 'indextabla'])->name('courierregistrotabla');
    Route::post('register_courier_registros', [CourierRegistrosController::class, 'register'])->name('register_courier_registros');
    Route::post('validar_register_courier_registros', [CourierRegistrosController::class, 'validarregister'])->name('validar_register_courier_registros');

    Route::post('movimientodeleteRequest', [MovimientoController::class, 'destroyid'])->name('movimientodeleteRequest.post');
    /* Movimiento */

    Route::resource('roles', RoleController::class)->names('roles');

    Route::post('VentaPorFechas', [PdfController::class, 'VentaPorFechas'])->name('VentaPorFechas');
    Route::post('IngresoPorFechas', [PdfController::class, 'IngresoPorFechas'])->name('IngresoPorFechas');
    Route::get('reportes.index', [PdfController::class, 'index'])->name('reportes.index');
    Route::get('reportes.misasesores', [PdfController::class, 'MisAsesores'])->name('reportes.misasesores');
    Route::get('reportes.operaciones', [PdfController::class, 'Operaciones'])->name('reportes.operaciones');
    Route::get('reportes.analisis', [PdfController::class, 'Analisis'])->name('reportes.analisis');

    Route::resource('notifications', NotificationsController::class)->names('notifications');
    Route::get('notifications.get', [NotificationsController::class, 'getNotificationsData'])->name('notifications.get');
    Route::get('markAsRead', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('markAsRead');
    Route::post('/mark-as-read', [NotificationsController::class, 'markNotification'])->name('markNotification');
    Route::post('/descargaDevolucion', [NotificationsController::class, 'descargaDevolucion'])->name('descargaDevolucion');
//PDF
    //MODULO PEDIDOS
    Route::get('PDF/{pedido}/pedido', [PdfController::class, 'pedidosPDF'])->name('pedidosPDF');
    Route::get('PDF/{pedido}/correccion', [PdfController::class, 'correccionPDF'])->name('correccionPDF');
    Route::get('PDF/{pedido}/correccionpedido', [PdfController::class, 'correccionpedidoPDF'])->name('correccionpedidoPDF');
    Route::get('pedidosPDFpreview', [PdfController::class, 'pedidosPDFpreview'])->name('pedidosPDFpreview');

    Route::get('pedidosPDFpreview2', [PdfController::class, 'pedidosPDFpreview'])->name('pedidosPDFpreview2');
    //MODULO REPORTES
    Route::post('reporte/pedidosporfechas', [PdfController::class, 'PedidosPorFechas'])->name('pedidosporfechas');
    Route::post('reporte/pedidosporasesor', [PdfController::class, 'PedidosPorAsesor'])->name('pedidosporasesor');
    Route::post('reporte/pedidosporasesores', [PdfController::class, 'PedidosPorAsesores'])->name('pedidosporasesores');
    Route::post('reporte/pagosporfechas', [PdfController::class, 'PagosPorFechas'])->name('pagosporfechas');
    Route::post('reporte/pagosporasesor', [PdfController::class, 'PagosPorAsesor'])->name('pagosporasesor');
    Route::post('reporte/pagosporasesores', [PdfController::class, 'PagosPorAsesores'])->name('pagosporasesores');
    Route::get('imprimir/venta/{venta}', [PdfController::class, 'ticketVentaPDF'])->name('ticketVentaPDF');


//EXCEL EXPORTABLES
    //MODULO PERSONAS
    Route::get('clientesExcel', [ExcelController::class, 'clientesExcel'])->name('clientesExcel');

    Route::post('situacionporfechasExcel', [ExcelController::class, 'clientessituacionExcel'])->name('situacionporfechasExcel');


    Route::post('clientesv2Excel', [ExcelController::class, 'clientesv2Excel'])->name('clientesv2Excel');

    Route::post('clientespedidosExcel', [ExcelController::class, 'clientespedidosExcel'])->name('clientespedidosExcel');

    Route::any('pedidosPerdonarCourierExcel', [ExcelController::class, 'pedidosPerdonarCourierExcel'])->name('pedidosPerdonarCourierExcel');

    Route::post('clientesabandonoExcel', [ExcelController::class, 'clientesabandonoExcel'])->name('clientesabandonoExcel');

    Route::any('excel/clientes/reporte_multiple', [ExcelController::class, 'clientesReporteMultipleExcel'])->name('excel.clientes.reporte.multiple');

    Route::get('excel.basefria-all_asesor-excel', [ExcelController::class, 'basefriaAllAsesorExcel'])->name('excel.basefria-all_asesor-excel');


    Route::get('excel/clientes/two_month_ago', [ExcelController::class, 'clientesTwoMonthAgoExcel'])->name('excel.clientes-two-month-ago-excel');
    Route::get('excel/clientes/four_month_ago', [ExcelController::class, 'clientesFourMonthAgoExcel'])->name('excel.clientes-four-month-ago-excel');


    Route::get('excel.clientes.caidos.deuda.excel', [ExcelController::class, 'clientsFallenDebtExcel'])->name('excel.clientes.caidos.deuda.excel');
    Route::get('excel.clientes.caidos.sindeuda.excel', [ExcelController::class, 'clientsFallenWithOutDebtExcel'])->name('excel.clientes.caidos.sindeuda.excel');

    Route::get('excel/basefria/exportar', [ExcelController::class, 'excelBaseFriaExportar'])->name('excel/basefria/exportar');

    // Route::get('basefriaExcel', [ExcelController::class, 'basefriaExcel'])->name('basefriaExcel');
    Route::post('basefriaExcel', [ExcelController::class, 'basefriaExcel'])->name('basefriaExcel');
    Route::post('basefriaporasesorExcel', [ExcelController::class, 'basefriaporasesorExcel'])->name('basefriaporasesorExcel');
    //MODULO ADMINISTRACION
    // Route::get('pagosaprobadosExcel', [ExcelController::class, 'pagosaprobadosExcel'])->name('pagosaprobadosExcel');
    Route::post('pagosaprobadosExcel', [ExcelController::class, 'pagosaprobadosExcel'])->name('pagosaprobadosExcel');
    //MODULO PAGOS
    // Route::get('pagosExcel', [ExcelController::class, 'pagosExcel'])->name('pagosExcel');
    // Route::get('mispagosExcel', [ExcelController::class, 'mispagosExcel'])->name('mispagosExcel');
    // Route::get('pagosincompletosExcel', [ExcelController::class, 'pagosincompletosExcel'])->name('pagosincompletosExcel');
    // Route::get('pagosobservadosExcel', [ExcelController::class, 'pagosobservadosExcel'])->name('pagosobservadosExcel');
    Route::post('pagosExcel', [ExcelController::class, 'pagosExcel'])->name('pagosExcel');
    Route::post('mispagosExcel', [ExcelController::class, 'mispagosExcel'])->name('mispagosExcel');
    Route::post('pagosincompletosExcel', [ExcelController::class, 'pagosincompletosExcel'])->name('pagosincompletosExcel');
    Route::post('pagosobservadosExcel', [ExcelController::class, 'pagosobservadosExcel'])->name('pagosobservadosExcel');
    Route::post('pagosabonadosExcel', [ExcelController::class, 'pagosabonadosExcel'])->name('pagosabonadosExcel');

    Route::post('porrevisarExcel', [ExcelController::class, 'porrevisarExcel'])->name('porrevisarExcel');
    //MODULO OPERACION
    // Route::get('pedidosporatenderExcel', [ExcelController::class, 'pedidosporatenderExcel'])->name('pedidosporatenderExcel');
    // Route::get('pedidosenatencionExcel', [ExcelController::class, 'pedidosenatencionExcel'])->name('pedidosenatencionExcel');
    // Route::get('pedidosatendidosExcel', [ExcelController::class, 'pedidosatendidosExcel'])->name('pedidosatendidosExcel');
    Route::post('pedidosporatenderExcel', [ExcelController::class, 'pedidosporatenderExcel'])->name('pedidosporatenderExcel');
    Route::post('pedidosenatencionExcel', [ExcelController::class, 'pedidosenatencionExcel'])->name('pedidosenatencionExcel');
    Route::post('pedidosatendidosExcel', [ExcelController::class, 'pedidosatendidosExcel'])->name('pedidosatendidosExcel');
    Route::post('pedidosentregadosExcel', [ExcelController::class, 'pedidosentregadosExcel'])->name('pedidosentregadosExcel');
    //MODULO ENVIOS

    // Route::get('pedidosporenviarExcel', [ExcelController::class, 'pedidosporenviarExcel'])->name('pedidosporenviarExcel');
    Route::post('pedidosporenviarExcel', [ExcelController::class, 'pedidosporenviarExcel'])->name('pedidosporenviarExcel');


    Route::post('sobresRutaEnvioExcel', [ExcelController::class, 'sobresRutaEnvioExcel'])->name('sobresRutaEnvioExcel');
    Route::post('sobresRutaEnvioLimaNorteExcel', [ExcelController::class, 'sobresRutaEnvioLimaNorteExcel'])->name('sobresRutaEnvioLimaNorteExcel');
    Route::post('sobresRutaEnvioLimaCentroExcel', [ExcelController::class, 'sobresRutaEnvioLimaCentroExcel'])->name('sobresRutaEnvioLimaCentroExcel');
    Route::post('sobresRutaEnvioLimaSurExcel', [ExcelController::class, 'sobresRutaEnvioLimaSurExcel'])->name('sobresRutaEnvioLimaSurExcel');
    Route::post('sobresRutaEnvioProvinciaExcel', [ExcelController::class, 'sobresRutaEnvioProvinciaExcel'])->name('sobresRutaEnvioProvinciaExcel');

    Route::post('envios.motorizadoconfirmar.Excel', [ExcelController::class, 'enviosMotorizadoconfirmarExcel'])->name('envios.motorizadoconfirmar.Excel');
    Route::any('envios.recepcionmotorizado.Excel', [ExcelController::class, 'enviosRecepcionmotorizadoExcel'])->name('envios.recepcionmotorizado.Excel');
    //Route::post('sobresRutaEnviov2Excel', [ExcelController::class, 'sobresRutaEnviov2Excel'])->name('sobresRutaEnviov2Excel');

    //MODULO PEDIDOS
    // Route::get('pedidosExcel', [ExcelController::class, 'pedidosExcel'])->name('pedidosExcel');
    // Route::get('mispedidosExcel', [ExcelController::class, 'mispedidosExcel'])->name('mispedidosExcel');
    // Route::get('pedidospagadosExcel', [ExcelController::class, 'pedidospagadosExcel'])->name('pedidospagadosExcel');
    // Route::get('pedidossinpagosExcel', [ExcelController::class, 'pedidossinpagosExcel'])->name('pedidossinpagosExcel');
    Route::post('movimientosExcel', [ExcelController::class, 'movimientosExcel'])->name('movimientosExcel');
    Route::post('usuariosExcel', [ExcelController::class, 'usuariosExcel'])->name('usuariosExcel');

    Route::get('pedidosExcel', [ExcelController::class, 'pedidosExcel'])->name('pedidosExcel');

    Route::post('mispedidosExcel', [ExcelController::class, 'mispedidosExcel'])->name('mispedidosExcel');
    Route::post('pedidospagadosExcel', [ExcelController::class, 'pedidospagadosExcel'])->name('pedidospagadosExcel');
    Route::post('pedidossinpagosExcel', [ExcelController::class, 'pedidossinpagosExcel'])->name('pedidossinpagosExcel');
    Route::post('analisisExcel', [ExcelController::class, 'analisisExcel'])->name('analisisExcel');
    //REPORTES
    Route::post('reporte/pedidosgeneralexcel', [ExcelController::class, 'pedidosgeneralexcel'])->name('pedidosgeneralexcel');
    Route::post('reporte/pedidosporfechasexcel', [ExcelController::class, 'pedidosporfechasExcel'])->name('pedidosporfechasexcel');
    Route::post('reporte/pedidosporasesorexcel', [ExcelController::class, 'pedidosporasesorExcel'])->name('pedidosporasesorexcel');
    Route::post('reporte/pedidosoperacionesexcel', [ExcelController::class, 'pedidosoperacionesexcel'])->name('pedidosoperacionesexcel');
    Route::post('reporte/pagosporasesorexcel', [ExcelController::class, 'pagosporasesorExcel'])->name('pagosporasesorexcel');
    Route::post('reporte/pagosporasesoresexcel', [ExcelController::class, 'pagosporasesoresExcel'])->name('pagosporasesoresexcel');

    Route::post('reporte/entregadosporfechasexcel', [ExcelController::class, 'entregadosporfechasexcel'])->name('entregadosporfechasexcel');

    /* PWA */
    Route::get('/offline', function () {
        return view('vendor.laravelpwa.offline');
    });

    /* Escaneo e códdigos de Barras  */

    Route::post('operaciones.confirmaropbarras', [EnvioController::class, 'ConfirmarOPBarra'])->name('operaciones.confirmaropbarras');
    Route::post('operaciones.validaropbarras', [EnvioController::class, 'ValidarOPBarra'])->name('operaciones.validaropbarras');
    /* Cambiar estado a Motorizado */
    Route::post('operaciones.confirmar', [EnvioController::class, 'confirmarEstado'])->name('operaciones.confirmar');
    Route::post('operaciones.confirmarentregasinenvio', [EnvioController::class, 'confirmarEntregaSinEnvio'])->name('operaciones.confirmarentregasinenvio');

    Route::post('operaciones.confirmarrecepcionmotorizado', [EnvioController::class, 'confirmarEstadoRecepcionMotorizado'])->name('operaciones.confirmarrecepcionmotorizado');
    Route::post('operaciones.confirmar.revertir', [EnvioController::class, 'confirmarEstadoRevert'])->name('operaciones.confirmar.revertir');

    Route::post('operaciones.comparacionmotorizado', [MotorizadoController::class, 'ComparacionMotorizado'])->name('operaciones.comparacionmotorizado');
    //Route::post('operaciones.confirmaradjuntomotorizado/{pedido}', [OperacionController::class, 'updateatendersinconfirmar'])->name('operaciones.updateatendersinconfirmar');

    Route::post('operaciones.confirmarmotorizado', [EnvioController::class, 'confirmarEstadoConfirm'])->name('operaciones.confirmarmotorizado');
    Route::post('operaciones.confirmarmotorizado.revertir', [EnvioController::class, 'confirmarEstadoConfirmRevert'])->name('operaciones.confirmarmotorizado.revertir');
    Route::post('operaciones.confirmarmotorizadodismiss', [EnvioController::class, 'confirmarEstadoConfirmDismiss'])->name('operaciones.confirmarmotorizadodismiss');
    Route::post('operaciones.confirmarmotorizadoconfirm', [EnvioController::class, 'confirmarEstadoConfirmConfirm'])->name('operaciones.confirmarmotorizadoconfirm');
    Route::post('operaciones.confirmarmotorizadoconfirmdismiss', [EnvioController::class, 'confirmarEstadoConfirmConfirmDismiss'])->name('operaciones.confirmarmotorizadoconfirmdismiss');
    Route::post('operaciones.confirmarcliente', [EnvioController::class, 'confirmarEstadoConfirmValidada'])->name('operaciones.confirmarcliente');


    Route::post('motorizado.recojoenviarcourier', [MotorizadoController::class, 'motorizadoRecojoenviarcourier'])->name('motorizado.recojoenviarcourier');
    Route::post('courier.confirmrecojo', [RecojoController::class, 'courierConfirmRecojo'])->name('courier.confirmrecojo');

    Route::post('courier.recojoenviarope', [RecojoController::class, 'courierRecojoenviarope'])->name('courier.recojoenviarope');


    Route::get('direcciongrupo/{grupo}/no_contesto/get_sustentos_adjuntos', [DireccionGrupoController::class, 'get_sustentos_adjuntos'])->name('direcciongrupo.no-contesto.get-sustentos-adjuntos');


    Route::post('agregardireccionjefeoperaciones.post', [SettingsController::class, 'agregardireccionjefeoperaciones'])->name('agregardireccionjefeoperaciones.post');

    //Route::post('getdireecionentrega', [SettingsController::class, 'getdireecionentrega'])->name('getdireecionentrega');
    Route::post('getdireecionentrega', [PedidoController::class, 'getdireecionentrega'])->name('getdireecionentrega');

    //Ruta par usar scraping, con el paquete weidner/goutte
    Route::get('scraper', [ScraperController::class, 'getscraper'])->name('scraper');

    /* Route::group(['middleware' => ['permission:pedidos.index']], function () {
        Route::get('pedidos.index', [PedidoController::class, 'index']);
    }); */

    Route::any('agenda_token', [FullCalenderController::class, 'token'])->name('agenda_token');

    Route::any('fullcalendarindex', [FullCalenderController::class, 'indexcalendario'])->name('fullcalendarindex');
    Route::any('fullcalendar', [FullCalenderController::class, 'index'])->name('fullcalendar');
    Route::any('fullcalendarAjax', [FullCalenderController::class, 'ajax'])->name('fullcalendarAjax');
    Route::any('fullcalendarAjaxUnsigned', [FullCalenderController::class, 'ajaxunsigned'])->name('fullcalendarAjaxUnsigned');

    Route::any('pedidosanulaciones.modal.agregaranulacion_pc', [PedidosAnulacionController::class, 'modalsAnulacion'])->name('pedidosanulaciones.modal.agregaranulacion_pc');
    Route::any('pedidosanulaciones.modal.agregaranulacion_f', [PedidosAnulacionController::class, 'modalsAnulacion'])->name('pedidosanulaciones.modal.agregaranulacion_f');
    Route::any('pedidosanulaciones_cobranza', [PedidosAnulacionController::class, 'modalsanulacioncobranza'])->name('pedidosanulaciones_cobranza');

    Route::get('pedidosanulacionestabla', [PedidosAnulacionController::class, 'indexanulacionestabla'])->name('pedidosanulacionestabla');
    Route::post('solicita_anulacion_pedido', [PedidosAnulacionController::class, 'solicitaAnulacionPedido'])->name('solicita_anulacion_pedido');
    Route::post('solicita_anulacion_pedidof', [PedidosAnulacionController::class, 'solicitaAnulacionPedidof'])->name('solicita_anulacion_pedidof');
    Route::post('solicita_anulacion_pedidoq', [PedidosAnulacionController::class, 'solicitaAnulacionPedidoq'])->name('solicita_anulacion_pedidoq');
    Route::post('anulacionAprobacionAsesor', [PedidosAnulacionController::class, 'anulacionAprobacionAsesor'])->name('anulacionAprobacionAsesor');
    Route::post('anulacionAprobacionEncargado', [PedidosAnulacionController::class, 'anulacionAprobacionEncargado'])->name('anulacionAprobacionEncargado');
    Route::post('anulacionAprobacionAdmin', [PedidosAnulacionController::class, 'anulacionAprobacionAdmin'])->name('anulacionAprobacionAdmin');
    Route::post('anulacionAprobacionJefeOp', [PedidosAnulacionController::class, 'anulacionAprobacionJefeOp'])->name('anulacionAprobacionJefeOp');
    Route::post('anulacionSolicitud', [PedidosAnulacionController::class, 'anulacionSolicitud'])->name('anulacionSolicitud');
    Route::middleware('authorize.pedido.anulled')
        ->post('confirmaSolicitudAnulacion', [PedidosAnulacionController::class, 'confirmaSolicitudAnulacion'])
        ->name('confirmaSolicitudAnulacion');
    Route::post('verAdjuntosAddAsesorAnulacion', [PedidosAnulacionController::class, 'verAdjuntosAddAsesorAnulacion'])->name('verAdjuntosAddAsesorAnulacion');
    Route::post('getcbxculpables', [PedidosAnulacionController::class, 'getcbxculpables'])->name('getcbxculpables');

    Route::get('chart-data', [ChartController::class,'getData'])->name('chart-data');
    Route::get('chart-pedidos-asesores', [ChartController::class,'getPedidosAsesores'])->name('chart-pedidos-asesores');
    Route::get('chart-pedidos-asesores-faltantes', [ChartController::class,'getPedidosAsesoresfaltantes'])->name('chart-pedidos-asesores-faltantes');
    Route::get('chart-pedidos-encargados', [ChartController::class,'getPedidosEncargadosfaltantes'])->name('chart-pedidos-encargados');


    Route::get('excel/envios/olva', [ExcelController::class, 'listadoEnviosTiendaOlva'])->name('excel.envios-olva');
    Route::post('pedidosanul.verfilessolicasesores/{pedidosanulacion}', [PedidosAnulacionController::class, 'verFilesSolicitudAsesorAnulacion'])->name('anulaciones.verfilessolicasesores');

    Route::post('getRucComboPedidos', [ClienteController::class, 'getRucComboPedidos'])->name('getRucComboPedidos');
    Route::post('uptRucPedidos', [ClienteController::class, 'uptRucPedidos'])->name('uptRucPedidos');
});
