<?php //a7049bc7ffdf78f4c6f7c612f0c5ea26
/** @noinspection all */

namespace App\Models {

    use Database\Factories\PagoFactory;
    use Database\Factories\TeamFactory;
    use Database\Factories\UserFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;
    use Illuminate\Support\Carbon;
    use Laravel\Sanctum\PersonalAccessToken;
    use LaravelIdea\Helper\App\Models\_IH_Alerta_C;
    use LaravelIdea\Helper\App\Models\_IH_Alerta_QB;
    use LaravelIdea\Helper\App\Models\_IH_AttachCorrection_C;
    use LaravelIdea\Helper\App\Models\_IH_AttachCorrection_QB;
    use LaravelIdea\Helper\App\Models\_IH_CallAtention_C;
    use LaravelIdea\Helper\App\Models\_IH_CallAtention_QB;
    use LaravelIdea\Helper\App\Models\_IH_Cliente_C;
    use LaravelIdea\Helper\App\Models\_IH_Cliente_QB;
    use LaravelIdea\Helper\App\Models\_IH_Correction_C;
    use LaravelIdea\Helper\App\Models\_IH_Correction_QB;
    use LaravelIdea\Helper\App\Models\_IH_CourierRegistro_C;
    use LaravelIdea\Helper\App\Models\_IH_CourierRegistro_QB;
    use LaravelIdea\Helper\App\Models\_IH_CuentaBancaria_C;
    use LaravelIdea\Helper\App\Models\_IH_CuentaBancaria_QB;
    use LaravelIdea\Helper\App\Models\_IH_Departamento_C;
    use LaravelIdea\Helper\App\Models\_IH_Departamento_QB;
    use LaravelIdea\Helper\App\Models\_IH_DetalleContactos_C;
    use LaravelIdea\Helper\App\Models\_IH_DetalleContactos_QB;
    use LaravelIdea\Helper\App\Models\_IH_DetallePago_C;
    use LaravelIdea\Helper\App\Models\_IH_DetallePago_QB;
    use LaravelIdea\Helper\App\Models\_IH_DetallePedido_C;
    use LaravelIdea\Helper\App\Models\_IH_DetallePedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_Devolucion_C;
    use LaravelIdea\Helper\App\Models\_IH_Devolucion_QB;
    use LaravelIdea\Helper\App\Models\_IH_DireccionEnvio_C;
    use LaravelIdea\Helper\App\Models\_IH_DireccionEnvio_QB;
    use LaravelIdea\Helper\App\Models\_IH_DireccionGrupo_C;
    use LaravelIdea\Helper\App\Models\_IH_DireccionGrupo_QB;
    use LaravelIdea\Helper\App\Models\_IH_DireccionPedido_C;
    use LaravelIdea\Helper\App\Models\_IH_DireccionPedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_Directions_C;
    use LaravelIdea\Helper\App\Models\_IH_Directions_QB;
    use LaravelIdea\Helper\App\Models\_IH_Distrito_C;
    use LaravelIdea\Helper\App\Models\_IH_Distrito_QB;
    use LaravelIdea\Helper\App\Models\_IH_EntidadBancaria_C;
    use LaravelIdea\Helper\App\Models\_IH_EntidadBancaria_QB;
    use LaravelIdea\Helper\App\Models\_IH_EventsUnsigned_C;
    use LaravelIdea\Helper\App\Models\_IH_EventsUnsigned_QB;
    use LaravelIdea\Helper\App\Models\_IH_Event_C;
    use LaravelIdea\Helper\App\Models\_IH_Event_QB;
    use LaravelIdea\Helper\App\Models\_IH_FileUploadAnulacion_C;
    use LaravelIdea\Helper\App\Models\_IH_FileUploadAnulacion_QB;
    use LaravelIdea\Helper\App\Models\_IH_GastoEnvio_C;
    use LaravelIdea\Helper\App\Models\_IH_GastoEnvio_QB;
    use LaravelIdea\Helper\App\Models\_IH_GastoPedido_C;
    use LaravelIdea\Helper\App\Models\_IH_GastoPedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_GrupoPedido_C;
    use LaravelIdea\Helper\App\Models\_IH_GrupoPedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_HistorialVidas_C;
    use LaravelIdea\Helper\App\Models\_IH_HistorialVidas_QB;
    use LaravelIdea\Helper\App\Models\_IH_HistoriaPedidos_C;
    use LaravelIdea\Helper\App\Models\_IH_HistoriaPedidos_QB;
    use LaravelIdea\Helper\App\Models\_IH_ImageAgenda_C;
    use LaravelIdea\Helper\App\Models\_IH_ImageAgenda_QB;
    use LaravelIdea\Helper\App\Models\_IH_ImagenAtencion_C;
    use LaravelIdea\Helper\App\Models\_IH_ImagenAtencion_QB;
    use LaravelIdea\Helper\App\Models\_IH_ImagenPedido_C;
    use LaravelIdea\Helper\App\Models\_IH_ImagenPedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_ListadoResultado_C;
    use LaravelIdea\Helper\App\Models\_IH_ListadoResultado_QB;
    use LaravelIdea\Helper\App\Models\_IH_Media_C;
    use LaravelIdea\Helper\App\Models\_IH_Media_QB;
    use LaravelIdea\Helper\App\Models\_IH_Membership_C;
    use LaravelIdea\Helper\App\Models\_IH_Membership_QB;
    use LaravelIdea\Helper\App\Models\_IH_Meta_C;
    use LaravelIdea\Helper\App\Models\_IH_Meta_QB;
    use LaravelIdea\Helper\App\Models\_IH_MovimientoBancario_C;
    use LaravelIdea\Helper\App\Models\_IH_MovimientoBancario_QB;
    use LaravelIdea\Helper\App\Models\_IH_OlvaMovimiento_C;
    use LaravelIdea\Helper\App\Models\_IH_OlvaMovimiento_QB;
    use LaravelIdea\Helper\App\Models\_IH_PagoPedido_C;
    use LaravelIdea\Helper\App\Models\_IH_PagoPedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_Pago_C;
    use LaravelIdea\Helper\App\Models\_IH_Pago_QB;
    use LaravelIdea\Helper\App\Models\_IH_PasswordReset_C;
    use LaravelIdea\Helper\App\Models\_IH_PasswordReset_QB;
    use LaravelIdea\Helper\App\Models\_IH_PedidoHistory_C;
    use LaravelIdea\Helper\App\Models\_IH_PedidoHistory_QB;
    use LaravelIdea\Helper\App\Models\_IH_PedidoMotorizadoHistory_C;
    use LaravelIdea\Helper\App\Models\_IH_PedidoMotorizadoHistory_QB;
    use LaravelIdea\Helper\App\Models\_IH_PedidoMovimientoEstado_C;
    use LaravelIdea\Helper\App\Models\_IH_PedidoMovimientoEstado_QB;
    use LaravelIdea\Helper\App\Models\_IH_PedidosAnulacion_C;
    use LaravelIdea\Helper\App\Models\_IH_PedidosAnulacion_QB;
    use LaravelIdea\Helper\App\Models\_IH_Pedido_C;
    use LaravelIdea\Helper\App\Models\_IH_Pedido_QB;
    use LaravelIdea\Helper\App\Models\_IH_Porcentaje_C;
    use LaravelIdea\Helper\App\Models\_IH_Porcentaje_QB;
    use LaravelIdea\Helper\App\Models\_IH_Provincia_C;
    use LaravelIdea\Helper\App\Models\_IH_Provincia_QB;
    use LaravelIdea\Helper\App\Models\_IH_Ruc_C;
    use LaravelIdea\Helper\App\Models\_IH_Ruc_QB;
    use LaravelIdea\Helper\App\Models\_IH_SituacionClientes_C;
    use LaravelIdea\Helper\App\Models\_IH_SituacionClientes_QB;
    use LaravelIdea\Helper\App\Models\_IH_TeamInvitation_C;
    use LaravelIdea\Helper\App\Models\_IH_TeamInvitation_QB;
    use LaravelIdea\Helper\App\Models\_IH_Team_C;
    use LaravelIdea\Helper\App\Models\_IH_Team_QB;
    use LaravelIdea\Helper\App\Models\_IH_TipoMovimiento_C;
    use LaravelIdea\Helper\App\Models\_IH_TipoMovimiento_QB;
    use LaravelIdea\Helper\App\Models\_IH_Titular_C;
    use LaravelIdea\Helper\App\Models\_IH_Titular_QB;
    use LaravelIdea\Helper\App\Models\_IH_UpdateMovimiento_C;
    use LaravelIdea\Helper\App\Models\_IH_UpdateMovimiento_QB;
    use LaravelIdea\Helper\App\Models\_IH_User_C;
    use LaravelIdea\Helper\App\Models\_IH_User_QB;
    use LaravelIdea\Helper\Illuminate\Notifications\_IH_DatabaseNotification_QB;
    use LaravelIdea\Helper\Laravel\Sanctum\_IH_PersonalAccessToken_C;
    use LaravelIdea\Helper\Laravel\Sanctum\_IH_PersonalAccessToken_QB;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Permission_C;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Permission_QB;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Role_C;
    use LaravelIdea\Helper\Spatie\Permission\Models\_IH_Role_QB;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;
    
    /**
     * @property User $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Alerta_QB onWriteConnection()
     * @method _IH_Alerta_QB newQuery()
     * @method static _IH_Alerta_QB on(null|string $connection = null)
     * @method static _IH_Alerta_QB query()
     * @method static _IH_Alerta_QB with(array|string $relations)
     * @method _IH_Alerta_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Alerta_C|Alerta[] all()
     * @mixin _IH_Alerta_QB
     */
    class Alerta extends Model {}
    
    /**
     * @property int $id
     * @property int|null $correction_id
     * @property string|null $type
     * @property string|null $name
     * @property string|null $file_name
     * @property string|null $mime_type
     * @property string $disk
     * @property bool|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_AttachCorrection_QB onWriteConnection()
     * @method _IH_AttachCorrection_QB newQuery()
     * @method static _IH_AttachCorrection_QB on(null|string $connection = null)
     * @method static _IH_AttachCorrection_QB query()
     * @method static _IH_AttachCorrection_QB with(array|string $relations)
     * @method _IH_AttachCorrection_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_AttachCorrection_C|AttachCorrection[] all()
     * @ownLinks correction_id,\App\Models\Correction,id
     * @mixin _IH_AttachCorrection_QB
     */
    class AttachCorrection extends Model {}
    
    /**
     * @property int $id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $user_id
     * @property string|null $user_identificador
     * @property string|null $accion
     * @property int|null $responsable
     * @method static _IH_CallAtention_QB onWriteConnection()
     * @method _IH_CallAtention_QB newQuery()
     * @method static _IH_CallAtention_QB on(null|string $connection = null)
     * @method static _IH_CallAtention_QB query()
     * @method static _IH_CallAtention_QB with(array|string $relations)
     * @method _IH_CallAtention_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_CallAtention_C|CallAtention[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @mixin _IH_CallAtention_QB
     */
    class CallAtention extends Model {}
    
    /**
     * @property int $id
     * @property int $user_id
     * @property string|null $nombre
     * @property string|null $icelular
     * @property int $celular
     * @property int|null $tipo
     * @property string|null $provincia
     * @property string|null $distrito
     * @property string|null $direccion
     * @property string|null $referencia
     * @property string|null $dni
     * @property float|null $saldo
     * @property int|null $deuda
     * @property int|null $pidio
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $crea_temporal
     * @property int|null $activado_tiempo
     * @property int|null $activado_pedido
     * @property Carbon|null $temporal_update
     * @property string|null $situacion
     * @property string|null $motivo_anulacion
     * @property string|null $responsable_anulacion
     * @property int|null $user_anulacion_id
     * @property Carbon|null $fecha_anulacion
     * @property string|null $path_adjunto_anular
     * @property string|null $path_adjunto_anular_disk
     * @property string|null $agenda
     * @property string|null $user_identificador
     * @property string|null $user_clavepedido
     * @property Carbon|null $fecha_ultimopedido
     * @property string|null $codigo_ultimopedido
     * @property int|null $pago_ultimopedido
     * @property int|null $pagado_ultimopedido
     * @property float|null $fsb_porcentaje
     * @property float|null $fcb_porcentaje
     * @property float|null $esb_porcentaje
     * @property float|null $ecb_porcentaje
     * @property _IH_DireccionGrupo_C|DireccionGrupo[] $direccion_grupos
     * @property-read int $direccion_grupos_count
     * @method HasMany|_IH_DireccionGrupo_QB direccion_grupos()
     * @property _IH_Pedido_C|Pedido[] $pedidos
     * @property-read int $pedidos_count
     * @method HasMany|_IH_Pedido_QB pedidos()
     * @property _IH_Porcentaje_C|Porcentaje[] $porcentajes
     * @property-read int $porcentajes_count
     * @method HasMany|_IH_Porcentaje_QB porcentajes()
     * @property _IH_Ruc_C|Ruc[] $rucs
     * @property-read int $rucs_count
     * @method HasMany|_IH_Ruc_QB rucs()
     * @property User $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Cliente_QB onWriteConnection()
     * @method _IH_Cliente_QB newQuery()
     * @method static _IH_Cliente_QB on(null|string $connection = null)
     * @method static _IH_Cliente_QB query()
     * @method static _IH_Cliente_QB with(array|string $relations)
     * @method _IH_Cliente_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Cliente_C|Cliente[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @foreignLinks id,\App\Models\DireccionEnvio,cliente_id|id,\App\Models\DireccionGrupo,cliente_id|id,\App\Models\GastoEnvio,cliente_id|id,\App\Models\Pago,cliente_id|id,\App\Models\Pedido,cliente_id|id,\App\Models\Porcentaje,cliente_id|id,\App\Models\Ruc,cliente_id|id,\App\Models\PedidoHistory,cliente_id|id,\App\Models\HistoriaPedidos,cliente_id
     * @mixin _IH_Cliente_QB
     */
    class Cliente extends Model {}
    
    /**
     * @property string|null $condicion_envio
     * @property int|null $condicion_envio_code
     * @method static _IH_Correction_QB onWriteConnection()
     * @method _IH_Correction_QB newQuery()
     * @method static _IH_Correction_QB on(null|string $connection = null)
     * @method static _IH_Correction_QB query()
     * @method static _IH_Correction_QB with(array|string $relations)
     * @method _IH_Correction_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Correction_C|Correction[] all()
     * @foreignLinks id,\App\Models\AttachCorrection,correction_id
     * @mixin _IH_Correction_QB
     */
    class Correction extends Model {}
    
    /**
     * @property int $id
     * @property string|null $courier_registro
     * @property string|null $adjunto
     * @property int $user_created
     * @property Carbon|null $created_at
     * @property int $user_updated
     * @property Carbon|null $updated_at
     * @property int $user_deleted
     * @property Carbon|null $deleted_at
     * @property int $status
     * @property int $relacionado
     * @property int|null $rel_direcciongrupo
     * @property Carbon|null $rel_fechadp
     * @property float|null $rel_importe
     * @property string|null $rel_tracking
     * @property int $rel_userid
     * @property Carbon|null $rel_fecharel
     * @method static _IH_CourierRegistro_QB onWriteConnection()
     * @method _IH_CourierRegistro_QB newQuery()
     * @method static _IH_CourierRegistro_QB on(null|string $connection = null)
     * @method static _IH_CourierRegistro_QB query()
     * @method static _IH_CourierRegistro_QB with(array|string $relations)
     * @method _IH_CourierRegistro_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_CourierRegistro_C|CourierRegistro[] all()
     * @mixin _IH_CourierRegistro_QB
     */
    class CourierRegistro extends Model {}
    
    /**
     * @method static _IH_CuentaBancaria_QB onWriteConnection()
     * @method _IH_CuentaBancaria_QB newQuery()
     * @method static _IH_CuentaBancaria_QB on(null|string $connection = null)
     * @method static _IH_CuentaBancaria_QB query()
     * @method static _IH_CuentaBancaria_QB with(array|string $relations)
     * @method _IH_CuentaBancaria_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_CuentaBancaria_C|CuentaBancaria[] all()
     * @mixin _IH_CuentaBancaria_QB
     */
    class CuentaBancaria extends Model {}
    
    /**
     * @property int $id
     * @property string|null $departamento
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_Departamento_QB onWriteConnection()
     * @method _IH_Departamento_QB newQuery()
     * @method static _IH_Departamento_QB on(null|string $connection = null)
     * @method static _IH_Departamento_QB query()
     * @method static _IH_Departamento_QB with(array|string $relations)
     * @method _IH_Departamento_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Departamento_C|Departamento[] all()
     * @mixin _IH_Departamento_QB
     */
    class Departamento extends Model {}
    
    /**
     * @property int $id
     * @property string $codigo_asesor
     * @property string $nombre_asesor
     * @property string $celular
     * @property int $codigo_cliente
     * @property string $nombres_cliente
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string $nombre_contacto
     * @property bool $guardado
     * @property bool $confirmado
     * @property int $codigo_registra
     * @property int|null $tipo_insert
     * @property int|null $reconfirmado
     * @property string|null $foto
     * @method static _IH_DetalleContactos_QB onWriteConnection()
     * @method _IH_DetalleContactos_QB newQuery()
     * @method static _IH_DetalleContactos_QB on(null|string $connection = null)
     * @method static _IH_DetalleContactos_QB query()
     * @method static _IH_DetalleContactos_QB with(array|string $relations)
     * @method _IH_DetalleContactos_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DetalleContactos_C|DetalleContactos[] all()
     * @mixin _IH_DetalleContactos_QB
     */
    class DetalleContactos extends Model {}
    
    /**
     * @property int $id
     * @property int $pago_id
     * @property float $monto
     * @property string|null $banco
     * @property string|null $bancop
     * @property string|null $obanco
     * @property string|null $imagen
     * @property Carbon|null $fecha
     * @property string|null $cuenta
     * @property string|null $titular
     * @property Carbon|null $fecha_deposito
     * @property string|null $observacion
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $nota
     * @property int|null $user_reg
     * @property Pago $pago
     * @method BelongsTo|_IH_Pago_QB pago()
     * @method static _IH_DetallePago_QB onWriteConnection()
     * @method _IH_DetallePago_QB newQuery()
     * @method static _IH_DetallePago_QB on(null|string $connection = null)
     * @method static _IH_DetallePago_QB query()
     * @method static _IH_DetallePago_QB with(array|string $relations)
     * @method _IH_DetallePago_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DetallePago_C|DetallePago[] all()
     * @ownLinks pago_id,\App\Models\Pago,id
     * @mixin _IH_DetallePago_QB
     */
    class DetallePago extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property string $codigo
     * @property string $nombre_empresa
     * @property string $mes
     * @property string|null $anio
     * @property string $ruc
     * @property float $cantidad
     * @property string|null $adjunto
     * @property string $tipo_banca
     * @property float $porcentaje
     * @property float $ft
     * @property float $courier
     * @property float $total
     * @property float|null $saldo
     * @property string|null $descripcion
     * @property string|null $nota
     * @property string|null $envio_doc
     * @property Carbon|null $fecha_envio_doc
     * @property int $cant_compro
     * @property Carbon|null $fecha_envio_doc_fis
     * @property string|null $foto1
     * @property string|null $foto2
     * @property string|null $atendido_por
     * @property Carbon|null $fecha_recepcion
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $atendido_por_id
     * @property string|null $sobre_valida
     * @property int|null $user_reg
     * @method static _IH_DetallePedido_QB onWriteConnection()
     * @method _IH_DetallePedido_QB newQuery()
     * @method static _IH_DetallePedido_QB on(null|string $connection = null)
     * @method static _IH_DetallePedido_QB query()
     * @method static _IH_DetallePedido_QB with(array|string $relations)
     * @method _IH_DetallePedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DetallePedido_C|DetallePedido[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_DetallePedido_QB
     */
    class DetallePedido extends Model {}
    
    /**
     * @property int $id
     * @property int $pago_id
     * @property int $client_id
     * @property int $asesor_id
     * @property string|null $bank_destino
     * @property string|null $bank_number
     * @property string|null $num_operacion
     * @property string|null $bank_titular
     * @property float $amount
     * @property int $status
     * @property string|null $voucher_disk
     * @property string|null $voucher_path
     * @property Carbon|null $returned_at
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property User $asesor
     * @method BelongsTo|_IH_User_QB asesor()
     * @property Cliente $cliente
     * @method BelongsTo|_IH_Cliente_QB cliente()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
     * @property-read int $notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB notifications()
     * @property Pago $pago
     * @method BelongsTo|_IH_Pago_QB pago()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
     * @property-read int $read_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB readNotifications()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
     * @property-read int $unread_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB unreadNotifications()
     * @method static _IH_Devolucion_QB onWriteConnection()
     * @method _IH_Devolucion_QB newQuery()
     * @method static _IH_Devolucion_QB on(null|string $connection = null)
     * @method static _IH_Devolucion_QB query()
     * @method static _IH_Devolucion_QB with(array|string $relations)
     * @method _IH_Devolucion_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Devolucion_C|Devolucion[] all()
     * @ownLinks pago_id,\App\Models\Pago,id
     * @mixin _IH_Devolucion_QB
     */
    class Devolucion extends Model {}
    
    /**
     * @property int $id
     * @property int $cliente_id
     * @property int|null $user_id
     * @property string|null $departamento
     * @property string|null $provincia
     * @property string|null $distrito
     * @property string|null $direccion
     * @property string|null $referencia
     * @property string|null $nombre
     * @property int|null $celular
     * @property int|null $direcciongrupo
     * @property int|null $cantidad
     * @property string|null $observacion
     * @property int|null $estado
     * @property bool|null $salvado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_DireccionEnvio_QB onWriteConnection()
     * @method _IH_DireccionEnvio_QB newQuery()
     * @method static _IH_DireccionEnvio_QB on(null|string $connection = null)
     * @method static _IH_DireccionEnvio_QB query()
     * @method static _IH_DireccionEnvio_QB with(array|string $relations)
     * @method _IH_DireccionEnvio_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DireccionEnvio_C|DireccionEnvio[] all()
     * @ownLinks cliente_id,\App\Models\Cliente,id|user_id,\App\Models\User,id
     * @mixin _IH_DireccionEnvio_QB
     */
    class DireccionEnvio extends Model {}
    
    /**
     * @property int $id
     * @property string|null $correlativo
     * @property string|null $destino
     * @property string|null $distribucion
     * @property int|null $condicion_envio_code
     * @property string|null $condicion_envio
     * @property string|null $subcondicion_envio
     * @property string|null $condicion_sobre
     * @property string|null $foto1
     * @property string|null $foto2
     * @property string|null $foto3
     * @property Carbon|null $fecha_recepcion
     * @property string|null $atendido_por
     * @property int|null $atendido_por_id
     * @property string|null $nombre_cliente
     * @property string|null $celular_cliente
     * @property string|null $icelular_cliente
     * @property int|null $estado
     * @property int|null $motorizado_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $cliente_id
     * @property int|null $user_id
     * @property string|null $codigos
     * @property string|null $producto
     * @property string|null $identificador
     * @property string|null $celular
     * @property string|null $nombre
     * @property Carbon|null $fecha
     * @property int|null $cantidad
     * @property float|null $importe
     * @property string|null $direccion
     * @property string|null $referencia
     * @property string|null $observacion
     * @property string|null $distrito
     * @property string|null $destino2
     * @property int|null $pedido_id
     * @property Carbon|null $fecha_salida
     * @property int $motorizado_status
     * @property string|null $motorizado_sustento_text
     * @property string|null $motorizado_sustento_foto
     * @property string|null $codigos_confirmados
     * @property string|null $cambio_direccion_sustento
     * @property Carbon|null $cambio_direccion_at
     * @property int $estado_consinsobre
     * @property Carbon|null $condicion_envio_at
     * @property string|null $gmlink
     * @property Carbon|null $reprogramacion_at
     * @property int|null $reprogramacion_solicitud_user_id
     * @property Carbon|null $reprogramacion_solicitud_at
     * @property int|null $reprogramacion_accept_user_id
     * @property Carbon|null $reprogramacion_accept_at
     * @property Carbon|null $fecha_salida_old_at
     * @property Carbon|null $courier_sync_at
     * @property Carbon|null $courier_failed_sync_at
     * @property bool|null $courier_sync_finalized
     * @property string|null $courier_estado
     * @property mixed|null $courier_data
     * @property int $relacionado
     * @property Carbon|null $add_screenshot_at
     * @property int|null $urgente
     * @property-read $fecha_salida_format attribute
     * @property-read bool $is_reprogramado attribute
     * @property DireccionEnvio $direccionEnvio
     * @method HasOne|_IH_DireccionEnvio_QB direccionEnvio()
     * @property _IH_DireccionEnvio_C|DireccionEnvio[] $direccionEnvios
     * @property-read int $direccion_envios_count
     * @method HasMany|_IH_DireccionEnvio_QB direccionEnvios()
     * @property GastoEnvio $gastoEnvio
     * @method HasOne|_IH_GastoEnvio_QB gastoEnvio()
     * @property _IH_GastoEnvio_C|GastoEnvio[] $gastoEnvios
     * @property-read int $gasto_envios_count
     * @method HasMany|_IH_GastoEnvio_QB gastoEnvios()
     * @property _IH_Media_C|Media[] $media
     * @property-read int $media_count
     * @method MorphToMany|_IH_Media_QB media()
     * @property User|null $motorizado
     * @method BelongsTo|_IH_User_QB motorizado()
     * @property _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] $motorizadoHistories
     * @property-read int $motorizado_histories_count
     * @method HasMany|_IH_PedidoMotorizadoHistory_QB motorizadoHistories()
     * @property _IH_Pedido_C|Pedido[] $pedidos
     * @property-read int $pedidos_count
     * @method HasMany|_IH_Pedido_QB pedidos()
     * @method static _IH_DireccionGrupo_QB onWriteConnection()
     * @method _IH_DireccionGrupo_QB newQuery()
     * @method static _IH_DireccionGrupo_QB on(null|string $connection = null)
     * @method static _IH_DireccionGrupo_QB query()
     * @method static _IH_DireccionGrupo_QB with(array|string $relations)
     * @method _IH_DireccionGrupo_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DireccionGrupo_C|DireccionGrupo[] all()
     * @ownLinks cliente_id,\App\Models\Cliente,id|user_id,\App\Models\User,id|pedido_id,\App\Models\Pedido,id
     * @foreignLinks id,\App\Models\PedidoMotorizadoHistory,direccion_grupo_id
     * @mixin _IH_DireccionGrupo_QB
     */
    class DireccionGrupo extends Model {}
    
    /**
     * @property int $id
     * @property int $direccion_id
     * @property int $pedido_id
     * @property string|null $codigo_pedido
     * @property int|null $direcciongrupo
     * @property string|null $empresa
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_DireccionPedido_QB onWriteConnection()
     * @method _IH_DireccionPedido_QB newQuery()
     * @method static _IH_DireccionPedido_QB on(null|string $connection = null)
     * @method static _IH_DireccionPedido_QB query()
     * @method static _IH_DireccionPedido_QB with(array|string $relations)
     * @method _IH_DireccionPedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DireccionPedido_C|DireccionPedido[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_DireccionPedido_QB
     */
    class DireccionPedido extends Model {}
    
    /**
     * @property int $id
     * @property int|null $user_id
     * @property string|null $rol
     * @property string|null $distrito
     * @property string|null $direccion_recojo
     * @property string|null $numero_recojo
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $referencia
     * @property string|null $destino
     * @property string|null $cliente
     * @method static _IH_Directions_QB onWriteConnection()
     * @method _IH_Directions_QB newQuery()
     * @method static _IH_Directions_QB on(null|string $connection = null)
     * @method static _IH_Directions_QB query()
     * @method static _IH_Directions_QB with(array|string $relations)
     * @method _IH_Directions_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Directions_C|Directions[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @mixin _IH_Directions_QB
     */
    class Directions extends Model {}
    
    /**
     * @property int $id
     * @property string|null $distrito
     * @property string|null $provincia
     * @property string|null $zona
     * @property string|null $sugerencia
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_Distrito_QB onWriteConnection()
     * @method _IH_Distrito_QB newQuery()
     * @method static _IH_Distrito_QB on(null|string $connection = null)
     * @method static _IH_Distrito_QB query()
     * @method static _IH_Distrito_QB with(array|string $relations)
     * @method _IH_Distrito_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Distrito_C|Distrito[] all()
     * @mixin _IH_Distrito_QB
     */
    class Distrito extends Model {}
    
    /**
     * @method static _IH_EntidadBancaria_QB onWriteConnection()
     * @method _IH_EntidadBancaria_QB newQuery()
     * @method static _IH_EntidadBancaria_QB on(null|string $connection = null)
     * @method static _IH_EntidadBancaria_QB query()
     * @method static _IH_EntidadBancaria_QB with(array|string $relations)
     * @method _IH_EntidadBancaria_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_EntidadBancaria_C|EntidadBancaria[] all()
     * @mixin _IH_EntidadBancaria_QB
     */
    class EntidadBancaria extends Model {}
    
    /**
     * @property int $id
     * @property string $title
     * @property Carbon $start
     * @property Carbon $end
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $color
     * @property string|null $description
     * @property string|null $colorEvento
     * @property string|null $fondoEvento
     * @property int|null $grupo
     * @property string|null $tipo
     * @property string|null $frecuencia
     * @property int|null $status
     * @property string|null $attach
     * @property int|null $unsigned
     * @method static _IH_Event_QB onWriteConnection()
     * @method _IH_Event_QB newQuery()
     * @method static _IH_Event_QB on(null|string $connection = null)
     * @method static _IH_Event_QB query()
     * @method static _IH_Event_QB with(array|string $relations)
     * @method _IH_Event_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Event_C|Event[] all()
     * @foreignLinks id,\App\Models\ImageAgenda,event_id
     * @mixin _IH_Event_QB
     */
    class Event extends Model {}
    
    /**
     * @property int $id
     * @property string $title
     * @property string|null $color
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $description
     * @property string|null $attach
     * @method static _IH_EventsUnsigned_QB onWriteConnection()
     * @method _IH_EventsUnsigned_QB newQuery()
     * @method static _IH_EventsUnsigned_QB on(null|string $connection = null)
     * @method static _IH_EventsUnsigned_QB query()
     * @method static _IH_EventsUnsigned_QB with(array|string $relations)
     * @method _IH_EventsUnsigned_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_EventsUnsigned_C|EventsUnsigned[] all()
     * @mixin _IH_EventsUnsigned_QB
     */
    class EventsUnsigned extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_anulacion_id
     * @property string $filename
     * @property string $filepath
     * @property string $type
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_FileUploadAnulacion_QB onWriteConnection()
     * @method _IH_FileUploadAnulacion_QB newQuery()
     * @method static _IH_FileUploadAnulacion_QB on(null|string $connection = null)
     * @method static _IH_FileUploadAnulacion_QB query()
     * @method static _IH_FileUploadAnulacion_QB with(array|string $relations)
     * @method _IH_FileUploadAnulacion_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_FileUploadAnulacion_C|FileUploadAnulacion[] all()
     * @mixin _IH_FileUploadAnulacion_QB
     */
    class FileUploadAnulacion extends Model {}
    
    /**
     * @property int $id
     * @property int $cliente_id
     * @property int $user_id
     * @property string|null $tracking
     * @property string|null $registro
     * @property string|null $foto
     * @property int|null $cantidad
     * @property float $importe
     * @property int|null $direcciongrupo
     * @property int|null $estado
     * @property bool|null $salvado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_GastoEnvio_QB onWriteConnection()
     * @method _IH_GastoEnvio_QB newQuery()
     * @method static _IH_GastoEnvio_QB on(null|string $connection = null)
     * @method static _IH_GastoEnvio_QB query()
     * @method static _IH_GastoEnvio_QB with(array|string $relations)
     * @method _IH_GastoEnvio_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_GastoEnvio_C|GastoEnvio[] all()
     * @ownLinks cliente_id,\App\Models\Cliente,id|user_id,\App\Models\User,id
     * @mixin _IH_GastoEnvio_QB
     */
    class GastoEnvio extends Model {}
    
    /**
     * @property int $id
     * @property int $gasto_id
     * @property int $pedido_id
     * @property string|null $codigo_pedido
     * @property int|null $direcciongrupo
     * @property string|null $empresa
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_GastoPedido_QB onWriteConnection()
     * @method _IH_GastoPedido_QB newQuery()
     * @method static _IH_GastoPedido_QB on(null|string $connection = null)
     * @method static _IH_GastoPedido_QB query()
     * @method static _IH_GastoPedido_QB with(array|string $relations)
     * @method _IH_GastoPedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_GastoPedido_C|GastoPedido[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_GastoPedido_QB
     */
    class GastoPedido extends Model {}
    
    /**
     * @property int $id
     * @property string $zona
     * @property string $provincia
     * @property string $distrito
     * @property string $direccion
     * @property string|null $referencia
     * @property string|null $cliente_recibe
     * @property string|null $telefono
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Carbon|null $deleted_at
     * @property int|null $urgente
     * @property _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] $motorizadoHistories
     * @property-read int $motorizado_histories_count
     * @method HasMany|_IH_PedidoMotorizadoHistory_QB motorizadoHistories()
     * @property _IH_Pedido_C|Pedido[] $pedidos
     * @property-read int $pedidos_count
     * @method BelongsToMany|_IH_Pedido_QB pedidos()
     * @method static _IH_GrupoPedido_QB onWriteConnection()
     * @method _IH_GrupoPedido_QB newQuery()
     * @method static _IH_GrupoPedido_QB on(null|string $connection = null)
     * @method static _IH_GrupoPedido_QB query()
     * @method static _IH_GrupoPedido_QB with(array|string $relations)
     * @method _IH_GrupoPedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_GrupoPedido_C|GrupoPedido[] all()
     * @foreignLinks 
     * @mixin _IH_GrupoPedido_QB
     */
    class GrupoPedido extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property string|null $correlativo
     * @property int $cliente_id
     * @property int $user_id
     * @property string $identificador
     * @property string $exidentificador
     * @property string $icelular_asesor
     * @property string $celular_cliente
     * @property string $icelular_cliente
     * @property string $creador
     * @property int $pago
     * @property int $pagado
     * @property string $condicion_envio
     * @property int $condicion_envio_code
     * @property Carbon $condicion_envio_at
     * @property string $codigo
     * @property string $motivo
     * @property string $responsable
     * @property string $modificador
     * @property int $estado
     * @property int $da_confirmar_descarga
     * @property int $estado_sobre
     * @property int $estado_consinsobre
     * @property string $env_destino
     * @property string $env_distrito
     * @property string $env_zona
     * @property string $env_zona_asignada
     * @property string $env_nombre_cliente_recibe
     * @property string $env_celular_cliente_recibe
     * @property string $env_cantidad
     * @property string $env_direccion
     * @property string $env_tracking
     * @property string $env_referencia
     * @property string $env_numregistro
     * @property string $env_rotulo
     * @property string $env_observacion
     * @property string $env_gmlink
     * @property string $env_importe
     * @property string $estado_ruta
     * @property string $direccion_grupo
     * @property int $estado_correccion
     * @property string $nombre_empresa
     * @property string $mes
     * @property int $anio
     * @property string $ruc
     * @property float $cantidad
     * @property string $tipo_banca
     * @property string $porcentaje
     * @property string $ft
     * @property string $courier
     * @property string $total
     * @property string $saldo
     * @property string $descripcion
     * @property string $nota
     * @property string $cant_compro
     * @property string $atendido_por
     * @property string $atendido_por_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_HistoriaPedidos_QB onWriteConnection()
     * @method _IH_HistoriaPedidos_QB newQuery()
     * @method static _IH_HistoriaPedidos_QB on(null|string $connection = null)
     * @method static _IH_HistoriaPedidos_QB query()
     * @method static _IH_HistoriaPedidos_QB with(array|string $relations)
     * @method _IH_HistoriaPedidos_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_HistoriaPedidos_C|HistoriaPedidos[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id|cliente_id,\App\Models\Cliente,id|user_id,\App\Models\User,id
     * @mixin _IH_HistoriaPedidos_QB
     */
    class HistoriaPedidos extends Model {}
    
    /**
     * @property int $id
     * @property int|null $user_id
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $accion
     * @property int|null $responsable
     * @method static _IH_HistorialVidas_QB onWriteConnection()
     * @method _IH_HistorialVidas_QB newQuery()
     * @method static _IH_HistorialVidas_QB on(null|string $connection = null)
     * @method static _IH_HistorialVidas_QB query()
     * @method static _IH_HistorialVidas_QB with(array|string $relations)
     * @method _IH_HistorialVidas_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_HistorialVidas_C|HistorialVidas[] all()
     * @ownLinks user_id,\App\Models\User,id
     * @mixin _IH_HistorialVidas_QB
     */
    class HistorialVidas extends Model {}
    
    /**
     * @property int $id
     * @property int|null $unsigned
     * @property int|null $event_id
     * @property string|null $filename
     * @property string|null $filepath
     * @property string|null $filetype
     * @property int|null $status
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_ImageAgenda_QB onWriteConnection()
     * @method _IH_ImageAgenda_QB newQuery()
     * @method static _IH_ImageAgenda_QB on(null|string $connection = null)
     * @method static _IH_ImageAgenda_QB query()
     * @method static _IH_ImageAgenda_QB with(array|string $relations)
     * @method _IH_ImageAgenda_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ImageAgenda_C|ImageAgenda[] all()
     * @ownLinks event_id,\App\Models\Event,id
     * @mixin _IH_ImageAgenda_QB
     */
    class ImageAgenda extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property string|null $adjunto
     * @property int|null $confirm
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property-read null $link attribute
     * @method static _IH_ImagenAtencion_QB onWriteConnection()
     * @method _IH_ImagenAtencion_QB newQuery()
     * @method static _IH_ImagenAtencion_QB on(null|string $connection = null)
     * @method static _IH_ImagenAtencion_QB query()
     * @method static _IH_ImagenAtencion_QB with(array|string $relations)
     * @method _IH_ImagenAtencion_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ImagenAtencion_C|ImagenAtencion[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_ImagenAtencion_QB
     */
    class ImagenAtencion extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property string|null $adjunto
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_ImagenPedido_QB onWriteConnection()
     * @method _IH_ImagenPedido_QB newQuery()
     * @method static _IH_ImagenPedido_QB on(null|string $connection = null)
     * @method static _IH_ImagenPedido_QB query()
     * @method static _IH_ImagenPedido_QB with(array|string $relations)
     * @method _IH_ImagenPedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ImagenPedido_C|ImagenPedido[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_ImagenPedido_QB
     */
    class ImagenPedido extends Model {}
    
    /**
     * @property int $id
     * @property string|null $user_identificador
     * @property int|null $a_2021_11
     * @property string $s_2021_11
     * @property int|null $a_2021_12
     * @property string $s_2021_12
     * @property int|null $a_2022_01
     * @property string $s_2022_01
     * @property int|null $a_2022_02
     * @property string $s_2022_02
     * @property int|null $a_2022_03
     * @property string $s_2022_03
     * @property int|null $a_2022_04
     * @property string $s_2022_04
     * @property int|null $a_2022_05
     * @property string $s_2022_05
     * @property int|null $a_2022_06
     * @property string $s_2022_06
     * @property int|null $a_2022_07
     * @property string $s_2022_07
     * @property int|null $a_2022_08
     * @property string $s_2022_08
     * @property int|null $a_2022_09
     * @property string $s_2022_09
     * @property int|null $a_2022_10
     * @property string $s_2022_10
     * @property int|null $a_2022_11
     * @property string $s_2022_11
     * @property int|null $a_2022_12
     * @property string $s_2022_12
     * @property int|null $a_2023_01
     * @property string|null $s_2023_01
     * @method static _IH_ListadoResultado_QB onWriteConnection()
     * @method _IH_ListadoResultado_QB newQuery()
     * @method static _IH_ListadoResultado_QB on(null|string $connection = null)
     * @method static _IH_ListadoResultado_QB query()
     * @method static _IH_ListadoResultado_QB with(array|string $relations)
     * @method _IH_ListadoResultado_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ListadoResultado_C|ListadoResultado[] all()
     * @mixin _IH_ListadoResultado_QB
     */
    class ListadoResultado extends Model {}
    
    /**
     * @property int $id
     * @property int $model_id
     * @property string $model_type
     * @property string|null $uuid
     * @property string $collection_name
     * @property string $name
     * @property string $file_name
     * @property string|null $mime_type
     * @property string $disk
     * @property string|null $conversions_disk
     * @property int $size
     * @property array $manipulations
     * @property array $custom_properties
     * @property array $generated_conversions
     * @property array $responsive_images
     * @property int|null $order_column
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property-read $created_at_format attribute
     * @property-read $media_link attribute
     * @method static _IH_Media_QB onWriteConnection()
     * @method _IH_Media_QB newQuery()
     * @method static _IH_Media_QB on(null|string $connection = null)
     * @method static _IH_Media_QB query()
     * @method static _IH_Media_QB with(array|string $relations)
     * @method _IH_Media_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Media_C|Media[] all()
     * @mixin _IH_Media_QB
     */
    class Media extends Model {}
    
    /**
     * @method static _IH_Membership_QB onWriteConnection()
     * @method _IH_Membership_QB newQuery()
     * @method static _IH_Membership_QB on(null|string $connection = null)
     * @method static _IH_Membership_QB query()
     * @method static _IH_Membership_QB with(array|string $relations)
     * @method _IH_Membership_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Membership_C|Membership[] all()
     * @mixin _IH_Membership_QB
     */
    class Membership extends Model {}
    
    /**
     * @method static _IH_Meta_QB onWriteConnection()
     * @method _IH_Meta_QB newQuery()
     * @method static _IH_Meta_QB on(null|string $connection = null)
     * @method static _IH_Meta_QB query()
     * @method static _IH_Meta_QB with(array|string $relations)
     * @method _IH_Meta_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Meta_C|Meta[] all()
     * @mixin _IH_Meta_QB
     */
    class Meta extends Model {}
    
    /**
     * @property int $id
     * @property string|null $banco
     * @property string|null $titular
     * @property float|null $importe
     * @property string|null $tipo
     * @property string|null $descripcion_otros
     * @property Carbon|null $fecha
     * @property int|null $pago
     * @property int|null $detpago
     * @property int|null $cabpago
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_MovimientoBancario_QB onWriteConnection()
     * @method _IH_MovimientoBancario_QB newQuery()
     * @method static _IH_MovimientoBancario_QB on(null|string $connection = null)
     * @method static _IH_MovimientoBancario_QB query()
     * @method static _IH_MovimientoBancario_QB with(array|string $relations)
     * @method _IH_MovimientoBancario_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_MovimientoBancario_C|MovimientoBancario[] all()
     * @mixin _IH_MovimientoBancario_QB
     */
    class MovimientoBancario extends Model {}
    
    /**
     * @property int $id
     * @property string $obs
     * @property string|null $nombre_sede
     * @property Carbon $fecha_creacion
     * @property string|null $estado_tracking
     * @property int|null $id_rpt_envio_ruta
     * @property int|null $status
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string|null $numerotrack
     * @property string|null $aniotrack
     * @method static _IH_OlvaMovimiento_QB onWriteConnection()
     * @method _IH_OlvaMovimiento_QB newQuery()
     * @method static _IH_OlvaMovimiento_QB on(null|string $connection = null)
     * @method static _IH_OlvaMovimiento_QB query()
     * @method static _IH_OlvaMovimiento_QB with(array|string $relations)
     * @method _IH_OlvaMovimiento_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_OlvaMovimiento_C|OlvaMovimiento[] all()
     * @mixin _IH_OlvaMovimiento_QB
     */
    class OlvaMovimiento extends Model {}
    
    /**
     * @property int $id
     * @property int $user_id
     * @property int $cliente_id
     * @property float|null $total_cobro
     * @property float|null $total_pagado
     * @property string|null $observacion
     * @property string|null $condicion
     * @property string|null $subcondicion
     * @property int|null $subcondicion_code
     * @property string|null $notificacion
     * @property float|null $saldo
     * @property float|null $diferencia
     * @property Carbon|null $fecha_aprobacion
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $condicion_code
     * @property string|null $correlativo
     * @property string|null $user_identificador
     * @property string|null $user_clavepedido
     * @property int|null $user_reg
     * @property-read string $code_id attribute
     * @property-read string $id_code attribute
     * @property _IH_DetallePago_C|DetallePago[] $detalle_pagos
     * @property-read int $detalle_pagos_count
     * @method HasMany|_IH_DetallePago_QB detalle_pagos()
     * @property _IH_PagoPedido_C|PagoPedido[] $pago_pedidos
     * @property-read int $pago_pedidos_count
     * @method HasMany|_IH_PagoPedido_QB pago_pedidos()
     * @property User $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Pago_QB onWriteConnection()
     * @method _IH_Pago_QB newQuery()
     * @method static _IH_Pago_QB on(null|string $connection = null)
     * @method static _IH_Pago_QB query()
     * @method static _IH_Pago_QB with(array|string $relations)
     * @method _IH_Pago_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Pago_C|Pago[] all()
     * @ownLinks user_id,\App\Models\User,id|cliente_id,\App\Models\Cliente,id
     * @foreignLinks id,\App\Models\DetallePago,pago_id|id,\App\Models\Devolucion,pago_id|id,\App\Models\PagoPedido,pago_id
     * @mixin _IH_Pago_QB
     * @method static PagoFactory factory(...$parameters)
     */
    class Pago extends Model {}
    
    /**
     * @property int $id
     * @property int $pago_id
     * @property int $pedido_id
     * @property int|null $pagado
     * @property float|null $abono
     * @property int $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_PagoPedido_QB onWriteConnection()
     * @method _IH_PagoPedido_QB newQuery()
     * @method static _IH_PagoPedido_QB on(null|string $connection = null)
     * @method static _IH_PagoPedido_QB query()
     * @method static _IH_PagoPedido_QB with(array|string $relations)
     * @method _IH_PagoPedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PagoPedido_C|PagoPedido[] all()
     * @ownLinks pago_id,\App\Models\Pago,id|pedido_id,\App\Models\Pedido,id
     * @mixin _IH_PagoPedido_QB
     */
    class PagoPedido extends Model {}
    
    /**
     * @property string $email
     * @property string|null $token
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_PasswordReset_QB onWriteConnection()
     * @method _IH_PasswordReset_QB newQuery()
     * @method static _IH_PasswordReset_QB on(null|string $connection = null)
     * @method static _IH_PasswordReset_QB query()
     * @method static _IH_PasswordReset_QB with(array|string $relations)
     * @method _IH_PasswordReset_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PasswordReset_C|PasswordReset[] all()
     * @mixin _IH_PasswordReset_QB
     */
    class PasswordReset extends Model {}
    
    /**
     * @property int $id
     * @property string|null $correlativo
     * @property int $cliente_id
     * @property int $user_id
     * @property string|null $creador
     * @property int|null $pago
     * @property int|null $pagado
     * @property string|null $destino
     * @property string|null $trecking
     * @property string|null $direccion
     * @property string|null $condicion_envio
     * @property int|null $condicion_envio_code
     * @property string|null $condicion
     * @property int|null $condicion_code
     * @property int|null $condicion_int
     * @property string|null $codigo
     * @property string|null $notificacion
     * @property string|null $motivo
     * @property string|null $responsable
     * @property string|null $modificador
     * @property int|null $devuelto
     * @property int|null $cant_devuelto
     * @property string|null $observacion_devuelto
     * @property int $estado
     * @property bool|null $da_confirmar_descarga
     * @property string|null $sustento_adjunto
     * @property string|null $path_adjunto_anular
     * @property string|null $path_adjunto_anular_disk
     * @property bool $pendiente_anulacion
     * @property int|null $user_anulacion_id
     * @property Carbon|null $fecha_anulacion
     * @property Carbon|null $fecha_anulacion_confirm
     * @property Carbon|null $fecha_anulacion_denegada
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Carbon|null $returned_at
     * @property int|null $envio
     * @property int|null $estado_condicion_envio
     * @property int|null $estado_condicion_pedido
     * @property int|null $estado_sobre
     * @property string|null $env_destino
     * @property string|null $env_distrito
     * @property string|null $env_zona
     * @property string|null $env_zona_asignada
     * @property string|null $env_nombre_cliente_recibe
     * @property string|null $env_celular_cliente_recibe
     * @property string|null $env_cantidad
     * @property string|null $env_direccion
     * @property string|null $env_tracking
     * @property string|null $env_referencia
     * @property string|null $env_numregistro
     * @property string|null $env_rotulo
     * @property string|null $env_observacion
     * @property string|null $env_importe
     * @property int|null $estado_ruta
     * @property int|null $direccion_grupo
     * @property Carbon|null $fecha_salida
     * @property string|null $cambio_direccion_sustento
     * @property string|null $identificador
     * @property string|null $exidentificador
     * @property string|null $icelular_asesor
     * @property string|null $icelular_cliente
     * @property Carbon|null $fecha_envio_op_courier
     * @property string|null $celular_cliente
     * @property Carbon|null $cambio_direccion_at
     * @property int $estado_consinsobre
     * @property Carbon|null $fecha_envio_atendido_op
     * @property Carbon|null $condicion_envio_at
     * @property string|null $env_gmlink
     * @property Carbon|null $courier_sync_at
     * @property Carbon|null $courier_failed_sync_at
     * @property bool|null $courier_sync_finalized
     * @property string|null $courier_estado
     * @property array|null $courier_data
     * @property string $condicion_envio_anterior
     * @property int $condicion_envio_code_anterior
     * @property string $codigo_anterior
     * @property int $pedidoid_anterior
     * @property string|null $env_sustento
     * @property int $estado_correccion
     * @property string|null $user_clavepedido
     * @property int|null $user_reg
     * @property-read string $condicion_envio_color attribute
     * @property-read $id_code attribute
     * @property Cliente $cliente
     * @method BelongsTo|_IH_Cliente_QB cliente()
     * @property DetallePedido $detallePedido
     * @method HasOne|_IH_DetallePedido_QB detallePedido()
     * @property _IH_DetallePedido_C|DetallePedido[] $detallePedidos
     * @property-read int $detalle_pedidos_count
     * @method HasMany|_IH_DetallePedido_QB detallePedidos()
     * @property DireccionGrupo|null $direcciongrupo
     * @method BelongsTo|_IH_DireccionGrupo_QB direcciongrupo()
     * @property _IH_GrupoPedido_C|GrupoPedido[] $grupoPedidos
     * @property-read int $grupo_pedidos_count
     * @method BelongsToMany|_IH_GrupoPedido_QB grupoPedidos()
     * @property _IH_ImagenAtencion_C|ImagenAtencion[] $imagenAtencion
     * @property-read int $imagen_atencion_count
     * @method HasMany|_IH_ImagenAtencion_QB imagenAtencion()
     * @property _IH_PagoPedido_C|PagoPedido[] $pagoPedidos
     * @property-read int $pago_pedidos_count
     * @method HasMany|_IH_PagoPedido_QB pagoPedidos()
     * @property User $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Pedido_QB onWriteConnection()
     * @method _IH_Pedido_QB newQuery()
     * @method static _IH_Pedido_QB on(null|string $connection = null)
     * @method static _IH_Pedido_QB query()
     * @method static _IH_Pedido_QB with(array|string $relations)
     * @method _IH_Pedido_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Pedido_C|Pedido[] all()
     * @ownLinks cliente_id,\App\Models\Cliente,id|user_id,\App\Models\User,id
     * @foreignLinks id,\App\Models\DetallePedido,pedido_id|id,\App\Models\DireccionGrupo,pedido_id|id,\App\Models\DireccionPedido,pedido_id|id,\App\Models\GastoPedido,pedido_id|id,\App\Models\ImagenAtencion,pedido_id|id,\App\Models\ImagenPedido,pedido_id|id,\App\Models\PagoPedido,pedido_id|id,\App\Models\PedidoMotorizadoHistory,pedido_id|id,\App\Models\HistoriaPedidos,pedido_id|id,\App\Models\PedidosAnulacion,pedido_id
     * @mixin _IH_Pedido_QB
     */
    class Pedido extends Model {}
    
    /**
     * @property int $id
     * @property int $user_id
     * @property string $identificador
     * @property string $cliente_id
     * @property string $ruc
     * @property string $empresa
     * @property string $mes
     * @property string $year
     * @property string $cantidad
     * @property string $tipo_banca
     * @property string $descripcion
     * @property string $nota
     * @property string $courier_price
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_PedidoHistory_QB onWriteConnection()
     * @method _IH_PedidoHistory_QB newQuery()
     * @method static _IH_PedidoHistory_QB on(null|string $connection = null)
     * @method static _IH_PedidoHistory_QB query()
     * @method static _IH_PedidoHistory_QB with(array|string $relations)
     * @method _IH_PedidoHistory_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PedidoHistory_C|PedidoHistory[] all()
     * @ownLinks user_id,\App\Models\User,id|cliente_id,\App\Models\Cliente,id
     * @mixin _IH_PedidoHistory_QB
     */
    class PedidoHistory extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property int $direccion_grupo_id
     * @property int|null $pedido_grupo_id
     * @property int $status
     * @property string|null $sustento_text
     * @property string|null $sustento_foto
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Carbon|null $deleted_at
     * @property int|null $old_direccion_grupo_id
     * @method static _IH_PedidoMotorizadoHistory_QB onWriteConnection()
     * @method _IH_PedidoMotorizadoHistory_QB newQuery()
     * @method static _IH_PedidoMotorizadoHistory_QB on(null|string $connection = null)
     * @method static _IH_PedidoMotorizadoHistory_QB query()
     * @method static _IH_PedidoMotorizadoHistory_QB with(array|string $relations)
     * @method _IH_PedidoMotorizadoHistory_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id|direccion_grupo_id,\App\Models\DireccionGrupo,id
     * @mixin _IH_PedidoMotorizadoHistory_QB
     */
    class PedidoMotorizadoHistory extends Model {}
    
    /**
     * @property int $id
     * @property int|null $condicion_envio_code
     * @property Carbon|null $fecha
     * @property int|null $pedido
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $notificado
     * @property array|null $json_envio
     * @method static _IH_PedidoMovimientoEstado_QB onWriteConnection()
     * @method _IH_PedidoMovimientoEstado_QB newQuery()
     * @method static _IH_PedidoMovimientoEstado_QB on(null|string $connection = null)
     * @method static _IH_PedidoMovimientoEstado_QB query()
     * @method static _IH_PedidoMovimientoEstado_QB with(array|string $relations)
     * @method _IH_PedidoMovimientoEstado_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] all()
     * @mixin _IH_PedidoMovimientoEstado_QB
     */
    class PedidoMovimientoEstado extends Model {}
    
    /**
     * @property int $id
     * @property int $pedido_id
     * @property int|null $user_id_asesor
     * @property string|null $motivo_solicitud
     * @property int $estado_aprueba_asesor
     * @property int|null $user_id_encargado
     * @property string|null $motivo_sol_encargado
     * @property int $estado_aprueba_encargado
     * @property int|null $user_id_administrador
     * @property string $motivo_sol_admin
     * @property int $estado_aprueba_administrador
     * @property int|null $user_id_jefeop
     * @property string|null $motivo_jefeop_admin
     * @property int $estado_aprueba_jefeop
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property float $total_anular
     * @property string $tipo
     * @property string|null $files_asesor_ids
     * @property string|null $files_encargado_ids
     * @property string|null $filesadmin_ids
     * @property string|null $files_jefeop_ids
     * @property int|null $state_solicitud
     * @property string|null $resposable_create_asesor
     * @property string|null $resposable_aprob_encargado
     * @property string|null $files_responsable_asesor
     * @property string|null $resposable_aprob_admin
     * @property float|null $cantidad
     * @property float|null $cantidad_resta
     * @property float|null $difanterior
     * @method static _IH_PedidosAnulacion_QB onWriteConnection()
     * @method _IH_PedidosAnulacion_QB newQuery()
     * @method static _IH_PedidosAnulacion_QB on(null|string $connection = null)
     * @method static _IH_PedidosAnulacion_QB query()
     * @method static _IH_PedidosAnulacion_QB with(array|string $relations)
     * @method _IH_PedidosAnulacion_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_PedidosAnulacion_C|PedidosAnulacion[] all()
     * @ownLinks pedido_id,\App\Models\Pedido,id
     * @mixin _IH_PedidosAnulacion_QB
     */
    class PedidosAnulacion extends Model {}
    
    /**
     * @property int $id
     * @property int $cliente_id
     * @property string|null $nombre
     * @property float|null $porcentaje
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_Porcentaje_QB onWriteConnection()
     * @method _IH_Porcentaje_QB newQuery()
     * @method static _IH_Porcentaje_QB on(null|string $connection = null)
     * @method static _IH_Porcentaje_QB query()
     * @method static _IH_Porcentaje_QB with(array|string $relations)
     * @method _IH_Porcentaje_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Porcentaje_C|Porcentaje[] all()
     * @ownLinks cliente_id,\App\Models\Cliente,id
     * @mixin _IH_Porcentaje_QB
     */
    class Porcentaje extends Model {}
    
    /**
     * @method static _IH_Provincia_QB onWriteConnection()
     * @method _IH_Provincia_QB newQuery()
     * @method static _IH_Provincia_QB on(null|string $connection = null)
     * @method static _IH_Provincia_QB query()
     * @method static _IH_Provincia_QB with(array|string $relations)
     * @method _IH_Provincia_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Provincia_C|Provincia[] all()
     * @mixin _IH_Provincia_QB
     */
    class Provincia extends Model {}
    
    /**
     * @property int $id
     * @property string $num_ruc
     * @property int $user_id
     * @property int $cliente_id
     * @property string|null $empresa
     * @property int|null $estado
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property float|null $porcentaje
     * @property Cliente $cliente
     * @method BelongsTo|_IH_Cliente_QB cliente()
     * @property User $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Ruc_QB onWriteConnection()
     * @method _IH_Ruc_QB newQuery()
     * @method static _IH_Ruc_QB on(null|string $connection = null)
     * @method static _IH_Ruc_QB query()
     * @method static _IH_Ruc_QB with(array|string $relations)
     * @method _IH_Ruc_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Ruc_C|Ruc[] all()
     * @ownLinks user_id,\App\Models\User,id|cliente_id,\App\Models\Cliente,id
     * @mixin _IH_Ruc_QB
     */
    class Ruc extends Model {}
    
    /**
     * @property int $id
     * @property int $cliente_id
     * @property string $situacion
     * @property Carbon|null $fecha
     * @property int|null $cantidad_pedidos
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property int|null $flag_fp
     * @property int|null $user_id
     * @property string $user_identificador
     * @property string|null $user_clavepedido
     * @property _IH_Media_C|Media[] $media
     * @property-read int $media_count
     * @method MorphToMany|_IH_Media_QB media()
     * @method static _IH_SituacionClientes_QB onWriteConnection()
     * @method _IH_SituacionClientes_QB newQuery()
     * @method static _IH_SituacionClientes_QB on(null|string $connection = null)
     * @method static _IH_SituacionClientes_QB query()
     * @method static _IH_SituacionClientes_QB with(array|string $relations)
     * @method _IH_SituacionClientes_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_SituacionClientes_C|SituacionClientes[] all()
     * @ownLinks cliente_id,\App\Models\User,id|user_id,\App\Models\User,id
     * @mixin _IH_SituacionClientes_QB
     */
    class SituacionClientes extends Model {}
    
    /**
     * @property User $owner
     * @method BelongsTo|_IH_User_QB owner()
     * @property _IH_TeamInvitation_C|TeamInvitation[] $teamInvitations
     * @property-read int $team_invitations_count
     * @method HasMany|_IH_TeamInvitation_QB teamInvitations()
     * @property _IH_User_C|User[] $users
     * @property-read int $users_count
     * @method BelongsToMany|_IH_User_QB users()
     * @method static _IH_Team_QB onWriteConnection()
     * @method _IH_Team_QB newQuery()
     * @method static _IH_Team_QB on(null|string $connection = null)
     * @method static _IH_Team_QB query()
     * @method static _IH_Team_QB with(array|string $relations)
     * @method _IH_Team_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Team_C|Team[] all()
     * @mixin _IH_Team_QB
     * @method static TeamFactory factory(...$parameters)
     */
    class Team extends Model {}
    
    /**
     * @property Team $team
     * @method BelongsTo|_IH_Team_QB team()
     * @method static _IH_TeamInvitation_QB onWriteConnection()
     * @method _IH_TeamInvitation_QB newQuery()
     * @method static _IH_TeamInvitation_QB on(null|string $connection = null)
     * @method static _IH_TeamInvitation_QB query()
     * @method static _IH_TeamInvitation_QB with(array|string $relations)
     * @method _IH_TeamInvitation_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_TeamInvitation_C|TeamInvitation[] all()
     * @mixin _IH_TeamInvitation_QB
     */
    class TeamInvitation extends Model {}
    
    /**
     * @property int $id
     * @property string|null $descripcion
     * @property string|null $banco
     * @method static _IH_TipoMovimiento_QB onWriteConnection()
     * @method _IH_TipoMovimiento_QB newQuery()
     * @method static _IH_TipoMovimiento_QB on(null|string $connection = null)
     * @method static _IH_TipoMovimiento_QB query()
     * @method static _IH_TipoMovimiento_QB with(array|string $relations)
     * @method _IH_TipoMovimiento_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_TipoMovimiento_C|TipoMovimiento[] all()
     * @mixin _IH_TipoMovimiento_QB
     */
    class TipoMovimiento extends Model {}
    
    /**
     * @method static _IH_Titular_QB onWriteConnection()
     * @method _IH_Titular_QB newQuery()
     * @method static _IH_Titular_QB on(null|string $connection = null)
     * @method static _IH_Titular_QB query()
     * @method static _IH_Titular_QB with(array|string $relations)
     * @method _IH_Titular_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Titular_C|Titular[] all()
     * @mixin _IH_Titular_QB
     */
    class Titular extends Model {}
    
    /**
     * @property int $id
     * @property string|null $obs
     * @property string $valores_ant
     * @property string $valores_act
     * @property Carbon|null $fecha_creacion
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @method static _IH_UpdateMovimiento_QB onWriteConnection()
     * @method _IH_UpdateMovimiento_QB newQuery()
     * @method static _IH_UpdateMovimiento_QB on(null|string $connection = null)
     * @method static _IH_UpdateMovimiento_QB query()
     * @method static _IH_UpdateMovimiento_QB with(array|string $relations)
     * @method _IH_UpdateMovimiento_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_UpdateMovimiento_C|UpdateMovimiento[] all()
     * @mixin _IH_UpdateMovimiento_QB
     */
    class UpdateMovimiento extends Model {}
    
    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property string|null $remember_token
     * @property string|null $letra
     * @property int $estado
     * @property string $rol
     * @property string|null $supervisor
     * @property string|null $operario
     * @property string|null $llamada
     * @property string|null $jefe
     * @property string|null $identificador
     * @property string|null $exidentificador
     * @property string|null $unificado
     * @property string|null $meta_pedido
     * @property float|null $meta_cobro
     * @property int|null $celular
     * @property string|null $provincia
     * @property string|null $distrito
     * @property string|null $direccion
     * @property string|null $referencia
     * @property int|null $current_team_id
     * @property string|null $profile_photo_path
     * @property bool $excluir_meta
     * @property string|null $zona
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property string $meta_pedido_2
     * @property int $vidas_total
     * @property int $vidas_restantes
     * @property int|null $cant_vidas_cero
     * @property int|null $meta_quincena
     * @property Carbon|null $birthday
     * @property string|null $clave_pedidos
     * @property-read string $profile_photo_url attribute
     * @property User|null $asesoroperario
     * @method BelongsTo|_IH_User_QB asesoroperario()
     * @property User|null $encargado
     * @method BelongsTo|_IH_User_QB encargado()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
     * @property-read int $notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB notifications()
     * @property _IH_Pedido_C|Pedido[] $pedidos
     * @property-read int $pedidos_count
     * @method HasMany|_IH_Pedido_QB pedidos()
     * @property _IH_Pedido_C|Pedido[] $pedidosActivos
     * @property-read int $pedidos_activos_count
     * @method HasMany|_IH_Pedido_QB pedidosActivos()
     * @property _IH_Permission_C|Permission[] $permissions
     * @property-read int $permissions_count
     * @method MorphToMany|_IH_Permission_QB permissions()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
     * @property-read int $read_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB readNotifications()
     * @property _IH_Role_C|Role[] $roles
     * @property-read int $roles_count
     * @method MorphToMany|_IH_Role_QB roles()
     * @property _IH_PersonalAccessToken_C|PersonalAccessToken[] $tokens
     * @property-read int $tokens_count
     * @method MorphToMany|_IH_PersonalAccessToken_QB tokens()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
     * @property-read int $unread_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB unreadNotifications()
     * @method static _IH_User_QB onWriteConnection()
     * @method _IH_User_QB newQuery()
     * @method static _IH_User_QB on(null|string $connection = null)
     * @method static _IH_User_QB query()
     * @method static _IH_User_QB with(array|string $relations)
     * @method _IH_User_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_User_C|User[] all()
     * @foreignLinks id,\App\Models\PedidoHistory,user_id|id,\App\Models\SituacionClientes,cliente_id|id,\App\Models\Cliente,user_id|id,\App\Models\DireccionEnvio,user_id|id,\App\Models\DireccionGrupo,user_id|id,\App\Models\GastoEnvio,user_id|id,\App\Models\Pago,user_id|id,\App\Models\Pedido,user_id|id,\App\Models\Ruc,user_id|id,\App\Models\SituacionClientes,user_id|id,\App\Models\Directions,user_id|id,\App\Models\HistoriaPedidos,user_id|id,\App\Models\HistorialVidas,user_id|id,\App\Models\CallAtention,user_id
     * @mixin _IH_User_QB
     * @method static UserFactory factory(...$parameters)
     */
    class User extends Model {}
}