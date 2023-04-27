<?php //0c8455869a88f52ea796d0ae3bf7370e
/** @noinspection all */

namespace LaravelIdea\Helper\App\Models {

    use App\Models\Alerta;
    use App\Models\AttachCorrection;
    use App\Models\CallAtention;
    use App\Models\Cliente;
    use App\Models\Correction;
    use App\Models\CourierRegistro;
    use App\Models\CuentaBancaria;
    use App\Models\Departamento;
    use App\Models\DetalleContactos;
    use App\Models\DetallePago;
    use App\Models\DetallePedido;
    use App\Models\Devolucion;
    use App\Models\DireccionEnvio;
    use App\Models\DireccionGrupo;
    use App\Models\DireccionPedido;
    use App\Models\Directions;
    use App\Models\Distrito;
    use App\Models\EntidadBancaria;
    use App\Models\Event;
    use App\Models\EventsUnsigned;
    use App\Models\FileUploadAnulacion;
    use App\Models\GastoEnvio;
    use App\Models\GastoPedido;
    use App\Models\GrupoPedido;
    use App\Models\HistorialVidas;
    use App\Models\HistoriaPedidos;
    use App\Models\ImageAgenda;
    use App\Models\ImagenAtencion;
    use App\Models\ImagenPedido;
    use App\Models\ListadoResultado;
    use App\Models\Media;
    use App\Models\Membership;
    use App\Models\Meta;
    use App\Models\MovimientoBancario;
    use App\Models\OlvaMovimiento;
    use App\Models\Pago;
    use App\Models\PagoPedido;
    use App\Models\PasswordReset;
    use App\Models\Pedido;
    use App\Models\PedidoHistory;
    use App\Models\PedidoMotorizadoHistory;
    use App\Models\PedidoMovimientoEstado;
    use App\Models\PedidosAnulacion;
    use App\Models\Porcentaje;
    use App\Models\Provincia;
    use App\Models\Ruc;
    use App\Models\SituacionClientes;
    use App\Models\Team;
    use App\Models\TeamInvitation;
    use App\Models\TipoMovimiento;
    use App\Models\Titular;
    use App\Models\UpdateMovimiento;
    use App\Models\User;
    use Carbon\CarbonInterface;
    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Database\Query\Expression;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Collection;
    use LaravelIdea\Helper\_BaseBuilder;
    use LaravelIdea\Helper\_BaseCollection;
    use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
    use Spatie\Permission\Contracts\Permission;
    use Spatie\Permission\Contracts\Role;
    
    /**
     * @method Alerta|null getOrPut($key, $value)
     * @method Alerta|$this shift(int $count = 1)
     * @method Alerta|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Alerta|$this pop(int $count = 1)
     * @method Alerta|null pull($key, $default = null)
     * @method Alerta|null last(callable $callback = null, $default = null)
     * @method Alerta|$this random(int|null $number = null)
     * @method Alerta|null sole($key = null, $operator = null, $value = null)
     * @method Alerta|null get($key, $default = null)
     * @method Alerta|null first(callable $callback = null, $default = null)
     * @method Alerta|null firstWhere(string $key, $operator = null, $value = null)
     * @method Alerta|null find($key, $default = null)
     * @method Alerta[] all()
     */
    class _IH_Alerta_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Alerta[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Alerta baseSole(array|string $columns = ['*'])
     * @method Alerta create(array $attributes = [])
     * @method _IH_Alerta_C|Alerta[] cursor()
     * @method Alerta|null|_IH_Alerta_C|Alerta[] find($id, array $columns = ['*'])
     * @method _IH_Alerta_C|Alerta[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Alerta|_IH_Alerta_C|Alerta[] findOrFail($id, array $columns = ['*'])
     * @method Alerta|_IH_Alerta_C|Alerta[] findOrNew($id, array $columns = ['*'])
     * @method Alerta first(array|string $columns = ['*'])
     * @method Alerta firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Alerta firstOrCreate(array $attributes = [], array $values = [])
     * @method Alerta firstOrFail(array $columns = ['*'])
     * @method Alerta firstOrNew(array $attributes = [], array $values = [])
     * @method Alerta firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Alerta forceCreate(array $attributes)
     * @method _IH_Alerta_C|Alerta[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Alerta_C|Alerta[] get(array|string $columns = ['*'])
     * @method Alerta getModel()
     * @method Alerta[] getModels(array|string $columns = ['*'])
     * @method _IH_Alerta_C|Alerta[] hydrate(array $items)
     * @method Alerta make(array $attributes = [])
     * @method Alerta newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Alerta[]|_IH_Alerta_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Alerta[]|_IH_Alerta_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Alerta sole(array|string $columns = ['*'])
     * @method Alerta updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Alerta_QB noFinalize()
     * @method _IH_Alerta_QB noReadTime(CarbonInterface $date)
     * @method _IH_Alerta_QB noReadTwoHours()
     * @method _IH_Alerta_QB withCurrentUser()
     */
    class _IH_Alerta_QB extends _BaseBuilder {}
    
    /**
     * @method AttachCorrection|null getOrPut($key, $value)
     * @method AttachCorrection|$this shift(int $count = 1)
     * @method AttachCorrection|null firstOrFail($key = null, $operator = null, $value = null)
     * @method AttachCorrection|$this pop(int $count = 1)
     * @method AttachCorrection|null pull($key, $default = null)
     * @method AttachCorrection|null last(callable $callback = null, $default = null)
     * @method AttachCorrection|$this random(int|null $number = null)
     * @method AttachCorrection|null sole($key = null, $operator = null, $value = null)
     * @method AttachCorrection|null get($key, $default = null)
     * @method AttachCorrection|null first(callable $callback = null, $default = null)
     * @method AttachCorrection|null firstWhere(string $key, $operator = null, $value = null)
     * @method AttachCorrection|null find($key, $default = null)
     * @method AttachCorrection[] all()
     */
    class _IH_AttachCorrection_C extends _BaseCollection {
        /**
         * @param int $size
         * @return AttachCorrection[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_AttachCorrection_QB whereId($value)
     * @method _IH_AttachCorrection_QB whereCorrectionId($value)
     * @method _IH_AttachCorrection_QB whereType($value)
     * @method _IH_AttachCorrection_QB whereName($value)
     * @method _IH_AttachCorrection_QB whereFileName($value)
     * @method _IH_AttachCorrection_QB whereMimeType($value)
     * @method _IH_AttachCorrection_QB whereDisk($value)
     * @method _IH_AttachCorrection_QB whereEstado($value)
     * @method _IH_AttachCorrection_QB whereCreatedAt($value)
     * @method _IH_AttachCorrection_QB whereUpdatedAt($value)
     * @method AttachCorrection baseSole(array|string $columns = ['*'])
     * @method AttachCorrection create(array $attributes = [])
     * @method _IH_AttachCorrection_C|AttachCorrection[] cursor()
     * @method AttachCorrection|null|_IH_AttachCorrection_C|AttachCorrection[] find($id, array $columns = ['*'])
     * @method _IH_AttachCorrection_C|AttachCorrection[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method AttachCorrection|_IH_AttachCorrection_C|AttachCorrection[] findOrFail($id, array $columns = ['*'])
     * @method AttachCorrection|_IH_AttachCorrection_C|AttachCorrection[] findOrNew($id, array $columns = ['*'])
     * @method AttachCorrection first(array|string $columns = ['*'])
     * @method AttachCorrection firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method AttachCorrection firstOrCreate(array $attributes = [], array $values = [])
     * @method AttachCorrection firstOrFail(array $columns = ['*'])
     * @method AttachCorrection firstOrNew(array $attributes = [], array $values = [])
     * @method AttachCorrection firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method AttachCorrection forceCreate(array $attributes)
     * @method _IH_AttachCorrection_C|AttachCorrection[] fromQuery(string $query, array $bindings = [])
     * @method _IH_AttachCorrection_C|AttachCorrection[] get(array|string $columns = ['*'])
     * @method AttachCorrection getModel()
     * @method AttachCorrection[] getModels(array|string $columns = ['*'])
     * @method _IH_AttachCorrection_C|AttachCorrection[] hydrate(array $items)
     * @method AttachCorrection make(array $attributes = [])
     * @method AttachCorrection newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|AttachCorrection[]|_IH_AttachCorrection_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|AttachCorrection[]|_IH_AttachCorrection_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method AttachCorrection sole(array|string $columns = ['*'])
     * @method AttachCorrection updateOrCreate(array $attributes, array $values = [])
     * @method _IH_AttachCorrection_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_AttachCorrection_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_AttachCorrection_QB extends _BaseBuilder {}
    
    /**
     * @method CallAtention|null getOrPut($key, $value)
     * @method CallAtention|$this shift(int $count = 1)
     * @method CallAtention|null firstOrFail($key = null, $operator = null, $value = null)
     * @method CallAtention|$this pop(int $count = 1)
     * @method CallAtention|null pull($key, $default = null)
     * @method CallAtention|null last(callable $callback = null, $default = null)
     * @method CallAtention|$this random(int|null $number = null)
     * @method CallAtention|null sole($key = null, $operator = null, $value = null)
     * @method CallAtention|null get($key, $default = null)
     * @method CallAtention|null first(callable $callback = null, $default = null)
     * @method CallAtention|null firstWhere(string $key, $operator = null, $value = null)
     * @method CallAtention|null find($key, $default = null)
     * @method CallAtention[] all()
     */
    class _IH_CallAtention_C extends _BaseCollection {
        /**
         * @param int $size
         * @return CallAtention[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_CallAtention_QB whereId($value)
     * @method _IH_CallAtention_QB whereCreatedAt($value)
     * @method _IH_CallAtention_QB whereUpdatedAt($value)
     * @method _IH_CallAtention_QB whereUserId($value)
     * @method _IH_CallAtention_QB whereUserIdentificador($value)
     * @method _IH_CallAtention_QB whereAccion($value)
     * @method _IH_CallAtention_QB whereResponsable($value)
     * @method CallAtention baseSole(array|string $columns = ['*'])
     * @method CallAtention create(array $attributes = [])
     * @method _IH_CallAtention_C|CallAtention[] cursor()
     * @method CallAtention|null|_IH_CallAtention_C|CallAtention[] find($id, array $columns = ['*'])
     * @method _IH_CallAtention_C|CallAtention[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method CallAtention|_IH_CallAtention_C|CallAtention[] findOrFail($id, array $columns = ['*'])
     * @method CallAtention|_IH_CallAtention_C|CallAtention[] findOrNew($id, array $columns = ['*'])
     * @method CallAtention first(array|string $columns = ['*'])
     * @method CallAtention firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method CallAtention firstOrCreate(array $attributes = [], array $values = [])
     * @method CallAtention firstOrFail(array $columns = ['*'])
     * @method CallAtention firstOrNew(array $attributes = [], array $values = [])
     * @method CallAtention firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method CallAtention forceCreate(array $attributes)
     * @method _IH_CallAtention_C|CallAtention[] fromQuery(string $query, array $bindings = [])
     * @method _IH_CallAtention_C|CallAtention[] get(array|string $columns = ['*'])
     * @method CallAtention getModel()
     * @method CallAtention[] getModels(array|string $columns = ['*'])
     * @method _IH_CallAtention_C|CallAtention[] hydrate(array $items)
     * @method CallAtention make(array $attributes = [])
     * @method CallAtention newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|CallAtention[]|_IH_CallAtention_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|CallAtention[]|_IH_CallAtention_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method CallAtention sole(array|string $columns = ['*'])
     * @method CallAtention updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_CallAtention_QB extends _BaseBuilder {}
    
    /**
     * @method Cliente|null getOrPut($key, $value)
     * @method Cliente|$this shift(int $count = 1)
     * @method Cliente|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Cliente|$this pop(int $count = 1)
     * @method Cliente|null pull($key, $default = null)
     * @method Cliente|null last(callable $callback = null, $default = null)
     * @method Cliente|$this random(int|null $number = null)
     * @method Cliente|null sole($key = null, $operator = null, $value = null)
     * @method Cliente|null get($key, $default = null)
     * @method Cliente|null first(callable $callback = null, $default = null)
     * @method Cliente|null firstWhere(string $key, $operator = null, $value = null)
     * @method Cliente|null find($key, $default = null)
     * @method Cliente[] all()
     */
    class _IH_Cliente_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Cliente[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Cliente_QB whereId($value)
     * @method _IH_Cliente_QB whereUserId($value)
     * @method _IH_Cliente_QB whereNombre($value)
     * @method _IH_Cliente_QB whereIcelular($value)
     * @method _IH_Cliente_QB whereCelular($value)
     * @method _IH_Cliente_QB whereTipo($value)
     * @method _IH_Cliente_QB whereProvincia($value)
     * @method _IH_Cliente_QB whereDistrito($value)
     * @method _IH_Cliente_QB whereDireccion($value)
     * @method _IH_Cliente_QB whereReferencia($value)
     * @method _IH_Cliente_QB whereDni($value)
     * @method _IH_Cliente_QB whereSaldo($value)
     * @method _IH_Cliente_QB whereDeuda($value)
     * @method _IH_Cliente_QB wherePidio($value)
     * @method _IH_Cliente_QB whereEstado($value)
     * @method _IH_Cliente_QB whereCreatedAt($value)
     * @method _IH_Cliente_QB whereUpdatedAt($value)
     * @method _IH_Cliente_QB whereCreaTemporal($value)
     * @method _IH_Cliente_QB whereActivadoTiempo($value)
     * @method _IH_Cliente_QB whereActivadoPedido($value)
     * @method _IH_Cliente_QB whereTemporalUpdate($value)
     * @method _IH_Cliente_QB whereSituacion($value)
     * @method _IH_Cliente_QB whereMotivoAnulacion($value)
     * @method _IH_Cliente_QB whereResponsableAnulacion($value)
     * @method _IH_Cliente_QB whereUserAnulacionId($value)
     * @method _IH_Cliente_QB whereFechaAnulacion($value)
     * @method _IH_Cliente_QB wherePathAdjuntoAnular($value)
     * @method _IH_Cliente_QB wherePathAdjuntoAnularDisk($value)
     * @method _IH_Cliente_QB whereAgenda($value)
     * @method _IH_Cliente_QB whereUserIdentificador($value)
     * @method _IH_Cliente_QB whereUserClavepedido($value)
     * @method _IH_Cliente_QB whereFechaUltimopedido($value)
     * @method _IH_Cliente_QB whereCodigoUltimopedido($value)
     * @method _IH_Cliente_QB wherePagoUltimopedido($value)
     * @method _IH_Cliente_QB wherePagadoUltimopedido($value)
     * @method _IH_Cliente_QB whereFsbPorcentaje($value)
     * @method _IH_Cliente_QB whereFcbPorcentaje($value)
     * @method _IH_Cliente_QB whereEsbPorcentaje($value)
     * @method _IH_Cliente_QB whereEcbPorcentaje($value)
     * @method Cliente baseSole(array|string $columns = ['*'])
     * @method Cliente create(array $attributes = [])
     * @method _IH_Cliente_C|Cliente[] cursor()
     * @method Cliente|null|_IH_Cliente_C|Cliente[] find($id, array $columns = ['*'])
     * @method _IH_Cliente_C|Cliente[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Cliente|_IH_Cliente_C|Cliente[] findOrFail($id, array $columns = ['*'])
     * @method Cliente|_IH_Cliente_C|Cliente[] findOrNew($id, array $columns = ['*'])
     * @method Cliente first(array|string $columns = ['*'])
     * @method Cliente firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Cliente firstOrCreate(array $attributes = [], array $values = [])
     * @method Cliente firstOrFail(array $columns = ['*'])
     * @method Cliente firstOrNew(array $attributes = [], array $values = [])
     * @method Cliente firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Cliente forceCreate(array $attributes)
     * @method _IH_Cliente_C|Cliente[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Cliente_C|Cliente[] get(array|string $columns = ['*'])
     * @method Cliente getModel()
     * @method Cliente[] getModels(array|string $columns = ['*'])
     * @method _IH_Cliente_C|Cliente[] hydrate(array $items)
     * @method Cliente make(array $attributes = [])
     * @method Cliente newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Cliente[]|_IH_Cliente_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Cliente[]|_IH_Cliente_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Cliente sole(array|string $columns = ['*'])
     * @method Cliente updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Cliente_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Cliente_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Cliente_QB extends _BaseBuilder {}
    
    /**
     * @method Correction|null getOrPut($key, $value)
     * @method Correction|$this shift(int $count = 1)
     * @method Correction|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Correction|$this pop(int $count = 1)
     * @method Correction|null pull($key, $default = null)
     * @method Correction|null last(callable $callback = null, $default = null)
     * @method Correction|$this random(int|null $number = null)
     * @method Correction|null sole($key = null, $operator = null, $value = null)
     * @method Correction|null get($key, $default = null)
     * @method Correction|null first(callable $callback = null, $default = null)
     * @method Correction|null firstWhere(string $key, $operator = null, $value = null)
     * @method Correction|null find($key, $default = null)
     * @method Correction[] all()
     */
    class _IH_Correction_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Correction[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Correction_QB whereCondicionEnvio($value)
     * @method _IH_Correction_QB whereCondicionEnvioCode($value)
     * @method Correction baseSole(array|string $columns = ['*'])
     * @method Correction create(array $attributes = [])
     * @method _IH_Correction_C|Correction[] cursor()
     * @method Correction|null|_IH_Correction_C|Correction[] find($id, array $columns = ['*'])
     * @method _IH_Correction_C|Correction[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Correction|_IH_Correction_C|Correction[] findOrFail($id, array $columns = ['*'])
     * @method Correction|_IH_Correction_C|Correction[] findOrNew($id, array $columns = ['*'])
     * @method Correction first(array|string $columns = ['*'])
     * @method Correction firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Correction firstOrCreate(array $attributes = [], array $values = [])
     * @method Correction firstOrFail(array $columns = ['*'])
     * @method Correction firstOrNew(array $attributes = [], array $values = [])
     * @method Correction firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Correction forceCreate(array $attributes)
     * @method _IH_Correction_C|Correction[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Correction_C|Correction[] get(array|string $columns = ['*'])
     * @method Correction getModel()
     * @method Correction[] getModels(array|string $columns = ['*'])
     * @method _IH_Correction_C|Correction[] hydrate(array $items)
     * @method Correction make(array $attributes = [])
     * @method Correction newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Correction[]|_IH_Correction_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Correction[]|_IH_Correction_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Correction sole(array|string $columns = ['*'])
     * @method Correction updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Correction_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Correction_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Correction_QB extends _BaseBuilder {}
    
    /**
     * @method CourierRegistro|null getOrPut($key, $value)
     * @method CourierRegistro|$this shift(int $count = 1)
     * @method CourierRegistro|null firstOrFail($key = null, $operator = null, $value = null)
     * @method CourierRegistro|$this pop(int $count = 1)
     * @method CourierRegistro|null pull($key, $default = null)
     * @method CourierRegistro|null last(callable $callback = null, $default = null)
     * @method CourierRegistro|$this random(int|null $number = null)
     * @method CourierRegistro|null sole($key = null, $operator = null, $value = null)
     * @method CourierRegistro|null get($key, $default = null)
     * @method CourierRegistro|null first(callable $callback = null, $default = null)
     * @method CourierRegistro|null firstWhere(string $key, $operator = null, $value = null)
     * @method CourierRegistro|null find($key, $default = null)
     * @method CourierRegistro[] all()
     */
    class _IH_CourierRegistro_C extends _BaseCollection {
        /**
         * @param int $size
         * @return CourierRegistro[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_CourierRegistro_QB whereId($value)
     * @method _IH_CourierRegistro_QB whereCourierRegistro($value)
     * @method _IH_CourierRegistro_QB whereAdjunto($value)
     * @method _IH_CourierRegistro_QB whereUserCreated($value)
     * @method _IH_CourierRegistro_QB whereCreatedAt($value)
     * @method _IH_CourierRegistro_QB whereUserUpdated($value)
     * @method _IH_CourierRegistro_QB whereUpdatedAt($value)
     * @method _IH_CourierRegistro_QB whereUserDeleted($value)
     * @method _IH_CourierRegistro_QB whereDeletedAt($value)
     * @method _IH_CourierRegistro_QB whereStatus($value)
     * @method _IH_CourierRegistro_QB whereRelacionado($value)
     * @method _IH_CourierRegistro_QB whereRelDirecciongrupo($value)
     * @method _IH_CourierRegistro_QB whereRelFechadp($value)
     * @method _IH_CourierRegistro_QB whereRelImporte($value)
     * @method _IH_CourierRegistro_QB whereRelTracking($value)
     * @method _IH_CourierRegistro_QB whereRelUserid($value)
     * @method _IH_CourierRegistro_QB whereRelFecharel($value)
     * @method CourierRegistro baseSole(array|string $columns = ['*'])
     * @method CourierRegistro create(array $attributes = [])
     * @method _IH_CourierRegistro_C|CourierRegistro[] cursor()
     * @method CourierRegistro|null|_IH_CourierRegistro_C|CourierRegistro[] find($id, array $columns = ['*'])
     * @method _IH_CourierRegistro_C|CourierRegistro[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method CourierRegistro|_IH_CourierRegistro_C|CourierRegistro[] findOrFail($id, array $columns = ['*'])
     * @method CourierRegistro|_IH_CourierRegistro_C|CourierRegistro[] findOrNew($id, array $columns = ['*'])
     * @method CourierRegistro first(array|string $columns = ['*'])
     * @method CourierRegistro firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method CourierRegistro firstOrCreate(array $attributes = [], array $values = [])
     * @method CourierRegistro firstOrFail(array $columns = ['*'])
     * @method CourierRegistro firstOrNew(array $attributes = [], array $values = [])
     * @method CourierRegistro firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method CourierRegistro forceCreate(array $attributes)
     * @method _IH_CourierRegistro_C|CourierRegistro[] fromQuery(string $query, array $bindings = [])
     * @method _IH_CourierRegistro_C|CourierRegistro[] get(array|string $columns = ['*'])
     * @method CourierRegistro getModel()
     * @method CourierRegistro[] getModels(array|string $columns = ['*'])
     * @method _IH_CourierRegistro_C|CourierRegistro[] hydrate(array $items)
     * @method CourierRegistro make(array $attributes = [])
     * @method CourierRegistro newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|CourierRegistro[]|_IH_CourierRegistro_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|CourierRegistro[]|_IH_CourierRegistro_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method CourierRegistro sole(array|string $columns = ['*'])
     * @method CourierRegistro updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_CourierRegistro_QB extends _BaseBuilder {}
    
    /**
     * @method CuentaBancaria|null getOrPut($key, $value)
     * @method CuentaBancaria|$this shift(int $count = 1)
     * @method CuentaBancaria|null firstOrFail($key = null, $operator = null, $value = null)
     * @method CuentaBancaria|$this pop(int $count = 1)
     * @method CuentaBancaria|null pull($key, $default = null)
     * @method CuentaBancaria|null last(callable $callback = null, $default = null)
     * @method CuentaBancaria|$this random(int|null $number = null)
     * @method CuentaBancaria|null sole($key = null, $operator = null, $value = null)
     * @method CuentaBancaria|null get($key, $default = null)
     * @method CuentaBancaria|null first(callable $callback = null, $default = null)
     * @method CuentaBancaria|null firstWhere(string $key, $operator = null, $value = null)
     * @method CuentaBancaria|null find($key, $default = null)
     * @method CuentaBancaria[] all()
     */
    class _IH_CuentaBancaria_C extends _BaseCollection {
        /**
         * @param int $size
         * @return CuentaBancaria[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method CuentaBancaria baseSole(array|string $columns = ['*'])
     * @method CuentaBancaria create(array $attributes = [])
     * @method _IH_CuentaBancaria_C|CuentaBancaria[] cursor()
     * @method CuentaBancaria|null|_IH_CuentaBancaria_C|CuentaBancaria[] find($id, array $columns = ['*'])
     * @method _IH_CuentaBancaria_C|CuentaBancaria[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method CuentaBancaria|_IH_CuentaBancaria_C|CuentaBancaria[] findOrFail($id, array $columns = ['*'])
     * @method CuentaBancaria|_IH_CuentaBancaria_C|CuentaBancaria[] findOrNew($id, array $columns = ['*'])
     * @method CuentaBancaria first(array|string $columns = ['*'])
     * @method CuentaBancaria firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method CuentaBancaria firstOrCreate(array $attributes = [], array $values = [])
     * @method CuentaBancaria firstOrFail(array $columns = ['*'])
     * @method CuentaBancaria firstOrNew(array $attributes = [], array $values = [])
     * @method CuentaBancaria firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method CuentaBancaria forceCreate(array $attributes)
     * @method _IH_CuentaBancaria_C|CuentaBancaria[] fromQuery(string $query, array $bindings = [])
     * @method _IH_CuentaBancaria_C|CuentaBancaria[] get(array|string $columns = ['*'])
     * @method CuentaBancaria getModel()
     * @method CuentaBancaria[] getModels(array|string $columns = ['*'])
     * @method _IH_CuentaBancaria_C|CuentaBancaria[] hydrate(array $items)
     * @method CuentaBancaria make(array $attributes = [])
     * @method CuentaBancaria newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|CuentaBancaria[]|_IH_CuentaBancaria_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|CuentaBancaria[]|_IH_CuentaBancaria_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method CuentaBancaria sole(array|string $columns = ['*'])
     * @method CuentaBancaria updateOrCreate(array $attributes, array $values = [])
     * @method _IH_CuentaBancaria_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_CuentaBancaria_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_CuentaBancaria_QB extends _BaseBuilder {}
    
    /**
     * @method Departamento|null getOrPut($key, $value)
     * @method Departamento|$this shift(int $count = 1)
     * @method Departamento|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Departamento|$this pop(int $count = 1)
     * @method Departamento|null pull($key, $default = null)
     * @method Departamento|null last(callable $callback = null, $default = null)
     * @method Departamento|$this random(int|null $number = null)
     * @method Departamento|null sole($key = null, $operator = null, $value = null)
     * @method Departamento|null get($key, $default = null)
     * @method Departamento|null first(callable $callback = null, $default = null)
     * @method Departamento|null firstWhere(string $key, $operator = null, $value = null)
     * @method Departamento|null find($key, $default = null)
     * @method Departamento[] all()
     */
    class _IH_Departamento_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Departamento[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Departamento_QB whereId($value)
     * @method _IH_Departamento_QB whereDepartamento($value)
     * @method _IH_Departamento_QB whereEstado($value)
     * @method _IH_Departamento_QB whereCreatedAt($value)
     * @method _IH_Departamento_QB whereUpdatedAt($value)
     * @method Departamento baseSole(array|string $columns = ['*'])
     * @method Departamento create(array $attributes = [])
     * @method _IH_Departamento_C|Departamento[] cursor()
     * @method Departamento|null|_IH_Departamento_C|Departamento[] find($id, array $columns = ['*'])
     * @method _IH_Departamento_C|Departamento[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Departamento|_IH_Departamento_C|Departamento[] findOrFail($id, array $columns = ['*'])
     * @method Departamento|_IH_Departamento_C|Departamento[] findOrNew($id, array $columns = ['*'])
     * @method Departamento first(array|string $columns = ['*'])
     * @method Departamento firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Departamento firstOrCreate(array $attributes = [], array $values = [])
     * @method Departamento firstOrFail(array $columns = ['*'])
     * @method Departamento firstOrNew(array $attributes = [], array $values = [])
     * @method Departamento firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Departamento forceCreate(array $attributes)
     * @method _IH_Departamento_C|Departamento[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Departamento_C|Departamento[] get(array|string $columns = ['*'])
     * @method Departamento getModel()
     * @method Departamento[] getModels(array|string $columns = ['*'])
     * @method _IH_Departamento_C|Departamento[] hydrate(array $items)
     * @method Departamento make(array $attributes = [])
     * @method Departamento newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Departamento[]|_IH_Departamento_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Departamento[]|_IH_Departamento_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Departamento sole(array|string $columns = ['*'])
     * @method Departamento updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Departamento_QB extends _BaseBuilder {}
    
    /**
     * @method DetalleContactos|null getOrPut($key, $value)
     * @method DetalleContactos|$this shift(int $count = 1)
     * @method DetalleContactos|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DetalleContactos|$this pop(int $count = 1)
     * @method DetalleContactos|null pull($key, $default = null)
     * @method DetalleContactos|null last(callable $callback = null, $default = null)
     * @method DetalleContactos|$this random(int|null $number = null)
     * @method DetalleContactos|null sole($key = null, $operator = null, $value = null)
     * @method DetalleContactos|null get($key, $default = null)
     * @method DetalleContactos|null first(callable $callback = null, $default = null)
     * @method DetalleContactos|null firstWhere(string $key, $operator = null, $value = null)
     * @method DetalleContactos|null find($key, $default = null)
     * @method DetalleContactos[] all()
     */
    class _IH_DetalleContactos_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DetalleContactos[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DetalleContactos_QB whereId($value)
     * @method _IH_DetalleContactos_QB whereCodigoAsesor($value)
     * @method _IH_DetalleContactos_QB whereNombreAsesor($value)
     * @method _IH_DetalleContactos_QB whereCelular($value)
     * @method _IH_DetalleContactos_QB whereCodigoCliente($value)
     * @method _IH_DetalleContactos_QB whereNombresCliente($value)
     * @method _IH_DetalleContactos_QB whereCreatedAt($value)
     * @method _IH_DetalleContactos_QB whereUpdatedAt($value)
     * @method _IH_DetalleContactos_QB whereNombreContacto($value)
     * @method _IH_DetalleContactos_QB whereGuardado($value)
     * @method _IH_DetalleContactos_QB whereConfirmado($value)
     * @method _IH_DetalleContactos_QB whereCodigoRegistra($value)
     * @method _IH_DetalleContactos_QB whereTipoInsert($value)
     * @method _IH_DetalleContactos_QB whereReconfirmado($value)
     * @method _IH_DetalleContactos_QB whereFoto($value)
     * @method DetalleContactos baseSole(array|string $columns = ['*'])
     * @method DetalleContactos create(array $attributes = [])
     * @method _IH_DetalleContactos_C|DetalleContactos[] cursor()
     * @method DetalleContactos|null|_IH_DetalleContactos_C|DetalleContactos[] find($id, array $columns = ['*'])
     * @method _IH_DetalleContactos_C|DetalleContactos[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DetalleContactos|_IH_DetalleContactos_C|DetalleContactos[] findOrFail($id, array $columns = ['*'])
     * @method DetalleContactos|_IH_DetalleContactos_C|DetalleContactos[] findOrNew($id, array $columns = ['*'])
     * @method DetalleContactos first(array|string $columns = ['*'])
     * @method DetalleContactos firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DetalleContactos firstOrCreate(array $attributes = [], array $values = [])
     * @method DetalleContactos firstOrFail(array $columns = ['*'])
     * @method DetalleContactos firstOrNew(array $attributes = [], array $values = [])
     * @method DetalleContactos firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DetalleContactos forceCreate(array $attributes)
     * @method _IH_DetalleContactos_C|DetalleContactos[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DetalleContactos_C|DetalleContactos[] get(array|string $columns = ['*'])
     * @method DetalleContactos getModel()
     * @method DetalleContactos[] getModels(array|string $columns = ['*'])
     * @method _IH_DetalleContactos_C|DetalleContactos[] hydrate(array $items)
     * @method DetalleContactos make(array $attributes = [])
     * @method DetalleContactos newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DetalleContactos[]|_IH_DetalleContactos_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DetalleContactos[]|_IH_DetalleContactos_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DetalleContactos sole(array|string $columns = ['*'])
     * @method DetalleContactos updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_DetalleContactos_QB extends _BaseBuilder {}
    
    /**
     * @method DetallePago|null getOrPut($key, $value)
     * @method DetallePago|$this shift(int $count = 1)
     * @method DetallePago|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DetallePago|$this pop(int $count = 1)
     * @method DetallePago|null pull($key, $default = null)
     * @method DetallePago|null last(callable $callback = null, $default = null)
     * @method DetallePago|$this random(int|null $number = null)
     * @method DetallePago|null sole($key = null, $operator = null, $value = null)
     * @method DetallePago|null get($key, $default = null)
     * @method DetallePago|null first(callable $callback = null, $default = null)
     * @method DetallePago|null firstWhere(string $key, $operator = null, $value = null)
     * @method DetallePago|null find($key, $default = null)
     * @method DetallePago[] all()
     */
    class _IH_DetallePago_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DetallePago[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DetallePago_QB whereId($value)
     * @method _IH_DetallePago_QB wherePagoId($value)
     * @method _IH_DetallePago_QB whereMonto($value)
     * @method _IH_DetallePago_QB whereBanco($value)
     * @method _IH_DetallePago_QB whereBancop($value)
     * @method _IH_DetallePago_QB whereObanco($value)
     * @method _IH_DetallePago_QB whereImagen($value)
     * @method _IH_DetallePago_QB whereFecha($value)
     * @method _IH_DetallePago_QB whereCuenta($value)
     * @method _IH_DetallePago_QB whereTitular($value)
     * @method _IH_DetallePago_QB whereFechaDeposito($value)
     * @method _IH_DetallePago_QB whereObservacion($value)
     * @method _IH_DetallePago_QB whereEstado($value)
     * @method _IH_DetallePago_QB whereCreatedAt($value)
     * @method _IH_DetallePago_QB whereUpdatedAt($value)
     * @method _IH_DetallePago_QB whereNota($value)
     * @method _IH_DetallePago_QB whereUserReg($value)
     * @method DetallePago baseSole(array|string $columns = ['*'])
     * @method DetallePago create(array $attributes = [])
     * @method _IH_DetallePago_C|DetallePago[] cursor()
     * @method DetallePago|null|_IH_DetallePago_C|DetallePago[] find($id, array $columns = ['*'])
     * @method _IH_DetallePago_C|DetallePago[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DetallePago|_IH_DetallePago_C|DetallePago[] findOrFail($id, array $columns = ['*'])
     * @method DetallePago|_IH_DetallePago_C|DetallePago[] findOrNew($id, array $columns = ['*'])
     * @method DetallePago first(array|string $columns = ['*'])
     * @method DetallePago firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DetallePago firstOrCreate(array $attributes = [], array $values = [])
     * @method DetallePago firstOrFail(array $columns = ['*'])
     * @method DetallePago firstOrNew(array $attributes = [], array $values = [])
     * @method DetallePago firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DetallePago forceCreate(array $attributes)
     * @method _IH_DetallePago_C|DetallePago[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DetallePago_C|DetallePago[] get(array|string $columns = ['*'])
     * @method DetallePago getModel()
     * @method DetallePago[] getModels(array|string $columns = ['*'])
     * @method _IH_DetallePago_C|DetallePago[] hydrate(array $items)
     * @method DetallePago make(array $attributes = [])
     * @method DetallePago newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DetallePago[]|_IH_DetallePago_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DetallePago[]|_IH_DetallePago_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DetallePago sole(array|string $columns = ['*'])
     * @method DetallePago updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_DetallePago_QB extends _BaseBuilder {}
    
    /**
     * @method DetallePedido|null getOrPut($key, $value)
     * @method DetallePedido|$this shift(int $count = 1)
     * @method DetallePedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DetallePedido|$this pop(int $count = 1)
     * @method DetallePedido|null pull($key, $default = null)
     * @method DetallePedido|null last(callable $callback = null, $default = null)
     * @method DetallePedido|$this random(int|null $number = null)
     * @method DetallePedido|null sole($key = null, $operator = null, $value = null)
     * @method DetallePedido|null get($key, $default = null)
     * @method DetallePedido|null first(callable $callback = null, $default = null)
     * @method DetallePedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method DetallePedido|null find($key, $default = null)
     * @method DetallePedido[] all()
     */
    class _IH_DetallePedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DetallePedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DetallePedido_QB whereId($value)
     * @method _IH_DetallePedido_QB wherePedidoId($value)
     * @method _IH_DetallePedido_QB whereCodigo($value)
     * @method _IH_DetallePedido_QB whereNombreEmpresa($value)
     * @method _IH_DetallePedido_QB whereMes($value)
     * @method _IH_DetallePedido_QB whereAnio($value)
     * @method _IH_DetallePedido_QB whereRuc($value)
     * @method _IH_DetallePedido_QB whereCantidad($value)
     * @method _IH_DetallePedido_QB whereAdjunto($value)
     * @method _IH_DetallePedido_QB whereTipoBanca($value)
     * @method _IH_DetallePedido_QB wherePorcentaje($value)
     * @method _IH_DetallePedido_QB whereFt($value)
     * @method _IH_DetallePedido_QB whereCourier($value)
     * @method _IH_DetallePedido_QB whereTotal($value)
     * @method _IH_DetallePedido_QB whereSaldo($value)
     * @method _IH_DetallePedido_QB whereDescripcion($value)
     * @method _IH_DetallePedido_QB whereNota($value)
     * @method _IH_DetallePedido_QB whereEnvioDoc($value)
     * @method _IH_DetallePedido_QB whereFechaEnvioDoc($value)
     * @method _IH_DetallePedido_QB whereCantCompro($value)
     * @method _IH_DetallePedido_QB whereFechaEnvioDocFis($value)
     * @method _IH_DetallePedido_QB whereFoto1($value)
     * @method _IH_DetallePedido_QB whereFoto2($value)
     * @method _IH_DetallePedido_QB whereAtendidoPor($value)
     * @method _IH_DetallePedido_QB whereFechaRecepcion($value)
     * @method _IH_DetallePedido_QB whereEstado($value)
     * @method _IH_DetallePedido_QB whereCreatedAt($value)
     * @method _IH_DetallePedido_QB whereUpdatedAt($value)
     * @method _IH_DetallePedido_QB whereAtendidoPorId($value)
     * @method _IH_DetallePedido_QB whereSobreValida($value)
     * @method _IH_DetallePedido_QB whereUserReg($value)
     * @method DetallePedido baseSole(array|string $columns = ['*'])
     * @method DetallePedido create(array $attributes = [])
     * @method _IH_DetallePedido_C|DetallePedido[] cursor()
     * @method DetallePedido|null|_IH_DetallePedido_C|DetallePedido[] find($id, array $columns = ['*'])
     * @method _IH_DetallePedido_C|DetallePedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DetallePedido|_IH_DetallePedido_C|DetallePedido[] findOrFail($id, array $columns = ['*'])
     * @method DetallePedido|_IH_DetallePedido_C|DetallePedido[] findOrNew($id, array $columns = ['*'])
     * @method DetallePedido first(array|string $columns = ['*'])
     * @method DetallePedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DetallePedido firstOrCreate(array $attributes = [], array $values = [])
     * @method DetallePedido firstOrFail(array $columns = ['*'])
     * @method DetallePedido firstOrNew(array $attributes = [], array $values = [])
     * @method DetallePedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DetallePedido forceCreate(array $attributes)
     * @method _IH_DetallePedido_C|DetallePedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DetallePedido_C|DetallePedido[] get(array|string $columns = ['*'])
     * @method DetallePedido getModel()
     * @method DetallePedido[] getModels(array|string $columns = ['*'])
     * @method _IH_DetallePedido_C|DetallePedido[] hydrate(array $items)
     * @method DetallePedido make(array $attributes = [])
     * @method DetallePedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DetallePedido[]|_IH_DetallePedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DetallePedido[]|_IH_DetallePedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DetallePedido sole(array|string $columns = ['*'])
     * @method DetallePedido updateOrCreate(array $attributes, array $values = [])
     * @method _IH_DetallePedido_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_DetallePedido_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_DetallePedido_QB extends _BaseBuilder {}
    
    /**
     * @method Devolucion|null getOrPut($key, $value)
     * @method Devolucion|$this shift(int $count = 1)
     * @method Devolucion|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Devolucion|$this pop(int $count = 1)
     * @method Devolucion|null pull($key, $default = null)
     * @method Devolucion|null last(callable $callback = null, $default = null)
     * @method Devolucion|$this random(int|null $number = null)
     * @method Devolucion|null sole($key = null, $operator = null, $value = null)
     * @method Devolucion|null get($key, $default = null)
     * @method Devolucion|null first(callable $callback = null, $default = null)
     * @method Devolucion|null firstWhere(string $key, $operator = null, $value = null)
     * @method Devolucion|null find($key, $default = null)
     * @method Devolucion[] all()
     */
    class _IH_Devolucion_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Devolucion[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Devolucion_QB whereId($value)
     * @method _IH_Devolucion_QB wherePagoId($value)
     * @method _IH_Devolucion_QB whereClientId($value)
     * @method _IH_Devolucion_QB whereAsesorId($value)
     * @method _IH_Devolucion_QB whereBankDestino($value)
     * @method _IH_Devolucion_QB whereBankNumber($value)
     * @method _IH_Devolucion_QB whereNumOperacion($value)
     * @method _IH_Devolucion_QB whereBankTitular($value)
     * @method _IH_Devolucion_QB whereAmount($value)
     * @method _IH_Devolucion_QB whereStatus($value)
     * @method _IH_Devolucion_QB whereVoucherDisk($value)
     * @method _IH_Devolucion_QB whereVoucherPath($value)
     * @method _IH_Devolucion_QB whereReturnedAt($value)
     * @method _IH_Devolucion_QB whereCreatedAt($value)
     * @method _IH_Devolucion_QB whereUpdatedAt($value)
     * @method Devolucion baseSole(array|string $columns = ['*'])
     * @method Devolucion create(array $attributes = [])
     * @method _IH_Devolucion_C|Devolucion[] cursor()
     * @method Devolucion|null|_IH_Devolucion_C|Devolucion[] find($id, array $columns = ['*'])
     * @method _IH_Devolucion_C|Devolucion[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Devolucion|_IH_Devolucion_C|Devolucion[] findOrFail($id, array $columns = ['*'])
     * @method Devolucion|_IH_Devolucion_C|Devolucion[] findOrNew($id, array $columns = ['*'])
     * @method Devolucion first(array|string $columns = ['*'])
     * @method Devolucion firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Devolucion firstOrCreate(array $attributes = [], array $values = [])
     * @method Devolucion firstOrFail(array $columns = ['*'])
     * @method Devolucion firstOrNew(array $attributes = [], array $values = [])
     * @method Devolucion firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Devolucion forceCreate(array $attributes)
     * @method _IH_Devolucion_C|Devolucion[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Devolucion_C|Devolucion[] get(array|string $columns = ['*'])
     * @method Devolucion getModel()
     * @method Devolucion[] getModels(array|string $columns = ['*'])
     * @method _IH_Devolucion_C|Devolucion[] hydrate(array $items)
     * @method Devolucion make(array $attributes = [])
     * @method Devolucion newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Devolucion[]|_IH_Devolucion_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Devolucion[]|_IH_Devolucion_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Devolucion sole(array|string $columns = ['*'])
     * @method Devolucion updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Devolucion_QB devueltos()
     * @method _IH_Devolucion_QB noAtendidos()
     */
    class _IH_Devolucion_QB extends _BaseBuilder {}
    
    /**
     * @method DireccionEnvio|null getOrPut($key, $value)
     * @method DireccionEnvio|$this shift(int $count = 1)
     * @method DireccionEnvio|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DireccionEnvio|$this pop(int $count = 1)
     * @method DireccionEnvio|null pull($key, $default = null)
     * @method DireccionEnvio|null last(callable $callback = null, $default = null)
     * @method DireccionEnvio|$this random(int|null $number = null)
     * @method DireccionEnvio|null sole($key = null, $operator = null, $value = null)
     * @method DireccionEnvio|null get($key, $default = null)
     * @method DireccionEnvio|null first(callable $callback = null, $default = null)
     * @method DireccionEnvio|null firstWhere(string $key, $operator = null, $value = null)
     * @method DireccionEnvio|null find($key, $default = null)
     * @method DireccionEnvio[] all()
     */
    class _IH_DireccionEnvio_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DireccionEnvio[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DireccionEnvio_QB whereId($value)
     * @method _IH_DireccionEnvio_QB whereClienteId($value)
     * @method _IH_DireccionEnvio_QB whereUserId($value)
     * @method _IH_DireccionEnvio_QB whereDepartamento($value)
     * @method _IH_DireccionEnvio_QB whereProvincia($value)
     * @method _IH_DireccionEnvio_QB whereDistrito($value)
     * @method _IH_DireccionEnvio_QB whereDireccion($value)
     * @method _IH_DireccionEnvio_QB whereReferencia($value)
     * @method _IH_DireccionEnvio_QB whereNombre($value)
     * @method _IH_DireccionEnvio_QB whereCelular($value)
     * @method _IH_DireccionEnvio_QB whereDirecciongrupo($value)
     * @method _IH_DireccionEnvio_QB whereCantidad($value)
     * @method _IH_DireccionEnvio_QB whereObservacion($value)
     * @method _IH_DireccionEnvio_QB whereEstado($value)
     * @method _IH_DireccionEnvio_QB whereSalvado($value)
     * @method _IH_DireccionEnvio_QB whereCreatedAt($value)
     * @method _IH_DireccionEnvio_QB whereUpdatedAt($value)
     * @method DireccionEnvio baseSole(array|string $columns = ['*'])
     * @method DireccionEnvio create(array $attributes = [])
     * @method _IH_DireccionEnvio_C|DireccionEnvio[] cursor()
     * @method DireccionEnvio|null|_IH_DireccionEnvio_C|DireccionEnvio[] find($id, array $columns = ['*'])
     * @method _IH_DireccionEnvio_C|DireccionEnvio[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DireccionEnvio|_IH_DireccionEnvio_C|DireccionEnvio[] findOrFail($id, array $columns = ['*'])
     * @method DireccionEnvio|_IH_DireccionEnvio_C|DireccionEnvio[] findOrNew($id, array $columns = ['*'])
     * @method DireccionEnvio first(array|string $columns = ['*'])
     * @method DireccionEnvio firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DireccionEnvio firstOrCreate(array $attributes = [], array $values = [])
     * @method DireccionEnvio firstOrFail(array $columns = ['*'])
     * @method DireccionEnvio firstOrNew(array $attributes = [], array $values = [])
     * @method DireccionEnvio firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DireccionEnvio forceCreate(array $attributes)
     * @method _IH_DireccionEnvio_C|DireccionEnvio[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DireccionEnvio_C|DireccionEnvio[] get(array|string $columns = ['*'])
     * @method DireccionEnvio getModel()
     * @method DireccionEnvio[] getModels(array|string $columns = ['*'])
     * @method _IH_DireccionEnvio_C|DireccionEnvio[] hydrate(array $items)
     * @method DireccionEnvio make(array $attributes = [])
     * @method DireccionEnvio newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DireccionEnvio[]|_IH_DireccionEnvio_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DireccionEnvio[]|_IH_DireccionEnvio_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DireccionEnvio sole(array|string $columns = ['*'])
     * @method DireccionEnvio updateOrCreate(array $attributes, array $values = [])
     * @method _IH_DireccionEnvio_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_DireccionEnvio_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_DireccionEnvio_QB extends _BaseBuilder {}
    
    /**
     * @method DireccionGrupo|null getOrPut($key, $value)
     * @method DireccionGrupo|$this shift(int $count = 1)
     * @method DireccionGrupo|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DireccionGrupo|$this pop(int $count = 1)
     * @method DireccionGrupo|null pull($key, $default = null)
     * @method DireccionGrupo|null last(callable $callback = null, $default = null)
     * @method DireccionGrupo|$this random(int|null $number = null)
     * @method DireccionGrupo|null sole($key = null, $operator = null, $value = null)
     * @method DireccionGrupo|null get($key, $default = null)
     * @method DireccionGrupo|null first(callable $callback = null, $default = null)
     * @method DireccionGrupo|null firstWhere(string $key, $operator = null, $value = null)
     * @method DireccionGrupo|null find($key, $default = null)
     * @method DireccionGrupo[] all()
     */
    class _IH_DireccionGrupo_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DireccionGrupo[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DireccionGrupo_QB whereId($value)
     * @method _IH_DireccionGrupo_QB whereCorrelativo($value)
     * @method _IH_DireccionGrupo_QB whereDestino($value)
     * @method _IH_DireccionGrupo_QB whereDistribucion($value)
     * @method _IH_DireccionGrupo_QB whereCondicionEnvioCode($value)
     * @method _IH_DireccionGrupo_QB whereCondicionEnvio($value)
     * @method _IH_DireccionGrupo_QB whereSubcondicionEnvio($value)
     * @method _IH_DireccionGrupo_QB whereCondicionSobre($value)
     * @method _IH_DireccionGrupo_QB whereFoto1($value)
     * @method _IH_DireccionGrupo_QB whereFoto2($value)
     * @method _IH_DireccionGrupo_QB whereFoto3($value)
     * @method _IH_DireccionGrupo_QB whereFechaRecepcion($value)
     * @method _IH_DireccionGrupo_QB whereAtendidoPor($value)
     * @method _IH_DireccionGrupo_QB whereAtendidoPorId($value)
     * @method _IH_DireccionGrupo_QB whereNombreCliente($value)
     * @method _IH_DireccionGrupo_QB whereCelularCliente($value)
     * @method _IH_DireccionGrupo_QB whereIcelularCliente($value)
     * @method _IH_DireccionGrupo_QB whereEstado($value)
     * @method _IH_DireccionGrupo_QB whereMotorizadoId($value)
     * @method _IH_DireccionGrupo_QB whereCreatedAt($value)
     * @method _IH_DireccionGrupo_QB whereUpdatedAt($value)
     * @method _IH_DireccionGrupo_QB whereClienteId($value)
     * @method _IH_DireccionGrupo_QB whereUserId($value)
     * @method _IH_DireccionGrupo_QB whereCodigos($value)
     * @method _IH_DireccionGrupo_QB whereProducto($value)
     * @method _IH_DireccionGrupo_QB whereIdentificador($value)
     * @method _IH_DireccionGrupo_QB whereCelular($value)
     * @method _IH_DireccionGrupo_QB whereNombre($value)
     * @method _IH_DireccionGrupo_QB whereFecha($value)
     * @method _IH_DireccionGrupo_QB whereCantidad($value)
     * @method _IH_DireccionGrupo_QB whereImporte($value)
     * @method _IH_DireccionGrupo_QB whereDireccion($value)
     * @method _IH_DireccionGrupo_QB whereReferencia($value)
     * @method _IH_DireccionGrupo_QB whereObservacion($value)
     * @method _IH_DireccionGrupo_QB whereDistrito($value)
     * @method _IH_DireccionGrupo_QB whereDestino2($value)
     * @method _IH_DireccionGrupo_QB wherePedidoId($value)
     * @method _IH_DireccionGrupo_QB whereFechaSalida($value)
     * @method _IH_DireccionGrupo_QB whereMotorizadoStatus($value)
     * @method _IH_DireccionGrupo_QB whereMotorizadoSustentoText($value)
     * @method _IH_DireccionGrupo_QB whereMotorizadoSustentoFoto($value)
     * @method _IH_DireccionGrupo_QB whereCodigosConfirmados($value)
     * @method _IH_DireccionGrupo_QB whereCambioDireccionSustento($value)
     * @method _IH_DireccionGrupo_QB whereCambioDireccionAt($value)
     * @method _IH_DireccionGrupo_QB whereEstadoConsinsobre($value)
     * @method _IH_DireccionGrupo_QB whereCondicionEnvioAt($value)
     * @method _IH_DireccionGrupo_QB whereGmlink($value)
     * @method _IH_DireccionGrupo_QB whereReprogramacionAt($value)
     * @method _IH_DireccionGrupo_QB whereReprogramacionSolicitudUserId($value)
     * @method _IH_DireccionGrupo_QB whereReprogramacionSolicitudAt($value)
     * @method _IH_DireccionGrupo_QB whereReprogramacionAcceptUserId($value)
     * @method _IH_DireccionGrupo_QB whereReprogramacionAcceptAt($value)
     * @method _IH_DireccionGrupo_QB whereFechaSalidaOldAt($value)
     * @method _IH_DireccionGrupo_QB whereCourierSyncAt($value)
     * @method _IH_DireccionGrupo_QB whereCourierFailedSyncAt($value)
     * @method _IH_DireccionGrupo_QB whereCourierSyncFinalized($value)
     * @method _IH_DireccionGrupo_QB whereCourierEstado($value)
     * @method _IH_DireccionGrupo_QB whereCourierData($value)
     * @method _IH_DireccionGrupo_QB whereRelacionado($value)
     * @method _IH_DireccionGrupo_QB whereAddScreenshotAt($value)
     * @method _IH_DireccionGrupo_QB whereUrgente($value)
     * @method DireccionGrupo baseSole(array|string $columns = ['*'])
     * @method DireccionGrupo create(array $attributes = [])
     * @method _IH_DireccionGrupo_C|DireccionGrupo[] cursor()
     * @method DireccionGrupo|null|_IH_DireccionGrupo_C|DireccionGrupo[] find($id, array $columns = ['*'])
     * @method _IH_DireccionGrupo_C|DireccionGrupo[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DireccionGrupo|_IH_DireccionGrupo_C|DireccionGrupo[] findOrFail($id, array $columns = ['*'])
     * @method DireccionGrupo|_IH_DireccionGrupo_C|DireccionGrupo[] findOrNew($id, array $columns = ['*'])
     * @method DireccionGrupo first(array|string $columns = ['*'])
     * @method DireccionGrupo firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DireccionGrupo firstOrCreate(array $attributes = [], array $values = [])
     * @method DireccionGrupo firstOrFail(array $columns = ['*'])
     * @method DireccionGrupo firstOrNew(array $attributes = [], array $values = [])
     * @method DireccionGrupo firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DireccionGrupo forceCreate(array $attributes)
     * @method _IH_DireccionGrupo_C|DireccionGrupo[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DireccionGrupo_C|DireccionGrupo[] get(array|string $columns = ['*'])
     * @method DireccionGrupo getModel()
     * @method DireccionGrupo[] getModels(array|string $columns = ['*'])
     * @method _IH_DireccionGrupo_C|DireccionGrupo[] hydrate(array $items)
     * @method DireccionGrupo make(array $attributes = [])
     * @method DireccionGrupo newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DireccionGrupo[]|_IH_DireccionGrupo_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DireccionGrupo[]|_IH_DireccionGrupo_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DireccionGrupo sole(array|string $columns = ['*'])
     * @method DireccionGrupo updateOrCreate(array $attributes, array $values = [])
     * @method _IH_DireccionGrupo_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_DireccionGrupo_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_DireccionGrupo_QB contestoNoObservado()
     * @method _IH_DireccionGrupo_QB inOlva()
     * @method _IH_DireccionGrupo_QB inOlvaAll()
     * @method _IH_DireccionGrupo_QB inOlvaFinalizado()
     * @method _IH_DireccionGrupo_QB inOlvaPending()
     * @method _IH_DireccionGrupo_QB noContesto()
     * @method _IH_DireccionGrupo_QB observado()
     * @method _IH_DireccionGrupo_QB reprogramados()
     */
    class _IH_DireccionGrupo_QB extends _BaseBuilder {}
    
    /**
     * @method DireccionPedido|null getOrPut($key, $value)
     * @method DireccionPedido|$this shift(int $count = 1)
     * @method DireccionPedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DireccionPedido|$this pop(int $count = 1)
     * @method DireccionPedido|null pull($key, $default = null)
     * @method DireccionPedido|null last(callable $callback = null, $default = null)
     * @method DireccionPedido|$this random(int|null $number = null)
     * @method DireccionPedido|null sole($key = null, $operator = null, $value = null)
     * @method DireccionPedido|null get($key, $default = null)
     * @method DireccionPedido|null first(callable $callback = null, $default = null)
     * @method DireccionPedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method DireccionPedido|null find($key, $default = null)
     * @method DireccionPedido[] all()
     */
    class _IH_DireccionPedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DireccionPedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DireccionPedido_QB whereId($value)
     * @method _IH_DireccionPedido_QB whereDireccionId($value)
     * @method _IH_DireccionPedido_QB wherePedidoId($value)
     * @method _IH_DireccionPedido_QB whereCodigoPedido($value)
     * @method _IH_DireccionPedido_QB whereDirecciongrupo($value)
     * @method _IH_DireccionPedido_QB whereEmpresa($value)
     * @method _IH_DireccionPedido_QB whereEstado($value)
     * @method _IH_DireccionPedido_QB whereCreatedAt($value)
     * @method _IH_DireccionPedido_QB whereUpdatedAt($value)
     * @method DireccionPedido baseSole(array|string $columns = ['*'])
     * @method DireccionPedido create(array $attributes = [])
     * @method _IH_DireccionPedido_C|DireccionPedido[] cursor()
     * @method DireccionPedido|null|_IH_DireccionPedido_C|DireccionPedido[] find($id, array $columns = ['*'])
     * @method _IH_DireccionPedido_C|DireccionPedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method DireccionPedido|_IH_DireccionPedido_C|DireccionPedido[] findOrFail($id, array $columns = ['*'])
     * @method DireccionPedido|_IH_DireccionPedido_C|DireccionPedido[] findOrNew($id, array $columns = ['*'])
     * @method DireccionPedido first(array|string $columns = ['*'])
     * @method DireccionPedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method DireccionPedido firstOrCreate(array $attributes = [], array $values = [])
     * @method DireccionPedido firstOrFail(array $columns = ['*'])
     * @method DireccionPedido firstOrNew(array $attributes = [], array $values = [])
     * @method DireccionPedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DireccionPedido forceCreate(array $attributes)
     * @method _IH_DireccionPedido_C|DireccionPedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DireccionPedido_C|DireccionPedido[] get(array|string $columns = ['*'])
     * @method DireccionPedido getModel()
     * @method DireccionPedido[] getModels(array|string $columns = ['*'])
     * @method _IH_DireccionPedido_C|DireccionPedido[] hydrate(array $items)
     * @method DireccionPedido make(array $attributes = [])
     * @method DireccionPedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DireccionPedido[]|_IH_DireccionPedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|DireccionPedido[]|_IH_DireccionPedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DireccionPedido sole(array|string $columns = ['*'])
     * @method DireccionPedido updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_DireccionPedido_QB extends _BaseBuilder {}
    
    /**
     * @method Directions|null getOrPut($key, $value)
     * @method Directions|$this shift(int $count = 1)
     * @method Directions|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Directions|$this pop(int $count = 1)
     * @method Directions|null pull($key, $default = null)
     * @method Directions|null last(callable $callback = null, $default = null)
     * @method Directions|$this random(int|null $number = null)
     * @method Directions|null sole($key = null, $operator = null, $value = null)
     * @method Directions|null get($key, $default = null)
     * @method Directions|null first(callable $callback = null, $default = null)
     * @method Directions|null firstWhere(string $key, $operator = null, $value = null)
     * @method Directions|null find($key, $default = null)
     * @method Directions[] all()
     */
    class _IH_Directions_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Directions[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Directions_QB whereId($value)
     * @method _IH_Directions_QB whereUserId($value)
     * @method _IH_Directions_QB whereRol($value)
     * @method _IH_Directions_QB whereDistrito($value)
     * @method _IH_Directions_QB whereDireccionRecojo($value)
     * @method _IH_Directions_QB whereNumeroRecojo($value)
     * @method _IH_Directions_QB whereCreatedAt($value)
     * @method _IH_Directions_QB whereUpdatedAt($value)
     * @method _IH_Directions_QB whereReferencia($value)
     * @method _IH_Directions_QB whereDestino($value)
     * @method _IH_Directions_QB whereCliente($value)
     * @method Directions baseSole(array|string $columns = ['*'])
     * @method Directions create(array $attributes = [])
     * @method _IH_Directions_C|Directions[] cursor()
     * @method Directions|null|_IH_Directions_C|Directions[] find($id, array $columns = ['*'])
     * @method _IH_Directions_C|Directions[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Directions|_IH_Directions_C|Directions[] findOrFail($id, array $columns = ['*'])
     * @method Directions|_IH_Directions_C|Directions[] findOrNew($id, array $columns = ['*'])
     * @method Directions first(array|string $columns = ['*'])
     * @method Directions firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Directions firstOrCreate(array $attributes = [], array $values = [])
     * @method Directions firstOrFail(array $columns = ['*'])
     * @method Directions firstOrNew(array $attributes = [], array $values = [])
     * @method Directions firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Directions forceCreate(array $attributes)
     * @method _IH_Directions_C|Directions[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Directions_C|Directions[] get(array|string $columns = ['*'])
     * @method Directions getModel()
     * @method Directions[] getModels(array|string $columns = ['*'])
     * @method _IH_Directions_C|Directions[] hydrate(array $items)
     * @method Directions make(array $attributes = [])
     * @method Directions newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Directions[]|_IH_Directions_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Directions[]|_IH_Directions_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Directions sole(array|string $columns = ['*'])
     * @method Directions updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Directions_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Directions_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Directions_QB extends _BaseBuilder {}
    
    /**
     * @method Distrito|null getOrPut($key, $value)
     * @method Distrito|$this shift(int $count = 1)
     * @method Distrito|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Distrito|$this pop(int $count = 1)
     * @method Distrito|null pull($key, $default = null)
     * @method Distrito|null last(callable $callback = null, $default = null)
     * @method Distrito|$this random(int|null $number = null)
     * @method Distrito|null sole($key = null, $operator = null, $value = null)
     * @method Distrito|null get($key, $default = null)
     * @method Distrito|null first(callable $callback = null, $default = null)
     * @method Distrito|null firstWhere(string $key, $operator = null, $value = null)
     * @method Distrito|null find($key, $default = null)
     * @method Distrito[] all()
     */
    class _IH_Distrito_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Distrito[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Distrito_QB whereId($value)
     * @method _IH_Distrito_QB whereDistrito($value)
     * @method _IH_Distrito_QB whereProvincia($value)
     * @method _IH_Distrito_QB whereZona($value)
     * @method _IH_Distrito_QB whereSugerencia($value)
     * @method _IH_Distrito_QB whereEstado($value)
     * @method _IH_Distrito_QB whereCreatedAt($value)
     * @method _IH_Distrito_QB whereUpdatedAt($value)
     * @method Distrito baseSole(array|string $columns = ['*'])
     * @method Distrito create(array $attributes = [])
     * @method _IH_Distrito_C|Distrito[] cursor()
     * @method Distrito|null|_IH_Distrito_C|Distrito[] find($id, array $columns = ['*'])
     * @method _IH_Distrito_C|Distrito[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Distrito|_IH_Distrito_C|Distrito[] findOrFail($id, array $columns = ['*'])
     * @method Distrito|_IH_Distrito_C|Distrito[] findOrNew($id, array $columns = ['*'])
     * @method Distrito first(array|string $columns = ['*'])
     * @method Distrito firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Distrito firstOrCreate(array $attributes = [], array $values = [])
     * @method Distrito firstOrFail(array $columns = ['*'])
     * @method Distrito firstOrNew(array $attributes = [], array $values = [])
     * @method Distrito firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Distrito forceCreate(array $attributes)
     * @method _IH_Distrito_C|Distrito[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Distrito_C|Distrito[] get(array|string $columns = ['*'])
     * @method Distrito getModel()
     * @method Distrito[] getModels(array|string $columns = ['*'])
     * @method _IH_Distrito_C|Distrito[] hydrate(array $items)
     * @method Distrito make(array $attributes = [])
     * @method Distrito newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Distrito[]|_IH_Distrito_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Distrito[]|_IH_Distrito_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Distrito sole(array|string $columns = ['*'])
     * @method Distrito updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Distrito_QB extends _BaseBuilder {}
    
    /**
     * @method EntidadBancaria|null getOrPut($key, $value)
     * @method EntidadBancaria|$this shift(int $count = 1)
     * @method EntidadBancaria|null firstOrFail($key = null, $operator = null, $value = null)
     * @method EntidadBancaria|$this pop(int $count = 1)
     * @method EntidadBancaria|null pull($key, $default = null)
     * @method EntidadBancaria|null last(callable $callback = null, $default = null)
     * @method EntidadBancaria|$this random(int|null $number = null)
     * @method EntidadBancaria|null sole($key = null, $operator = null, $value = null)
     * @method EntidadBancaria|null get($key, $default = null)
     * @method EntidadBancaria|null first(callable $callback = null, $default = null)
     * @method EntidadBancaria|null firstWhere(string $key, $operator = null, $value = null)
     * @method EntidadBancaria|null find($key, $default = null)
     * @method EntidadBancaria[] all()
     */
    class _IH_EntidadBancaria_C extends _BaseCollection {
        /**
         * @param int $size
         * @return EntidadBancaria[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method EntidadBancaria baseSole(array|string $columns = ['*'])
     * @method EntidadBancaria create(array $attributes = [])
     * @method _IH_EntidadBancaria_C|EntidadBancaria[] cursor()
     * @method EntidadBancaria|null|_IH_EntidadBancaria_C|EntidadBancaria[] find($id, array $columns = ['*'])
     * @method _IH_EntidadBancaria_C|EntidadBancaria[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method EntidadBancaria|_IH_EntidadBancaria_C|EntidadBancaria[] findOrFail($id, array $columns = ['*'])
     * @method EntidadBancaria|_IH_EntidadBancaria_C|EntidadBancaria[] findOrNew($id, array $columns = ['*'])
     * @method EntidadBancaria first(array|string $columns = ['*'])
     * @method EntidadBancaria firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method EntidadBancaria firstOrCreate(array $attributes = [], array $values = [])
     * @method EntidadBancaria firstOrFail(array $columns = ['*'])
     * @method EntidadBancaria firstOrNew(array $attributes = [], array $values = [])
     * @method EntidadBancaria firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method EntidadBancaria forceCreate(array $attributes)
     * @method _IH_EntidadBancaria_C|EntidadBancaria[] fromQuery(string $query, array $bindings = [])
     * @method _IH_EntidadBancaria_C|EntidadBancaria[] get(array|string $columns = ['*'])
     * @method EntidadBancaria getModel()
     * @method EntidadBancaria[] getModels(array|string $columns = ['*'])
     * @method _IH_EntidadBancaria_C|EntidadBancaria[] hydrate(array $items)
     * @method EntidadBancaria make(array $attributes = [])
     * @method EntidadBancaria newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|EntidadBancaria[]|_IH_EntidadBancaria_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|EntidadBancaria[]|_IH_EntidadBancaria_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method EntidadBancaria sole(array|string $columns = ['*'])
     * @method EntidadBancaria updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_EntidadBancaria_QB extends _BaseBuilder {}
    
    /**
     * @method Event|null getOrPut($key, $value)
     * @method Event|$this shift(int $count = 1)
     * @method Event|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Event|$this pop(int $count = 1)
     * @method Event|null pull($key, $default = null)
     * @method Event|null last(callable $callback = null, $default = null)
     * @method Event|$this random(int|null $number = null)
     * @method Event|null sole($key = null, $operator = null, $value = null)
     * @method Event|null get($key, $default = null)
     * @method Event|null first(callable $callback = null, $default = null)
     * @method Event|null firstWhere(string $key, $operator = null, $value = null)
     * @method Event|null find($key, $default = null)
     * @method Event[] all()
     */
    class _IH_Event_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Event[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Event_QB whereId($value)
     * @method _IH_Event_QB whereTitle($value)
     * @method _IH_Event_QB whereStart($value)
     * @method _IH_Event_QB whereEnd($value)
     * @method _IH_Event_QB whereCreatedAt($value)
     * @method _IH_Event_QB whereUpdatedAt($value)
     * @method _IH_Event_QB whereColor($value)
     * @method _IH_Event_QB whereDescription($value)
     * @method _IH_Event_QB whereColorevento($value)
     * @method _IH_Event_QB whereFondoevento($value)
     * @method _IH_Event_QB whereGrupo($value)
     * @method _IH_Event_QB whereTipo($value)
     * @method _IH_Event_QB whereFrecuencia($value)
     * @method _IH_Event_QB whereStatus($value)
     * @method _IH_Event_QB whereAttach($value)
     * @method _IH_Event_QB whereUnsigned($value)
     * @method Event baseSole(array|string $columns = ['*'])
     * @method Event create(array $attributes = [])
     * @method _IH_Event_C|Event[] cursor()
     * @method Event|null|_IH_Event_C|Event[] find($id, array $columns = ['*'])
     * @method _IH_Event_C|Event[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Event|_IH_Event_C|Event[] findOrFail($id, array $columns = ['*'])
     * @method Event|_IH_Event_C|Event[] findOrNew($id, array $columns = ['*'])
     * @method Event first(array|string $columns = ['*'])
     * @method Event firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Event firstOrCreate(array $attributes = [], array $values = [])
     * @method Event firstOrFail(array $columns = ['*'])
     * @method Event firstOrNew(array $attributes = [], array $values = [])
     * @method Event firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Event forceCreate(array $attributes)
     * @method _IH_Event_C|Event[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Event_C|Event[] get(array|string $columns = ['*'])
     * @method Event getModel()
     * @method Event[] getModels(array|string $columns = ['*'])
     * @method _IH_Event_C|Event[] hydrate(array $items)
     * @method Event make(array $attributes = [])
     * @method Event newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Event[]|_IH_Event_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Event[]|_IH_Event_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Event sole(array|string $columns = ['*'])
     * @method Event updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Event_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Event_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Event_QB extends _BaseBuilder {}
    
    /**
     * @method EventsUnsigned|null getOrPut($key, $value)
     * @method EventsUnsigned|$this shift(int $count = 1)
     * @method EventsUnsigned|null firstOrFail($key = null, $operator = null, $value = null)
     * @method EventsUnsigned|$this pop(int $count = 1)
     * @method EventsUnsigned|null pull($key, $default = null)
     * @method EventsUnsigned|null last(callable $callback = null, $default = null)
     * @method EventsUnsigned|$this random(int|null $number = null)
     * @method EventsUnsigned|null sole($key = null, $operator = null, $value = null)
     * @method EventsUnsigned|null get($key, $default = null)
     * @method EventsUnsigned|null first(callable $callback = null, $default = null)
     * @method EventsUnsigned|null firstWhere(string $key, $operator = null, $value = null)
     * @method EventsUnsigned|null find($key, $default = null)
     * @method EventsUnsigned[] all()
     */
    class _IH_EventsUnsigned_C extends _BaseCollection {
        /**
         * @param int $size
         * @return EventsUnsigned[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_EventsUnsigned_QB whereId($value)
     * @method _IH_EventsUnsigned_QB whereTitle($value)
     * @method _IH_EventsUnsigned_QB whereColor($value)
     * @method _IH_EventsUnsigned_QB whereCreatedAt($value)
     * @method _IH_EventsUnsigned_QB whereUpdatedAt($value)
     * @method _IH_EventsUnsigned_QB whereDescription($value)
     * @method _IH_EventsUnsigned_QB whereAttach($value)
     * @method EventsUnsigned baseSole(array|string $columns = ['*'])
     * @method EventsUnsigned create(array $attributes = [])
     * @method _IH_EventsUnsigned_C|EventsUnsigned[] cursor()
     * @method EventsUnsigned|null|_IH_EventsUnsigned_C|EventsUnsigned[] find($id, array $columns = ['*'])
     * @method _IH_EventsUnsigned_C|EventsUnsigned[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method EventsUnsigned|_IH_EventsUnsigned_C|EventsUnsigned[] findOrFail($id, array $columns = ['*'])
     * @method EventsUnsigned|_IH_EventsUnsigned_C|EventsUnsigned[] findOrNew($id, array $columns = ['*'])
     * @method EventsUnsigned first(array|string $columns = ['*'])
     * @method EventsUnsigned firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method EventsUnsigned firstOrCreate(array $attributes = [], array $values = [])
     * @method EventsUnsigned firstOrFail(array $columns = ['*'])
     * @method EventsUnsigned firstOrNew(array $attributes = [], array $values = [])
     * @method EventsUnsigned firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method EventsUnsigned forceCreate(array $attributes)
     * @method _IH_EventsUnsigned_C|EventsUnsigned[] fromQuery(string $query, array $bindings = [])
     * @method _IH_EventsUnsigned_C|EventsUnsigned[] get(array|string $columns = ['*'])
     * @method EventsUnsigned getModel()
     * @method EventsUnsigned[] getModels(array|string $columns = ['*'])
     * @method _IH_EventsUnsigned_C|EventsUnsigned[] hydrate(array $items)
     * @method EventsUnsigned make(array $attributes = [])
     * @method EventsUnsigned newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|EventsUnsigned[]|_IH_EventsUnsigned_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|EventsUnsigned[]|_IH_EventsUnsigned_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method EventsUnsigned sole(array|string $columns = ['*'])
     * @method EventsUnsigned updateOrCreate(array $attributes, array $values = [])
     * @method _IH_EventsUnsigned_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_EventsUnsigned_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_EventsUnsigned_QB extends _BaseBuilder {}
    
    /**
     * @method FileUploadAnulacion|null getOrPut($key, $value)
     * @method FileUploadAnulacion|$this shift(int $count = 1)
     * @method FileUploadAnulacion|null firstOrFail($key = null, $operator = null, $value = null)
     * @method FileUploadAnulacion|$this pop(int $count = 1)
     * @method FileUploadAnulacion|null pull($key, $default = null)
     * @method FileUploadAnulacion|null last(callable $callback = null, $default = null)
     * @method FileUploadAnulacion|$this random(int|null $number = null)
     * @method FileUploadAnulacion|null sole($key = null, $operator = null, $value = null)
     * @method FileUploadAnulacion|null get($key, $default = null)
     * @method FileUploadAnulacion|null first(callable $callback = null, $default = null)
     * @method FileUploadAnulacion|null firstWhere(string $key, $operator = null, $value = null)
     * @method FileUploadAnulacion|null find($key, $default = null)
     * @method FileUploadAnulacion[] all()
     */
    class _IH_FileUploadAnulacion_C extends _BaseCollection {
        /**
         * @param int $size
         * @return FileUploadAnulacion[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_FileUploadAnulacion_QB whereId($value)
     * @method _IH_FileUploadAnulacion_QB wherePedidoAnulacionId($value)
     * @method _IH_FileUploadAnulacion_QB whereFilename($value)
     * @method _IH_FileUploadAnulacion_QB whereFilepath($value)
     * @method _IH_FileUploadAnulacion_QB whereType($value)
     * @method _IH_FileUploadAnulacion_QB whereCreatedAt($value)
     * @method _IH_FileUploadAnulacion_QB whereUpdatedAt($value)
     * @method FileUploadAnulacion baseSole(array|string $columns = ['*'])
     * @method FileUploadAnulacion create(array $attributes = [])
     * @method _IH_FileUploadAnulacion_C|FileUploadAnulacion[] cursor()
     * @method FileUploadAnulacion|null|_IH_FileUploadAnulacion_C|FileUploadAnulacion[] find($id, array $columns = ['*'])
     * @method _IH_FileUploadAnulacion_C|FileUploadAnulacion[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method FileUploadAnulacion|_IH_FileUploadAnulacion_C|FileUploadAnulacion[] findOrFail($id, array $columns = ['*'])
     * @method FileUploadAnulacion|_IH_FileUploadAnulacion_C|FileUploadAnulacion[] findOrNew($id, array $columns = ['*'])
     * @method FileUploadAnulacion first(array|string $columns = ['*'])
     * @method FileUploadAnulacion firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method FileUploadAnulacion firstOrCreate(array $attributes = [], array $values = [])
     * @method FileUploadAnulacion firstOrFail(array $columns = ['*'])
     * @method FileUploadAnulacion firstOrNew(array $attributes = [], array $values = [])
     * @method FileUploadAnulacion firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method FileUploadAnulacion forceCreate(array $attributes)
     * @method _IH_FileUploadAnulacion_C|FileUploadAnulacion[] fromQuery(string $query, array $bindings = [])
     * @method _IH_FileUploadAnulacion_C|FileUploadAnulacion[] get(array|string $columns = ['*'])
     * @method FileUploadAnulacion getModel()
     * @method FileUploadAnulacion[] getModels(array|string $columns = ['*'])
     * @method _IH_FileUploadAnulacion_C|FileUploadAnulacion[] hydrate(array $items)
     * @method FileUploadAnulacion make(array $attributes = [])
     * @method FileUploadAnulacion newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|FileUploadAnulacion[]|_IH_FileUploadAnulacion_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|FileUploadAnulacion[]|_IH_FileUploadAnulacion_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method FileUploadAnulacion sole(array|string $columns = ['*'])
     * @method FileUploadAnulacion updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_FileUploadAnulacion_QB extends _BaseBuilder {}
    
    /**
     * @method GastoEnvio|null getOrPut($key, $value)
     * @method GastoEnvio|$this shift(int $count = 1)
     * @method GastoEnvio|null firstOrFail($key = null, $operator = null, $value = null)
     * @method GastoEnvio|$this pop(int $count = 1)
     * @method GastoEnvio|null pull($key, $default = null)
     * @method GastoEnvio|null last(callable $callback = null, $default = null)
     * @method GastoEnvio|$this random(int|null $number = null)
     * @method GastoEnvio|null sole($key = null, $operator = null, $value = null)
     * @method GastoEnvio|null get($key, $default = null)
     * @method GastoEnvio|null first(callable $callback = null, $default = null)
     * @method GastoEnvio|null firstWhere(string $key, $operator = null, $value = null)
     * @method GastoEnvio|null find($key, $default = null)
     * @method GastoEnvio[] all()
     */
    class _IH_GastoEnvio_C extends _BaseCollection {
        /**
         * @param int $size
         * @return GastoEnvio[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_GastoEnvio_QB whereId($value)
     * @method _IH_GastoEnvio_QB whereClienteId($value)
     * @method _IH_GastoEnvio_QB whereUserId($value)
     * @method _IH_GastoEnvio_QB whereTracking($value)
     * @method _IH_GastoEnvio_QB whereRegistro($value)
     * @method _IH_GastoEnvio_QB whereFoto($value)
     * @method _IH_GastoEnvio_QB whereCantidad($value)
     * @method _IH_GastoEnvio_QB whereImporte($value)
     * @method _IH_GastoEnvio_QB whereDirecciongrupo($value)
     * @method _IH_GastoEnvio_QB whereEstado($value)
     * @method _IH_GastoEnvio_QB whereSalvado($value)
     * @method _IH_GastoEnvio_QB whereCreatedAt($value)
     * @method _IH_GastoEnvio_QB whereUpdatedAt($value)
     * @method GastoEnvio baseSole(array|string $columns = ['*'])
     * @method GastoEnvio create(array $attributes = [])
     * @method _IH_GastoEnvio_C|GastoEnvio[] cursor()
     * @method GastoEnvio|null|_IH_GastoEnvio_C|GastoEnvio[] find($id, array $columns = ['*'])
     * @method _IH_GastoEnvio_C|GastoEnvio[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method GastoEnvio|_IH_GastoEnvio_C|GastoEnvio[] findOrFail($id, array $columns = ['*'])
     * @method GastoEnvio|_IH_GastoEnvio_C|GastoEnvio[] findOrNew($id, array $columns = ['*'])
     * @method GastoEnvio first(array|string $columns = ['*'])
     * @method GastoEnvio firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method GastoEnvio firstOrCreate(array $attributes = [], array $values = [])
     * @method GastoEnvio firstOrFail(array $columns = ['*'])
     * @method GastoEnvio firstOrNew(array $attributes = [], array $values = [])
     * @method GastoEnvio firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method GastoEnvio forceCreate(array $attributes)
     * @method _IH_GastoEnvio_C|GastoEnvio[] fromQuery(string $query, array $bindings = [])
     * @method _IH_GastoEnvio_C|GastoEnvio[] get(array|string $columns = ['*'])
     * @method GastoEnvio getModel()
     * @method GastoEnvio[] getModels(array|string $columns = ['*'])
     * @method _IH_GastoEnvio_C|GastoEnvio[] hydrate(array $items)
     * @method GastoEnvio make(array $attributes = [])
     * @method GastoEnvio newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|GastoEnvio[]|_IH_GastoEnvio_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|GastoEnvio[]|_IH_GastoEnvio_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method GastoEnvio sole(array|string $columns = ['*'])
     * @method GastoEnvio updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_GastoEnvio_QB extends _BaseBuilder {}
    
    /**
     * @method GastoPedido|null getOrPut($key, $value)
     * @method GastoPedido|$this shift(int $count = 1)
     * @method GastoPedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method GastoPedido|$this pop(int $count = 1)
     * @method GastoPedido|null pull($key, $default = null)
     * @method GastoPedido|null last(callable $callback = null, $default = null)
     * @method GastoPedido|$this random(int|null $number = null)
     * @method GastoPedido|null sole($key = null, $operator = null, $value = null)
     * @method GastoPedido|null get($key, $default = null)
     * @method GastoPedido|null first(callable $callback = null, $default = null)
     * @method GastoPedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method GastoPedido|null find($key, $default = null)
     * @method GastoPedido[] all()
     */
    class _IH_GastoPedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return GastoPedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_GastoPedido_QB whereId($value)
     * @method _IH_GastoPedido_QB whereGastoId($value)
     * @method _IH_GastoPedido_QB wherePedidoId($value)
     * @method _IH_GastoPedido_QB whereCodigoPedido($value)
     * @method _IH_GastoPedido_QB whereDirecciongrupo($value)
     * @method _IH_GastoPedido_QB whereEmpresa($value)
     * @method _IH_GastoPedido_QB whereEstado($value)
     * @method _IH_GastoPedido_QB whereCreatedAt($value)
     * @method _IH_GastoPedido_QB whereUpdatedAt($value)
     * @method GastoPedido baseSole(array|string $columns = ['*'])
     * @method GastoPedido create(array $attributes = [])
     * @method _IH_GastoPedido_C|GastoPedido[] cursor()
     * @method GastoPedido|null|_IH_GastoPedido_C|GastoPedido[] find($id, array $columns = ['*'])
     * @method _IH_GastoPedido_C|GastoPedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method GastoPedido|_IH_GastoPedido_C|GastoPedido[] findOrFail($id, array $columns = ['*'])
     * @method GastoPedido|_IH_GastoPedido_C|GastoPedido[] findOrNew($id, array $columns = ['*'])
     * @method GastoPedido first(array|string $columns = ['*'])
     * @method GastoPedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method GastoPedido firstOrCreate(array $attributes = [], array $values = [])
     * @method GastoPedido firstOrFail(array $columns = ['*'])
     * @method GastoPedido firstOrNew(array $attributes = [], array $values = [])
     * @method GastoPedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method GastoPedido forceCreate(array $attributes)
     * @method _IH_GastoPedido_C|GastoPedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_GastoPedido_C|GastoPedido[] get(array|string $columns = ['*'])
     * @method GastoPedido getModel()
     * @method GastoPedido[] getModels(array|string $columns = ['*'])
     * @method _IH_GastoPedido_C|GastoPedido[] hydrate(array $items)
     * @method GastoPedido make(array $attributes = [])
     * @method GastoPedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|GastoPedido[]|_IH_GastoPedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|GastoPedido[]|_IH_GastoPedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method GastoPedido sole(array|string $columns = ['*'])
     * @method GastoPedido updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_GastoPedido_QB extends _BaseBuilder {}
    
    /**
     * @method GrupoPedido|null getOrPut($key, $value)
     * @method GrupoPedido|$this shift(int $count = 1)
     * @method GrupoPedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method GrupoPedido|$this pop(int $count = 1)
     * @method GrupoPedido|null pull($key, $default = null)
     * @method GrupoPedido|null last(callable $callback = null, $default = null)
     * @method GrupoPedido|$this random(int|null $number = null)
     * @method GrupoPedido|null sole($key = null, $operator = null, $value = null)
     * @method GrupoPedido|null get($key, $default = null)
     * @method GrupoPedido|null first(callable $callback = null, $default = null)
     * @method GrupoPedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method GrupoPedido|null find($key, $default = null)
     * @method GrupoPedido[] all()
     */
    class _IH_GrupoPedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return GrupoPedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_GrupoPedido_QB whereId($value)
     * @method _IH_GrupoPedido_QB whereZona($value)
     * @method _IH_GrupoPedido_QB whereProvincia($value)
     * @method _IH_GrupoPedido_QB whereDistrito($value)
     * @method _IH_GrupoPedido_QB whereDireccion($value)
     * @method _IH_GrupoPedido_QB whereReferencia($value)
     * @method _IH_GrupoPedido_QB whereClienteRecibe($value)
     * @method _IH_GrupoPedido_QB whereTelefono($value)
     * @method _IH_GrupoPedido_QB whereCreatedAt($value)
     * @method _IH_GrupoPedido_QB whereUpdatedAt($value)
     * @method _IH_GrupoPedido_QB whereDeletedAt($value)
     * @method _IH_GrupoPedido_QB whereUrgente($value)
     * @method GrupoPedido baseSole(array|string $columns = ['*'])
     * @method GrupoPedido create(array $attributes = [])
     * @method _IH_GrupoPedido_C|GrupoPedido[] cursor()
     * @method GrupoPedido|null|_IH_GrupoPedido_C|GrupoPedido[] find($id, array $columns = ['*'])
     * @method _IH_GrupoPedido_C|GrupoPedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method GrupoPedido|_IH_GrupoPedido_C|GrupoPedido[] findOrFail($id, array $columns = ['*'])
     * @method GrupoPedido|_IH_GrupoPedido_C|GrupoPedido[] findOrNew($id, array $columns = ['*'])
     * @method GrupoPedido first(array|string $columns = ['*'])
     * @method GrupoPedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method GrupoPedido firstOrCreate(array $attributes = [], array $values = [])
     * @method GrupoPedido firstOrFail(array $columns = ['*'])
     * @method GrupoPedido firstOrNew(array $attributes = [], array $values = [])
     * @method GrupoPedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method GrupoPedido forceCreate(array $attributes)
     * @method _IH_GrupoPedido_C|GrupoPedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_GrupoPedido_C|GrupoPedido[] get(array|string $columns = ['*'])
     * @method GrupoPedido getModel()
     * @method GrupoPedido[] getModels(array|string $columns = ['*'])
     * @method _IH_GrupoPedido_C|GrupoPedido[] hydrate(array $items)
     * @method GrupoPedido make(array $attributes = [])
     * @method GrupoPedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|GrupoPedido[]|_IH_GrupoPedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|GrupoPedido[]|_IH_GrupoPedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method GrupoPedido sole(array|string $columns = ['*'])
     * @method GrupoPedido updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_GrupoPedido_QB extends _BaseBuilder {}
    
    /**
     * @method HistoriaPedidos|null getOrPut($key, $value)
     * @method HistoriaPedidos|$this shift(int $count = 1)
     * @method HistoriaPedidos|null firstOrFail($key = null, $operator = null, $value = null)
     * @method HistoriaPedidos|$this pop(int $count = 1)
     * @method HistoriaPedidos|null pull($key, $default = null)
     * @method HistoriaPedidos|null last(callable $callback = null, $default = null)
     * @method HistoriaPedidos|$this random(int|null $number = null)
     * @method HistoriaPedidos|null sole($key = null, $operator = null, $value = null)
     * @method HistoriaPedidos|null get($key, $default = null)
     * @method HistoriaPedidos|null first(callable $callback = null, $default = null)
     * @method HistoriaPedidos|null firstWhere(string $key, $operator = null, $value = null)
     * @method HistoriaPedidos|null find($key, $default = null)
     * @method HistoriaPedidos[] all()
     */
    class _IH_HistoriaPedidos_C extends _BaseCollection {
        /**
         * @param int $size
         * @return HistoriaPedidos[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_HistoriaPedidos_QB whereId($value)
     * @method _IH_HistoriaPedidos_QB wherePedidoId($value)
     * @method _IH_HistoriaPedidos_QB whereCorrelativo($value)
     * @method _IH_HistoriaPedidos_QB whereClienteId($value)
     * @method _IH_HistoriaPedidos_QB whereUserId($value)
     * @method _IH_HistoriaPedidos_QB whereIdentificador($value)
     * @method _IH_HistoriaPedidos_QB whereExidentificador($value)
     * @method _IH_HistoriaPedidos_QB whereIcelularAsesor($value)
     * @method _IH_HistoriaPedidos_QB whereCelularCliente($value)
     * @method _IH_HistoriaPedidos_QB whereIcelularCliente($value)
     * @method _IH_HistoriaPedidos_QB whereCreador($value)
     * @method _IH_HistoriaPedidos_QB wherePago($value)
     * @method _IH_HistoriaPedidos_QB wherePagado($value)
     * @method _IH_HistoriaPedidos_QB whereCondicionEnvio($value)
     * @method _IH_HistoriaPedidos_QB whereCondicionEnvioCode($value)
     * @method _IH_HistoriaPedidos_QB whereCondicionEnvioAt($value)
     * @method _IH_HistoriaPedidos_QB whereCodigo($value)
     * @method _IH_HistoriaPedidos_QB whereMotivo($value)
     * @method _IH_HistoriaPedidos_QB whereResponsable($value)
     * @method _IH_HistoriaPedidos_QB whereModificador($value)
     * @method _IH_HistoriaPedidos_QB whereEstado($value)
     * @method _IH_HistoriaPedidos_QB whereDaConfirmarDescarga($value)
     * @method _IH_HistoriaPedidos_QB whereEstadoSobre($value)
     * @method _IH_HistoriaPedidos_QB whereEstadoConsinsobre($value)
     * @method _IH_HistoriaPedidos_QB whereEnvDestino($value)
     * @method _IH_HistoriaPedidos_QB whereEnvDistrito($value)
     * @method _IH_HistoriaPedidos_QB whereEnvZona($value)
     * @method _IH_HistoriaPedidos_QB whereEnvZonaAsignada($value)
     * @method _IH_HistoriaPedidos_QB whereEnvNombreClienteRecibe($value)
     * @method _IH_HistoriaPedidos_QB whereEnvCelularClienteRecibe($value)
     * @method _IH_HistoriaPedidos_QB whereEnvCantidad($value)
     * @method _IH_HistoriaPedidos_QB whereEnvDireccion($value)
     * @method _IH_HistoriaPedidos_QB whereEnvTracking($value)
     * @method _IH_HistoriaPedidos_QB whereEnvReferencia($value)
     * @method _IH_HistoriaPedidos_QB whereEnvNumregistro($value)
     * @method _IH_HistoriaPedidos_QB whereEnvRotulo($value)
     * @method _IH_HistoriaPedidos_QB whereEnvObservacion($value)
     * @method _IH_HistoriaPedidos_QB whereEnvGmlink($value)
     * @method _IH_HistoriaPedidos_QB whereEnvImporte($value)
     * @method _IH_HistoriaPedidos_QB whereEstadoRuta($value)
     * @method _IH_HistoriaPedidos_QB whereDireccionGrupo($value)
     * @method _IH_HistoriaPedidos_QB whereEstadoCorreccion($value)
     * @method _IH_HistoriaPedidos_QB whereNombreEmpresa($value)
     * @method _IH_HistoriaPedidos_QB whereMes($value)
     * @method _IH_HistoriaPedidos_QB whereAnio($value)
     * @method _IH_HistoriaPedidos_QB whereRuc($value)
     * @method _IH_HistoriaPedidos_QB whereCantidad($value)
     * @method _IH_HistoriaPedidos_QB whereTipoBanca($value)
     * @method _IH_HistoriaPedidos_QB wherePorcentaje($value)
     * @method _IH_HistoriaPedidos_QB whereFt($value)
     * @method _IH_HistoriaPedidos_QB whereCourier($value)
     * @method _IH_HistoriaPedidos_QB whereTotal($value)
     * @method _IH_HistoriaPedidos_QB whereSaldo($value)
     * @method _IH_HistoriaPedidos_QB whereDescripcion($value)
     * @method _IH_HistoriaPedidos_QB whereNota($value)
     * @method _IH_HistoriaPedidos_QB whereCantCompro($value)
     * @method _IH_HistoriaPedidos_QB whereAtendidoPor($value)
     * @method _IH_HistoriaPedidos_QB whereAtendidoPorId($value)
     * @method _IH_HistoriaPedidos_QB whereCreatedAt($value)
     * @method _IH_HistoriaPedidos_QB whereUpdatedAt($value)
     * @method HistoriaPedidos baseSole(array|string $columns = ['*'])
     * @method HistoriaPedidos create(array $attributes = [])
     * @method _IH_HistoriaPedidos_C|HistoriaPedidos[] cursor()
     * @method HistoriaPedidos|null|_IH_HistoriaPedidos_C|HistoriaPedidos[] find($id, array $columns = ['*'])
     * @method _IH_HistoriaPedidos_C|HistoriaPedidos[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method HistoriaPedidos|_IH_HistoriaPedidos_C|HistoriaPedidos[] findOrFail($id, array $columns = ['*'])
     * @method HistoriaPedidos|_IH_HistoriaPedidos_C|HistoriaPedidos[] findOrNew($id, array $columns = ['*'])
     * @method HistoriaPedidos first(array|string $columns = ['*'])
     * @method HistoriaPedidos firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method HistoriaPedidos firstOrCreate(array $attributes = [], array $values = [])
     * @method HistoriaPedidos firstOrFail(array $columns = ['*'])
     * @method HistoriaPedidos firstOrNew(array $attributes = [], array $values = [])
     * @method HistoriaPedidos firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method HistoriaPedidos forceCreate(array $attributes)
     * @method _IH_HistoriaPedidos_C|HistoriaPedidos[] fromQuery(string $query, array $bindings = [])
     * @method _IH_HistoriaPedidos_C|HistoriaPedidos[] get(array|string $columns = ['*'])
     * @method HistoriaPedidos getModel()
     * @method HistoriaPedidos[] getModels(array|string $columns = ['*'])
     * @method _IH_HistoriaPedidos_C|HistoriaPedidos[] hydrate(array $items)
     * @method HistoriaPedidos make(array $attributes = [])
     * @method HistoriaPedidos newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|HistoriaPedidos[]|_IH_HistoriaPedidos_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|HistoriaPedidos[]|_IH_HistoriaPedidos_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method HistoriaPedidos sole(array|string $columns = ['*'])
     * @method HistoriaPedidos updateOrCreate(array $attributes, array $values = [])
     * @method _IH_HistoriaPedidos_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_HistoriaPedidos_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_HistoriaPedidos_QB extends _BaseBuilder {}
    
    /**
     * @method HistorialVidas|null getOrPut($key, $value)
     * @method HistorialVidas|$this shift(int $count = 1)
     * @method HistorialVidas|null firstOrFail($key = null, $operator = null, $value = null)
     * @method HistorialVidas|$this pop(int $count = 1)
     * @method HistorialVidas|null pull($key, $default = null)
     * @method HistorialVidas|null last(callable $callback = null, $default = null)
     * @method HistorialVidas|$this random(int|null $number = null)
     * @method HistorialVidas|null sole($key = null, $operator = null, $value = null)
     * @method HistorialVidas|null get($key, $default = null)
     * @method HistorialVidas|null first(callable $callback = null, $default = null)
     * @method HistorialVidas|null firstWhere(string $key, $operator = null, $value = null)
     * @method HistorialVidas|null find($key, $default = null)
     * @method HistorialVidas[] all()
     */
    class _IH_HistorialVidas_C extends _BaseCollection {
        /**
         * @param int $size
         * @return HistorialVidas[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_HistorialVidas_QB whereId($value)
     * @method _IH_HistorialVidas_QB whereUserId($value)
     * @method _IH_HistorialVidas_QB whereCreatedAt($value)
     * @method _IH_HistorialVidas_QB whereUpdatedAt($value)
     * @method _IH_HistorialVidas_QB whereAccion($value)
     * @method _IH_HistorialVidas_QB whereResponsable($value)
     * @method HistorialVidas baseSole(array|string $columns = ['*'])
     * @method HistorialVidas create(array $attributes = [])
     * @method _IH_HistorialVidas_C|HistorialVidas[] cursor()
     * @method HistorialVidas|null|_IH_HistorialVidas_C|HistorialVidas[] find($id, array $columns = ['*'])
     * @method _IH_HistorialVidas_C|HistorialVidas[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method HistorialVidas|_IH_HistorialVidas_C|HistorialVidas[] findOrFail($id, array $columns = ['*'])
     * @method HistorialVidas|_IH_HistorialVidas_C|HistorialVidas[] findOrNew($id, array $columns = ['*'])
     * @method HistorialVidas first(array|string $columns = ['*'])
     * @method HistorialVidas firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method HistorialVidas firstOrCreate(array $attributes = [], array $values = [])
     * @method HistorialVidas firstOrFail(array $columns = ['*'])
     * @method HistorialVidas firstOrNew(array $attributes = [], array $values = [])
     * @method HistorialVidas firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method HistorialVidas forceCreate(array $attributes)
     * @method _IH_HistorialVidas_C|HistorialVidas[] fromQuery(string $query, array $bindings = [])
     * @method _IH_HistorialVidas_C|HistorialVidas[] get(array|string $columns = ['*'])
     * @method HistorialVidas getModel()
     * @method HistorialVidas[] getModels(array|string $columns = ['*'])
     * @method _IH_HistorialVidas_C|HistorialVidas[] hydrate(array $items)
     * @method HistorialVidas make(array $attributes = [])
     * @method HistorialVidas newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|HistorialVidas[]|_IH_HistorialVidas_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|HistorialVidas[]|_IH_HistorialVidas_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method HistorialVidas sole(array|string $columns = ['*'])
     * @method HistorialVidas updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_HistorialVidas_QB extends _BaseBuilder {}
    
    /**
     * @method ImageAgenda|null getOrPut($key, $value)
     * @method ImageAgenda|$this shift(int $count = 1)
     * @method ImageAgenda|null firstOrFail($key = null, $operator = null, $value = null)
     * @method ImageAgenda|$this pop(int $count = 1)
     * @method ImageAgenda|null pull($key, $default = null)
     * @method ImageAgenda|null last(callable $callback = null, $default = null)
     * @method ImageAgenda|$this random(int|null $number = null)
     * @method ImageAgenda|null sole($key = null, $operator = null, $value = null)
     * @method ImageAgenda|null get($key, $default = null)
     * @method ImageAgenda|null first(callable $callback = null, $default = null)
     * @method ImageAgenda|null firstWhere(string $key, $operator = null, $value = null)
     * @method ImageAgenda|null find($key, $default = null)
     * @method ImageAgenda[] all()
     */
    class _IH_ImageAgenda_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ImageAgenda[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_ImageAgenda_QB whereId($value)
     * @method _IH_ImageAgenda_QB whereUnsigned($value)
     * @method _IH_ImageAgenda_QB whereEventId($value)
     * @method _IH_ImageAgenda_QB whereFilename($value)
     * @method _IH_ImageAgenda_QB whereFilepath($value)
     * @method _IH_ImageAgenda_QB whereFiletype($value)
     * @method _IH_ImageAgenda_QB whereStatus($value)
     * @method _IH_ImageAgenda_QB whereCreatedAt($value)
     * @method _IH_ImageAgenda_QB whereUpdatedAt($value)
     * @method ImageAgenda baseSole(array|string $columns = ['*'])
     * @method ImageAgenda create(array $attributes = [])
     * @method _IH_ImageAgenda_C|ImageAgenda[] cursor()
     * @method ImageAgenda|null|_IH_ImageAgenda_C|ImageAgenda[] find($id, array $columns = ['*'])
     * @method _IH_ImageAgenda_C|ImageAgenda[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method ImageAgenda|_IH_ImageAgenda_C|ImageAgenda[] findOrFail($id, array $columns = ['*'])
     * @method ImageAgenda|_IH_ImageAgenda_C|ImageAgenda[] findOrNew($id, array $columns = ['*'])
     * @method ImageAgenda first(array|string $columns = ['*'])
     * @method ImageAgenda firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method ImageAgenda firstOrCreate(array $attributes = [], array $values = [])
     * @method ImageAgenda firstOrFail(array $columns = ['*'])
     * @method ImageAgenda firstOrNew(array $attributes = [], array $values = [])
     * @method ImageAgenda firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ImageAgenda forceCreate(array $attributes)
     * @method _IH_ImageAgenda_C|ImageAgenda[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ImageAgenda_C|ImageAgenda[] get(array|string $columns = ['*'])
     * @method ImageAgenda getModel()
     * @method ImageAgenda[] getModels(array|string $columns = ['*'])
     * @method _IH_ImageAgenda_C|ImageAgenda[] hydrate(array $items)
     * @method ImageAgenda make(array $attributes = [])
     * @method ImageAgenda newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ImageAgenda[]|_IH_ImageAgenda_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|ImageAgenda[]|_IH_ImageAgenda_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ImageAgenda sole(array|string $columns = ['*'])
     * @method ImageAgenda updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_ImageAgenda_QB extends _BaseBuilder {}
    
    /**
     * @method ImagenAtencion|null getOrPut($key, $value)
     * @method ImagenAtencion|$this shift(int $count = 1)
     * @method ImagenAtencion|null firstOrFail($key = null, $operator = null, $value = null)
     * @method ImagenAtencion|$this pop(int $count = 1)
     * @method ImagenAtencion|null pull($key, $default = null)
     * @method ImagenAtencion|null last(callable $callback = null, $default = null)
     * @method ImagenAtencion|$this random(int|null $number = null)
     * @method ImagenAtencion|null sole($key = null, $operator = null, $value = null)
     * @method ImagenAtencion|null get($key, $default = null)
     * @method ImagenAtencion|null first(callable $callback = null, $default = null)
     * @method ImagenAtencion|null firstWhere(string $key, $operator = null, $value = null)
     * @method ImagenAtencion|null find($key, $default = null)
     * @method ImagenAtencion[] all()
     */
    class _IH_ImagenAtencion_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ImagenAtencion[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_ImagenAtencion_QB whereId($value)
     * @method _IH_ImagenAtencion_QB wherePedidoId($value)
     * @method _IH_ImagenAtencion_QB whereAdjunto($value)
     * @method _IH_ImagenAtencion_QB whereConfirm($value)
     * @method _IH_ImagenAtencion_QB whereEstado($value)
     * @method _IH_ImagenAtencion_QB whereCreatedAt($value)
     * @method _IH_ImagenAtencion_QB whereUpdatedAt($value)
     * @method ImagenAtencion baseSole(array|string $columns = ['*'])
     * @method ImagenAtencion create(array $attributes = [])
     * @method _IH_ImagenAtencion_C|ImagenAtencion[] cursor()
     * @method ImagenAtencion|null|_IH_ImagenAtencion_C|ImagenAtencion[] find($id, array $columns = ['*'])
     * @method _IH_ImagenAtencion_C|ImagenAtencion[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method ImagenAtencion|_IH_ImagenAtencion_C|ImagenAtencion[] findOrFail($id, array $columns = ['*'])
     * @method ImagenAtencion|_IH_ImagenAtencion_C|ImagenAtencion[] findOrNew($id, array $columns = ['*'])
     * @method ImagenAtencion first(array|string $columns = ['*'])
     * @method ImagenAtencion firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method ImagenAtencion firstOrCreate(array $attributes = [], array $values = [])
     * @method ImagenAtencion firstOrFail(array $columns = ['*'])
     * @method ImagenAtencion firstOrNew(array $attributes = [], array $values = [])
     * @method ImagenAtencion firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ImagenAtencion forceCreate(array $attributes)
     * @method _IH_ImagenAtencion_C|ImagenAtencion[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ImagenAtencion_C|ImagenAtencion[] get(array|string $columns = ['*'])
     * @method ImagenAtencion getModel()
     * @method ImagenAtencion[] getModels(array|string $columns = ['*'])
     * @method _IH_ImagenAtencion_C|ImagenAtencion[] hydrate(array $items)
     * @method ImagenAtencion make(array $attributes = [])
     * @method ImagenAtencion newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ImagenAtencion[]|_IH_ImagenAtencion_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|ImagenAtencion[]|_IH_ImagenAtencion_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ImagenAtencion sole(array|string $columns = ['*'])
     * @method ImagenAtencion updateOrCreate(array $attributes, array $values = [])
     * @method _IH_ImagenAtencion_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_ImagenAtencion_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_ImagenAtencion_QB extends _BaseBuilder {}
    
    /**
     * @method ImagenPedido|null getOrPut($key, $value)
     * @method ImagenPedido|$this shift(int $count = 1)
     * @method ImagenPedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method ImagenPedido|$this pop(int $count = 1)
     * @method ImagenPedido|null pull($key, $default = null)
     * @method ImagenPedido|null last(callable $callback = null, $default = null)
     * @method ImagenPedido|$this random(int|null $number = null)
     * @method ImagenPedido|null sole($key = null, $operator = null, $value = null)
     * @method ImagenPedido|null get($key, $default = null)
     * @method ImagenPedido|null first(callable $callback = null, $default = null)
     * @method ImagenPedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method ImagenPedido|null find($key, $default = null)
     * @method ImagenPedido[] all()
     */
    class _IH_ImagenPedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ImagenPedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_ImagenPedido_QB whereId($value)
     * @method _IH_ImagenPedido_QB wherePedidoId($value)
     * @method _IH_ImagenPedido_QB whereAdjunto($value)
     * @method _IH_ImagenPedido_QB whereEstado($value)
     * @method _IH_ImagenPedido_QB whereCreatedAt($value)
     * @method _IH_ImagenPedido_QB whereUpdatedAt($value)
     * @method ImagenPedido baseSole(array|string $columns = ['*'])
     * @method ImagenPedido create(array $attributes = [])
     * @method _IH_ImagenPedido_C|ImagenPedido[] cursor()
     * @method ImagenPedido|null|_IH_ImagenPedido_C|ImagenPedido[] find($id, array $columns = ['*'])
     * @method _IH_ImagenPedido_C|ImagenPedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method ImagenPedido|_IH_ImagenPedido_C|ImagenPedido[] findOrFail($id, array $columns = ['*'])
     * @method ImagenPedido|_IH_ImagenPedido_C|ImagenPedido[] findOrNew($id, array $columns = ['*'])
     * @method ImagenPedido first(array|string $columns = ['*'])
     * @method ImagenPedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method ImagenPedido firstOrCreate(array $attributes = [], array $values = [])
     * @method ImagenPedido firstOrFail(array $columns = ['*'])
     * @method ImagenPedido firstOrNew(array $attributes = [], array $values = [])
     * @method ImagenPedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ImagenPedido forceCreate(array $attributes)
     * @method _IH_ImagenPedido_C|ImagenPedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ImagenPedido_C|ImagenPedido[] get(array|string $columns = ['*'])
     * @method ImagenPedido getModel()
     * @method ImagenPedido[] getModels(array|string $columns = ['*'])
     * @method _IH_ImagenPedido_C|ImagenPedido[] hydrate(array $items)
     * @method ImagenPedido make(array $attributes = [])
     * @method ImagenPedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ImagenPedido[]|_IH_ImagenPedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|ImagenPedido[]|_IH_ImagenPedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ImagenPedido sole(array|string $columns = ['*'])
     * @method ImagenPedido updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_ImagenPedido_QB extends _BaseBuilder {}
    
    /**
     * @method ListadoResultado|null getOrPut($key, $value)
     * @method ListadoResultado|$this shift(int $count = 1)
     * @method ListadoResultado|null firstOrFail($key = null, $operator = null, $value = null)
     * @method ListadoResultado|$this pop(int $count = 1)
     * @method ListadoResultado|null pull($key, $default = null)
     * @method ListadoResultado|null last(callable $callback = null, $default = null)
     * @method ListadoResultado|$this random(int|null $number = null)
     * @method ListadoResultado|null sole($key = null, $operator = null, $value = null)
     * @method ListadoResultado|null get($key, $default = null)
     * @method ListadoResultado|null first(callable $callback = null, $default = null)
     * @method ListadoResultado|null firstWhere(string $key, $operator = null, $value = null)
     * @method ListadoResultado|null find($key, $default = null)
     * @method ListadoResultado[] all()
     */
    class _IH_ListadoResultado_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ListadoResultado[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_ListadoResultado_QB whereId($value)
     * @method _IH_ListadoResultado_QB whereUserIdentificador($value)
     * @method _IH_ListadoResultado_QB whereA202111($value)
     * @method _IH_ListadoResultado_QB whereS202111($value)
     * @method _IH_ListadoResultado_QB whereA202112($value)
     * @method _IH_ListadoResultado_QB whereS202112($value)
     * @method _IH_ListadoResultado_QB whereA202201($value)
     * @method _IH_ListadoResultado_QB whereS202201($value)
     * @method _IH_ListadoResultado_QB whereA202202($value)
     * @method _IH_ListadoResultado_QB whereS202202($value)
     * @method _IH_ListadoResultado_QB whereA202203($value)
     * @method _IH_ListadoResultado_QB whereS202203($value)
     * @method _IH_ListadoResultado_QB whereA202204($value)
     * @method _IH_ListadoResultado_QB whereS202204($value)
     * @method _IH_ListadoResultado_QB whereA202205($value)
     * @method _IH_ListadoResultado_QB whereS202205($value)
     * @method _IH_ListadoResultado_QB whereA202206($value)
     * @method _IH_ListadoResultado_QB whereS202206($value)
     * @method _IH_ListadoResultado_QB whereA202207($value)
     * @method _IH_ListadoResultado_QB whereS202207($value)
     * @method _IH_ListadoResultado_QB whereA202208($value)
     * @method _IH_ListadoResultado_QB whereS202208($value)
     * @method _IH_ListadoResultado_QB whereA202209($value)
     * @method _IH_ListadoResultado_QB whereS202209($value)
     * @method _IH_ListadoResultado_QB whereA202210($value)
     * @method _IH_ListadoResultado_QB whereS202210($value)
     * @method _IH_ListadoResultado_QB whereA202211($value)
     * @method _IH_ListadoResultado_QB whereS202211($value)
     * @method _IH_ListadoResultado_QB whereA202212($value)
     * @method _IH_ListadoResultado_QB whereS202212($value)
     * @method _IH_ListadoResultado_QB whereA202301($value)
     * @method _IH_ListadoResultado_QB whereS202301($value)
     * @method ListadoResultado baseSole(array|string $columns = ['*'])
     * @method ListadoResultado create(array $attributes = [])
     * @method _IH_ListadoResultado_C|ListadoResultado[] cursor()
     * @method ListadoResultado|null|_IH_ListadoResultado_C|ListadoResultado[] find($id, array $columns = ['*'])
     * @method _IH_ListadoResultado_C|ListadoResultado[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method ListadoResultado|_IH_ListadoResultado_C|ListadoResultado[] findOrFail($id, array $columns = ['*'])
     * @method ListadoResultado|_IH_ListadoResultado_C|ListadoResultado[] findOrNew($id, array $columns = ['*'])
     * @method ListadoResultado first(array|string $columns = ['*'])
     * @method ListadoResultado firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method ListadoResultado firstOrCreate(array $attributes = [], array $values = [])
     * @method ListadoResultado firstOrFail(array $columns = ['*'])
     * @method ListadoResultado firstOrNew(array $attributes = [], array $values = [])
     * @method ListadoResultado firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ListadoResultado forceCreate(array $attributes)
     * @method _IH_ListadoResultado_C|ListadoResultado[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ListadoResultado_C|ListadoResultado[] get(array|string $columns = ['*'])
     * @method ListadoResultado getModel()
     * @method ListadoResultado[] getModels(array|string $columns = ['*'])
     * @method _IH_ListadoResultado_C|ListadoResultado[] hydrate(array $items)
     * @method ListadoResultado make(array $attributes = [])
     * @method ListadoResultado newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ListadoResultado[]|_IH_ListadoResultado_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|ListadoResultado[]|_IH_ListadoResultado_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ListadoResultado sole(array|string $columns = ['*'])
     * @method ListadoResultado updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_ListadoResultado_QB extends _BaseBuilder {}
    
    /**
     * @method Media|null getOrPut($key, $value)
     * @method Media|$this shift(int $count = 1)
     * @method Media|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Media|$this pop(int $count = 1)
     * @method Media|null pull($key, $default = null)
     * @method Media|null last(callable $callback = null, $default = null)
     * @method Media|$this random(int|null $number = null)
     * @method Media|null sole($key = null, $operator = null, $value = null)
     * @method Media|null get($key, $default = null)
     * @method Media|null first(callable $callback = null, $default = null)
     * @method Media|null firstWhere(string $key, $operator = null, $value = null)
     * @method Media|null find($key, $default = null)
     * @method Media[] all()
     */
    class _IH_Media_C extends MediaCollection {
        /**
         * @param int $size
         * @return Media[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Media_QB whereId($value)
     * @method _IH_Media_QB whereModelId($value)
     * @method _IH_Media_QB whereModelType($value)
     * @method _IH_Media_QB whereUuid($value)
     * @method _IH_Media_QB whereCollectionName($value)
     * @method _IH_Media_QB whereName($value)
     * @method _IH_Media_QB whereFileName($value)
     * @method _IH_Media_QB whereMimeType($value)
     * @method _IH_Media_QB whereDisk($value)
     * @method _IH_Media_QB whereConversionsDisk($value)
     * @method _IH_Media_QB whereSize($value)
     * @method _IH_Media_QB whereManipulations($value)
     * @method _IH_Media_QB whereCustomProperties($value)
     * @method _IH_Media_QB whereGeneratedConversions($value)
     * @method _IH_Media_QB whereResponsiveImages($value)
     * @method _IH_Media_QB whereOrderColumn($value)
     * @method _IH_Media_QB whereCreatedAt($value)
     * @method _IH_Media_QB whereUpdatedAt($value)
     * @method Media baseSole(array|string $columns = ['*'])
     * @method Media create(array $attributes = [])
     * @method _IH_Media_C|Media[] cursor()
     * @method Media|null|_IH_Media_C|Media[] find($id, array $columns = ['*'])
     * @method _IH_Media_C|Media[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Media|_IH_Media_C|Media[] findOrFail($id, array $columns = ['*'])
     * @method Media|_IH_Media_C|Media[] findOrNew($id, array $columns = ['*'])
     * @method Media first(array|string $columns = ['*'])
     * @method Media firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Media firstOrCreate(array $attributes = [], array $values = [])
     * @method Media firstOrFail(array $columns = ['*'])
     * @method Media firstOrNew(array $attributes = [], array $values = [])
     * @method Media firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Media forceCreate(array $attributes)
     * @method _IH_Media_C|Media[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Media_C|Media[] get(array|string $columns = ['*'])
     * @method Media getModel()
     * @method Media[] getModels(array|string $columns = ['*'])
     * @method _IH_Media_C|Media[] hydrate(array $items)
     * @method Media make(array $attributes = [])
     * @method Media newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Media[]|_IH_Media_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Media[]|_IH_Media_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Media sole(array|string $columns = ['*'])
     * @method Media updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Media_QB extends _BaseBuilder {}
    
    /**
     * @method Membership|null getOrPut($key, $value)
     * @method Membership|$this shift(int $count = 1)
     * @method Membership|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Membership|$this pop(int $count = 1)
     * @method Membership|null pull($key, $default = null)
     * @method Membership|null last(callable $callback = null, $default = null)
     * @method Membership|$this random(int|null $number = null)
     * @method Membership|null sole($key = null, $operator = null, $value = null)
     * @method Membership|null get($key, $default = null)
     * @method Membership|null first(callable $callback = null, $default = null)
     * @method Membership|null firstWhere(string $key, $operator = null, $value = null)
     * @method Membership|null find($key, $default = null)
     * @method Membership[] all()
     */
    class _IH_Membership_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Membership[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Membership baseSole(array|string $columns = ['*'])
     * @method Membership create(array $attributes = [])
     * @method _IH_Membership_C|Membership[] cursor()
     * @method Membership|null|_IH_Membership_C|Membership[] find($id, array $columns = ['*'])
     * @method _IH_Membership_C|Membership[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Membership|_IH_Membership_C|Membership[] findOrFail($id, array $columns = ['*'])
     * @method Membership|_IH_Membership_C|Membership[] findOrNew($id, array $columns = ['*'])
     * @method Membership first(array|string $columns = ['*'])
     * @method Membership firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Membership firstOrCreate(array $attributes = [], array $values = [])
     * @method Membership firstOrFail(array $columns = ['*'])
     * @method Membership firstOrNew(array $attributes = [], array $values = [])
     * @method Membership firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Membership forceCreate(array $attributes)
     * @method _IH_Membership_C|Membership[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Membership_C|Membership[] get(array|string $columns = ['*'])
     * @method Membership getModel()
     * @method Membership[] getModels(array|string $columns = ['*'])
     * @method _IH_Membership_C|Membership[] hydrate(array $items)
     * @method Membership make(array $attributes = [])
     * @method Membership newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Membership[]|_IH_Membership_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Membership[]|_IH_Membership_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Membership sole(array|string $columns = ['*'])
     * @method Membership updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Membership_QB extends _BaseBuilder {}
    
    /**
     * @method Meta|null getOrPut($key, $value)
     * @method Meta|$this shift(int $count = 1)
     * @method Meta|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Meta|$this pop(int $count = 1)
     * @method Meta|null pull($key, $default = null)
     * @method Meta|null last(callable $callback = null, $default = null)
     * @method Meta|$this random(int|null $number = null)
     * @method Meta|null sole($key = null, $operator = null, $value = null)
     * @method Meta|null get($key, $default = null)
     * @method Meta|null first(callable $callback = null, $default = null)
     * @method Meta|null firstWhere(string $key, $operator = null, $value = null)
     * @method Meta|null find($key, $default = null)
     * @method Meta[] all()
     */
    class _IH_Meta_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Meta[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Meta baseSole(array|string $columns = ['*'])
     * @method Meta create(array $attributes = [])
     * @method _IH_Meta_C|Meta[] cursor()
     * @method Meta|null|_IH_Meta_C|Meta[] find($id, array $columns = ['*'])
     * @method _IH_Meta_C|Meta[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Meta|_IH_Meta_C|Meta[] findOrFail($id, array $columns = ['*'])
     * @method Meta|_IH_Meta_C|Meta[] findOrNew($id, array $columns = ['*'])
     * @method Meta first(array|string $columns = ['*'])
     * @method Meta firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Meta firstOrCreate(array $attributes = [], array $values = [])
     * @method Meta firstOrFail(array $columns = ['*'])
     * @method Meta firstOrNew(array $attributes = [], array $values = [])
     * @method Meta firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Meta forceCreate(array $attributes)
     * @method _IH_Meta_C|Meta[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Meta_C|Meta[] get(array|string $columns = ['*'])
     * @method Meta getModel()
     * @method Meta[] getModels(array|string $columns = ['*'])
     * @method _IH_Meta_C|Meta[] hydrate(array $items)
     * @method Meta make(array $attributes = [])
     * @method Meta newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Meta[]|_IH_Meta_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Meta[]|_IH_Meta_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Meta sole(array|string $columns = ['*'])
     * @method Meta updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Meta_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Meta_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Meta_QB extends _BaseBuilder {}
    
    /**
     * @method MovimientoBancario|null getOrPut($key, $value)
     * @method MovimientoBancario|$this shift(int $count = 1)
     * @method MovimientoBancario|null firstOrFail($key = null, $operator = null, $value = null)
     * @method MovimientoBancario|$this pop(int $count = 1)
     * @method MovimientoBancario|null pull($key, $default = null)
     * @method MovimientoBancario|null last(callable $callback = null, $default = null)
     * @method MovimientoBancario|$this random(int|null $number = null)
     * @method MovimientoBancario|null sole($key = null, $operator = null, $value = null)
     * @method MovimientoBancario|null get($key, $default = null)
     * @method MovimientoBancario|null first(callable $callback = null, $default = null)
     * @method MovimientoBancario|null firstWhere(string $key, $operator = null, $value = null)
     * @method MovimientoBancario|null find($key, $default = null)
     * @method MovimientoBancario[] all()
     */
    class _IH_MovimientoBancario_C extends _BaseCollection {
        /**
         * @param int $size
         * @return MovimientoBancario[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_MovimientoBancario_QB whereId($value)
     * @method _IH_MovimientoBancario_QB whereBanco($value)
     * @method _IH_MovimientoBancario_QB whereTitular($value)
     * @method _IH_MovimientoBancario_QB whereImporte($value)
     * @method _IH_MovimientoBancario_QB whereTipo($value)
     * @method _IH_MovimientoBancario_QB whereDescripcionOtros($value)
     * @method _IH_MovimientoBancario_QB whereFecha($value)
     * @method _IH_MovimientoBancario_QB wherePago($value)
     * @method _IH_MovimientoBancario_QB whereDetpago($value)
     * @method _IH_MovimientoBancario_QB whereCabpago($value)
     * @method _IH_MovimientoBancario_QB whereEstado($value)
     * @method _IH_MovimientoBancario_QB whereCreatedAt($value)
     * @method _IH_MovimientoBancario_QB whereUpdatedAt($value)
     * @method MovimientoBancario baseSole(array|string $columns = ['*'])
     * @method MovimientoBancario create(array $attributes = [])
     * @method _IH_MovimientoBancario_C|MovimientoBancario[] cursor()
     * @method MovimientoBancario|null|_IH_MovimientoBancario_C|MovimientoBancario[] find($id, array $columns = ['*'])
     * @method _IH_MovimientoBancario_C|MovimientoBancario[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method MovimientoBancario|_IH_MovimientoBancario_C|MovimientoBancario[] findOrFail($id, array $columns = ['*'])
     * @method MovimientoBancario|_IH_MovimientoBancario_C|MovimientoBancario[] findOrNew($id, array $columns = ['*'])
     * @method MovimientoBancario first(array|string $columns = ['*'])
     * @method MovimientoBancario firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method MovimientoBancario firstOrCreate(array $attributes = [], array $values = [])
     * @method MovimientoBancario firstOrFail(array $columns = ['*'])
     * @method MovimientoBancario firstOrNew(array $attributes = [], array $values = [])
     * @method MovimientoBancario firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method MovimientoBancario forceCreate(array $attributes)
     * @method _IH_MovimientoBancario_C|MovimientoBancario[] fromQuery(string $query, array $bindings = [])
     * @method _IH_MovimientoBancario_C|MovimientoBancario[] get(array|string $columns = ['*'])
     * @method MovimientoBancario getModel()
     * @method MovimientoBancario[] getModels(array|string $columns = ['*'])
     * @method _IH_MovimientoBancario_C|MovimientoBancario[] hydrate(array $items)
     * @method MovimientoBancario make(array $attributes = [])
     * @method MovimientoBancario newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|MovimientoBancario[]|_IH_MovimientoBancario_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|MovimientoBancario[]|_IH_MovimientoBancario_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method MovimientoBancario sole(array|string $columns = ['*'])
     * @method MovimientoBancario updateOrCreate(array $attributes, array $values = [])
     * @method _IH_MovimientoBancario_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_MovimientoBancario_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_MovimientoBancario_QB sinConciliar()
     */
    class _IH_MovimientoBancario_QB extends _BaseBuilder {}
    
    /**
     * @method OlvaMovimiento|null getOrPut($key, $value)
     * @method OlvaMovimiento|$this shift(int $count = 1)
     * @method OlvaMovimiento|null firstOrFail($key = null, $operator = null, $value = null)
     * @method OlvaMovimiento|$this pop(int $count = 1)
     * @method OlvaMovimiento|null pull($key, $default = null)
     * @method OlvaMovimiento|null last(callable $callback = null, $default = null)
     * @method OlvaMovimiento|$this random(int|null $number = null)
     * @method OlvaMovimiento|null sole($key = null, $operator = null, $value = null)
     * @method OlvaMovimiento|null get($key, $default = null)
     * @method OlvaMovimiento|null first(callable $callback = null, $default = null)
     * @method OlvaMovimiento|null firstWhere(string $key, $operator = null, $value = null)
     * @method OlvaMovimiento|null find($key, $default = null)
     * @method OlvaMovimiento[] all()
     */
    class _IH_OlvaMovimiento_C extends _BaseCollection {
        /**
         * @param int $size
         * @return OlvaMovimiento[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_OlvaMovimiento_QB whereId($value)
     * @method _IH_OlvaMovimiento_QB whereObs($value)
     * @method _IH_OlvaMovimiento_QB whereNombreSede($value)
     * @method _IH_OlvaMovimiento_QB whereFechaCreacion($value)
     * @method _IH_OlvaMovimiento_QB whereEstadoTracking($value)
     * @method _IH_OlvaMovimiento_QB whereIdRptEnvioRuta($value)
     * @method _IH_OlvaMovimiento_QB whereStatus($value)
     * @method _IH_OlvaMovimiento_QB whereCreatedAt($value)
     * @method _IH_OlvaMovimiento_QB whereUpdatedAt($value)
     * @method _IH_OlvaMovimiento_QB whereNumerotrack($value)
     * @method _IH_OlvaMovimiento_QB whereAniotrack($value)
     * @method OlvaMovimiento baseSole(array|string $columns = ['*'])
     * @method OlvaMovimiento create(array $attributes = [])
     * @method _IH_OlvaMovimiento_C|OlvaMovimiento[] cursor()
     * @method OlvaMovimiento|null|_IH_OlvaMovimiento_C|OlvaMovimiento[] find($id, array $columns = ['*'])
     * @method _IH_OlvaMovimiento_C|OlvaMovimiento[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method OlvaMovimiento|_IH_OlvaMovimiento_C|OlvaMovimiento[] findOrFail($id, array $columns = ['*'])
     * @method OlvaMovimiento|_IH_OlvaMovimiento_C|OlvaMovimiento[] findOrNew($id, array $columns = ['*'])
     * @method OlvaMovimiento first(array|string $columns = ['*'])
     * @method OlvaMovimiento firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method OlvaMovimiento firstOrCreate(array $attributes = [], array $values = [])
     * @method OlvaMovimiento firstOrFail(array $columns = ['*'])
     * @method OlvaMovimiento firstOrNew(array $attributes = [], array $values = [])
     * @method OlvaMovimiento firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method OlvaMovimiento forceCreate(array $attributes)
     * @method _IH_OlvaMovimiento_C|OlvaMovimiento[] fromQuery(string $query, array $bindings = [])
     * @method _IH_OlvaMovimiento_C|OlvaMovimiento[] get(array|string $columns = ['*'])
     * @method OlvaMovimiento getModel()
     * @method OlvaMovimiento[] getModels(array|string $columns = ['*'])
     * @method _IH_OlvaMovimiento_C|OlvaMovimiento[] hydrate(array $items)
     * @method OlvaMovimiento make(array $attributes = [])
     * @method OlvaMovimiento newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|OlvaMovimiento[]|_IH_OlvaMovimiento_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|OlvaMovimiento[]|_IH_OlvaMovimiento_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method OlvaMovimiento sole(array|string $columns = ['*'])
     * @method OlvaMovimiento updateOrCreate(array $attributes, array $values = [])
     * @method _IH_OlvaMovimiento_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_OlvaMovimiento_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_OlvaMovimiento_QB extends _BaseBuilder {}
    
    /**
     * @method PagoPedido|null getOrPut($key, $value)
     * @method PagoPedido|$this shift(int $count = 1)
     * @method PagoPedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PagoPedido|$this pop(int $count = 1)
     * @method PagoPedido|null pull($key, $default = null)
     * @method PagoPedido|null last(callable $callback = null, $default = null)
     * @method PagoPedido|$this random(int|null $number = null)
     * @method PagoPedido|null sole($key = null, $operator = null, $value = null)
     * @method PagoPedido|null get($key, $default = null)
     * @method PagoPedido|null first(callable $callback = null, $default = null)
     * @method PagoPedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method PagoPedido|null find($key, $default = null)
     * @method PagoPedido[] all()
     */
    class _IH_PagoPedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PagoPedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PagoPedido_QB whereId($value)
     * @method _IH_PagoPedido_QB wherePagoId($value)
     * @method _IH_PagoPedido_QB wherePedidoId($value)
     * @method _IH_PagoPedido_QB wherePagado($value)
     * @method _IH_PagoPedido_QB whereAbono($value)
     * @method _IH_PagoPedido_QB whereEstado($value)
     * @method _IH_PagoPedido_QB whereCreatedAt($value)
     * @method _IH_PagoPedido_QB whereUpdatedAt($value)
     * @method PagoPedido baseSole(array|string $columns = ['*'])
     * @method PagoPedido create(array $attributes = [])
     * @method _IH_PagoPedido_C|PagoPedido[] cursor()
     * @method PagoPedido|null|_IH_PagoPedido_C|PagoPedido[] find($id, array $columns = ['*'])
     * @method _IH_PagoPedido_C|PagoPedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PagoPedido|_IH_PagoPedido_C|PagoPedido[] findOrFail($id, array $columns = ['*'])
     * @method PagoPedido|_IH_PagoPedido_C|PagoPedido[] findOrNew($id, array $columns = ['*'])
     * @method PagoPedido first(array|string $columns = ['*'])
     * @method PagoPedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PagoPedido firstOrCreate(array $attributes = [], array $values = [])
     * @method PagoPedido firstOrFail(array $columns = ['*'])
     * @method PagoPedido firstOrNew(array $attributes = [], array $values = [])
     * @method PagoPedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PagoPedido forceCreate(array $attributes)
     * @method _IH_PagoPedido_C|PagoPedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PagoPedido_C|PagoPedido[] get(array|string $columns = ['*'])
     * @method PagoPedido getModel()
     * @method PagoPedido[] getModels(array|string $columns = ['*'])
     * @method _IH_PagoPedido_C|PagoPedido[] hydrate(array $items)
     * @method PagoPedido make(array $attributes = [])
     * @method PagoPedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PagoPedido[]|_IH_PagoPedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PagoPedido[]|_IH_PagoPedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PagoPedido sole(array|string $columns = ['*'])
     * @method PagoPedido updateOrCreate(array $attributes, array $values = [])
     * @method _IH_PagoPedido_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_PagoPedido_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_PagoPedido_QB pagado($value = '2')
     */
    class _IH_PagoPedido_QB extends _BaseBuilder {}
    
    /**
     * @method Pago|null getOrPut($key, $value)
     * @method Pago|$this shift(int $count = 1)
     * @method Pago|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Pago|$this pop(int $count = 1)
     * @method Pago|null pull($key, $default = null)
     * @method Pago|null last(callable $callback = null, $default = null)
     * @method Pago|$this random(int|null $number = null)
     * @method Pago|null sole($key = null, $operator = null, $value = null)
     * @method Pago|null get($key, $default = null)
     * @method Pago|null first(callable $callback = null, $default = null)
     * @method Pago|null firstWhere(string $key, $operator = null, $value = null)
     * @method Pago|null find($key, $default = null)
     * @method Pago[] all()
     */
    class _IH_Pago_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Pago[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Pago_QB whereId($value)
     * @method _IH_Pago_QB whereUserId($value)
     * @method _IH_Pago_QB whereClienteId($value)
     * @method _IH_Pago_QB whereTotalCobro($value)
     * @method _IH_Pago_QB whereTotalPagado($value)
     * @method _IH_Pago_QB whereObservacion($value)
     * @method _IH_Pago_QB whereCondicion($value)
     * @method _IH_Pago_QB whereSubcondicion($value)
     * @method _IH_Pago_QB whereSubcondicionCode($value)
     * @method _IH_Pago_QB whereNotificacion($value)
     * @method _IH_Pago_QB whereSaldo($value)
     * @method _IH_Pago_QB whereDiferencia($value)
     * @method _IH_Pago_QB whereFechaAprobacion($value)
     * @method _IH_Pago_QB whereEstado($value)
     * @method _IH_Pago_QB whereCreatedAt($value)
     * @method _IH_Pago_QB whereUpdatedAt($value)
     * @method _IH_Pago_QB whereCondicionCode($value)
     * @method _IH_Pago_QB whereCorrelativo($value)
     * @method _IH_Pago_QB whereUserIdentificador($value)
     * @method _IH_Pago_QB whereUserClavepedido($value)
     * @method _IH_Pago_QB whereUserReg($value)
     * @method Pago baseSole(array|string $columns = ['*'])
     * @method Pago create(array $attributes = [])
     * @method _IH_Pago_C|Pago[] cursor()
     * @method Pago|null|_IH_Pago_C|Pago[] find($id, array $columns = ['*'])
     * @method _IH_Pago_C|Pago[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Pago|_IH_Pago_C|Pago[] findOrFail($id, array $columns = ['*'])
     * @method Pago|_IH_Pago_C|Pago[] findOrNew($id, array $columns = ['*'])
     * @method Pago first(array|string $columns = ['*'])
     * @method Pago firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Pago firstOrCreate(array $attributes = [], array $values = [])
     * @method Pago firstOrFail(array $columns = ['*'])
     * @method Pago firstOrNew(array $attributes = [], array $values = [])
     * @method Pago firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Pago forceCreate(array $attributes)
     * @method _IH_Pago_C|Pago[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Pago_C|Pago[] get(array|string $columns = ['*'])
     * @method Pago getModel()
     * @method Pago[] getModels(array|string $columns = ['*'])
     * @method _IH_Pago_C|Pago[] hydrate(array $items)
     * @method Pago make(array $attributes = [])
     * @method Pago newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Pago[]|_IH_Pago_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Pago[]|_IH_Pago_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Pago sole(array|string $columns = ['*'])
     * @method Pago updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Pago_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Pago_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_Pago_QB condicion($value)
     */
    class _IH_Pago_QB extends _BaseBuilder {}
    
    /**
     * @method PasswordReset|null getOrPut($key, $value)
     * @method PasswordReset|$this shift(int $count = 1)
     * @method PasswordReset|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PasswordReset|$this pop(int $count = 1)
     * @method PasswordReset|null pull($key, $default = null)
     * @method PasswordReset|null last(callable $callback = null, $default = null)
     * @method PasswordReset|$this random(int|null $number = null)
     * @method PasswordReset|null sole($key = null, $operator = null, $value = null)
     * @method PasswordReset|null get($key, $default = null)
     * @method PasswordReset|null first(callable $callback = null, $default = null)
     * @method PasswordReset|null firstWhere(string $key, $operator = null, $value = null)
     * @method PasswordReset|null find($key, $default = null)
     * @method PasswordReset[] all()
     */
    class _IH_PasswordReset_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PasswordReset[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PasswordReset_QB whereEmail($value)
     * @method _IH_PasswordReset_QB whereToken($value)
     * @method _IH_PasswordReset_QB whereCreatedAt($value)
     * @method _IH_PasswordReset_QB whereUpdatedAt($value)
     * @method PasswordReset baseSole(array|string $columns = ['*'])
     * @method PasswordReset create(array $attributes = [])
     * @method _IH_PasswordReset_C|PasswordReset[] cursor()
     * @method PasswordReset|null|_IH_PasswordReset_C|PasswordReset[] find($id, array $columns = ['*'])
     * @method _IH_PasswordReset_C|PasswordReset[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PasswordReset|_IH_PasswordReset_C|PasswordReset[] findOrFail($id, array $columns = ['*'])
     * @method PasswordReset|_IH_PasswordReset_C|PasswordReset[] findOrNew($id, array $columns = ['*'])
     * @method PasswordReset first(array|string $columns = ['*'])
     * @method PasswordReset firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PasswordReset firstOrCreate(array $attributes = [], array $values = [])
     * @method PasswordReset firstOrFail(array $columns = ['*'])
     * @method PasswordReset firstOrNew(array $attributes = [], array $values = [])
     * @method PasswordReset firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PasswordReset forceCreate(array $attributes)
     * @method _IH_PasswordReset_C|PasswordReset[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PasswordReset_C|PasswordReset[] get(array|string $columns = ['*'])
     * @method PasswordReset getModel()
     * @method PasswordReset[] getModels(array|string $columns = ['*'])
     * @method _IH_PasswordReset_C|PasswordReset[] hydrate(array $items)
     * @method PasswordReset make(array $attributes = [])
     * @method PasswordReset newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PasswordReset[]|_IH_PasswordReset_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PasswordReset[]|_IH_PasswordReset_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PasswordReset sole(array|string $columns = ['*'])
     * @method PasswordReset updateOrCreate(array $attributes, array $values = [])
     * @method _IH_PasswordReset_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_PasswordReset_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_PasswordReset_QB extends _BaseBuilder {}
    
    /**
     * @method PedidoHistory|null getOrPut($key, $value)
     * @method PedidoHistory|$this shift(int $count = 1)
     * @method PedidoHistory|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PedidoHistory|$this pop(int $count = 1)
     * @method PedidoHistory|null pull($key, $default = null)
     * @method PedidoHistory|null last(callable $callback = null, $default = null)
     * @method PedidoHistory|$this random(int|null $number = null)
     * @method PedidoHistory|null sole($key = null, $operator = null, $value = null)
     * @method PedidoHistory|null get($key, $default = null)
     * @method PedidoHistory|null first(callable $callback = null, $default = null)
     * @method PedidoHistory|null firstWhere(string $key, $operator = null, $value = null)
     * @method PedidoHistory|null find($key, $default = null)
     * @method PedidoHistory[] all()
     */
    class _IH_PedidoHistory_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PedidoHistory[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PedidoHistory_QB whereId($value)
     * @method _IH_PedidoHistory_QB whereUserId($value)
     * @method _IH_PedidoHistory_QB whereIdentificador($value)
     * @method _IH_PedidoHistory_QB whereClienteId($value)
     * @method _IH_PedidoHistory_QB whereRuc($value)
     * @method _IH_PedidoHistory_QB whereEmpresa($value)
     * @method _IH_PedidoHistory_QB whereMes($value)
     * @method _IH_PedidoHistory_QB whereCantidad($value)
     * @method _IH_PedidoHistory_QB whereTipoBanca($value)
     * @method _IH_PedidoHistory_QB whereDescripcion($value)
     * @method _IH_PedidoHistory_QB whereNota($value)
     * @method _IH_PedidoHistory_QB whereCourierPrice($value)
     * @method _IH_PedidoHistory_QB whereCreatedAt($value)
     * @method _IH_PedidoHistory_QB whereUpdatedAt($value)
     * @method PedidoHistory baseSole(array|string $columns = ['*'])
     * @method PedidoHistory create(array $attributes = [])
     * @method _IH_PedidoHistory_C|PedidoHistory[] cursor()
     * @method PedidoHistory|null|_IH_PedidoHistory_C|PedidoHistory[] find($id, array $columns = ['*'])
     * @method _IH_PedidoHistory_C|PedidoHistory[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PedidoHistory|_IH_PedidoHistory_C|PedidoHistory[] findOrFail($id, array $columns = ['*'])
     * @method PedidoHistory|_IH_PedidoHistory_C|PedidoHistory[] findOrNew($id, array $columns = ['*'])
     * @method PedidoHistory first(array|string $columns = ['*'])
     * @method PedidoHistory firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PedidoHistory firstOrCreate(array $attributes = [], array $values = [])
     * @method PedidoHistory firstOrFail(array $columns = ['*'])
     * @method PedidoHistory firstOrNew(array $attributes = [], array $values = [])
     * @method PedidoHistory firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PedidoHistory forceCreate(array $attributes)
     * @method _IH_PedidoHistory_C|PedidoHistory[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PedidoHistory_C|PedidoHistory[] get(array|string $columns = ['*'])
     * @method PedidoHistory getModel()
     * @method PedidoHistory[] getModels(array|string $columns = ['*'])
     * @method _IH_PedidoHistory_C|PedidoHistory[] hydrate(array $items)
     * @method PedidoHistory make(array $attributes = [])
     * @method PedidoHistory newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PedidoHistory[]|_IH_PedidoHistory_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PedidoHistory[]|_IH_PedidoHistory_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PedidoHistory sole(array|string $columns = ['*'])
     * @method PedidoHistory updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_PedidoHistory_QB extends _BaseBuilder {}
    
    /**
     * @method PedidoMotorizadoHistory|null getOrPut($key, $value)
     * @method PedidoMotorizadoHistory|$this shift(int $count = 1)
     * @method PedidoMotorizadoHistory|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PedidoMotorizadoHistory|$this pop(int $count = 1)
     * @method PedidoMotorizadoHistory|null pull($key, $default = null)
     * @method PedidoMotorizadoHistory|null last(callable $callback = null, $default = null)
     * @method PedidoMotorizadoHistory|$this random(int|null $number = null)
     * @method PedidoMotorizadoHistory|null sole($key = null, $operator = null, $value = null)
     * @method PedidoMotorizadoHistory|null get($key, $default = null)
     * @method PedidoMotorizadoHistory|null first(callable $callback = null, $default = null)
     * @method PedidoMotorizadoHistory|null firstWhere(string $key, $operator = null, $value = null)
     * @method PedidoMotorizadoHistory|null find($key, $default = null)
     * @method PedidoMotorizadoHistory[] all()
     */
    class _IH_PedidoMotorizadoHistory_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PedidoMotorizadoHistory[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PedidoMotorizadoHistory_QB whereId($value)
     * @method _IH_PedidoMotorizadoHistory_QB wherePedidoId($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereDireccionGrupoId($value)
     * @method _IH_PedidoMotorizadoHistory_QB wherePedidoGrupoId($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereStatus($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereSustentoText($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereSustentoFoto($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereCreatedAt($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereUpdatedAt($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereDeletedAt($value)
     * @method _IH_PedidoMotorizadoHistory_QB whereOldDireccionGrupoId($value)
     * @method PedidoMotorizadoHistory baseSole(array|string $columns = ['*'])
     * @method PedidoMotorizadoHistory create(array $attributes = [])
     * @method _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] cursor()
     * @method PedidoMotorizadoHistory|null|_IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] find($id, array $columns = ['*'])
     * @method _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PedidoMotorizadoHistory|_IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] findOrFail($id, array $columns = ['*'])
     * @method PedidoMotorizadoHistory|_IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] findOrNew($id, array $columns = ['*'])
     * @method PedidoMotorizadoHistory first(array|string $columns = ['*'])
     * @method PedidoMotorizadoHistory firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PedidoMotorizadoHistory firstOrCreate(array $attributes = [], array $values = [])
     * @method PedidoMotorizadoHistory firstOrFail(array $columns = ['*'])
     * @method PedidoMotorizadoHistory firstOrNew(array $attributes = [], array $values = [])
     * @method PedidoMotorizadoHistory firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PedidoMotorizadoHistory forceCreate(array $attributes)
     * @method _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] get(array|string $columns = ['*'])
     * @method PedidoMotorizadoHistory getModel()
     * @method PedidoMotorizadoHistory[] getModels(array|string $columns = ['*'])
     * @method _IH_PedidoMotorizadoHistory_C|PedidoMotorizadoHistory[] hydrate(array $items)
     * @method PedidoMotorizadoHistory make(array $attributes = [])
     * @method PedidoMotorizadoHistory newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PedidoMotorizadoHistory[]|_IH_PedidoMotorizadoHistory_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PedidoMotorizadoHistory[]|_IH_PedidoMotorizadoHistory_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PedidoMotorizadoHistory sole(array|string $columns = ['*'])
     * @method PedidoMotorizadoHistory updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_PedidoMotorizadoHistory_QB extends _BaseBuilder {}
    
    /**
     * @method PedidoMovimientoEstado|null getOrPut($key, $value)
     * @method PedidoMovimientoEstado|$this shift(int $count = 1)
     * @method PedidoMovimientoEstado|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PedidoMovimientoEstado|$this pop(int $count = 1)
     * @method PedidoMovimientoEstado|null pull($key, $default = null)
     * @method PedidoMovimientoEstado|null last(callable $callback = null, $default = null)
     * @method PedidoMovimientoEstado|$this random(int|null $number = null)
     * @method PedidoMovimientoEstado|null sole($key = null, $operator = null, $value = null)
     * @method PedidoMovimientoEstado|null get($key, $default = null)
     * @method PedidoMovimientoEstado|null first(callable $callback = null, $default = null)
     * @method PedidoMovimientoEstado|null firstWhere(string $key, $operator = null, $value = null)
     * @method PedidoMovimientoEstado|null find($key, $default = null)
     * @method PedidoMovimientoEstado[] all()
     */
    class _IH_PedidoMovimientoEstado_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PedidoMovimientoEstado[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PedidoMovimientoEstado_QB whereId($value)
     * @method _IH_PedidoMovimientoEstado_QB whereCondicionEnvioCode($value)
     * @method _IH_PedidoMovimientoEstado_QB whereFecha($value)
     * @method _IH_PedidoMovimientoEstado_QB wherePedido($value)
     * @method _IH_PedidoMovimientoEstado_QB whereCreatedAt($value)
     * @method _IH_PedidoMovimientoEstado_QB whereUpdatedAt($value)
     * @method _IH_PedidoMovimientoEstado_QB whereNotificado($value)
     * @method _IH_PedidoMovimientoEstado_QB whereJsonEnvio($value)
     * @method PedidoMovimientoEstado baseSole(array|string $columns = ['*'])
     * @method PedidoMovimientoEstado create(array $attributes = [])
     * @method _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] cursor()
     * @method PedidoMovimientoEstado|null|_IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] find($id, array $columns = ['*'])
     * @method _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PedidoMovimientoEstado|_IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] findOrFail($id, array $columns = ['*'])
     * @method PedidoMovimientoEstado|_IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] findOrNew($id, array $columns = ['*'])
     * @method PedidoMovimientoEstado first(array|string $columns = ['*'])
     * @method PedidoMovimientoEstado firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PedidoMovimientoEstado firstOrCreate(array $attributes = [], array $values = [])
     * @method PedidoMovimientoEstado firstOrFail(array $columns = ['*'])
     * @method PedidoMovimientoEstado firstOrNew(array $attributes = [], array $values = [])
     * @method PedidoMovimientoEstado firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PedidoMovimientoEstado forceCreate(array $attributes)
     * @method _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] get(array|string $columns = ['*'])
     * @method PedidoMovimientoEstado getModel()
     * @method PedidoMovimientoEstado[] getModels(array|string $columns = ['*'])
     * @method _IH_PedidoMovimientoEstado_C|PedidoMovimientoEstado[] hydrate(array $items)
     * @method PedidoMovimientoEstado make(array $attributes = [])
     * @method PedidoMovimientoEstado newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PedidoMovimientoEstado[]|_IH_PedidoMovimientoEstado_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PedidoMovimientoEstado[]|_IH_PedidoMovimientoEstado_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PedidoMovimientoEstado sole(array|string $columns = ['*'])
     * @method PedidoMovimientoEstado updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_PedidoMovimientoEstado_QB extends _BaseBuilder {}
    
    /**
     * @method Pedido|null getOrPut($key, $value)
     * @method Pedido|$this shift(int $count = 1)
     * @method Pedido|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Pedido|$this pop(int $count = 1)
     * @method Pedido|null pull($key, $default = null)
     * @method Pedido|null last(callable $callback = null, $default = null)
     * @method Pedido|$this random(int|null $number = null)
     * @method Pedido|null sole($key = null, $operator = null, $value = null)
     * @method Pedido|null get($key, $default = null)
     * @method Pedido|null first(callable $callback = null, $default = null)
     * @method Pedido|null firstWhere(string $key, $operator = null, $value = null)
     * @method Pedido|null find($key, $default = null)
     * @method Pedido[] all()
     */
    class _IH_Pedido_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Pedido[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Pedido_QB whereId($value)
     * @method _IH_Pedido_QB whereCorrelativo($value)
     * @method _IH_Pedido_QB whereClienteId($value)
     * @method _IH_Pedido_QB whereUserId($value)
     * @method _IH_Pedido_QB whereCreador($value)
     * @method _IH_Pedido_QB wherePago($value)
     * @method _IH_Pedido_QB wherePagado($value)
     * @method _IH_Pedido_QB whereDestino($value)
     * @method _IH_Pedido_QB whereTrecking($value)
     * @method _IH_Pedido_QB whereDireccion($value)
     * @method _IH_Pedido_QB whereCondicionEnvio($value)
     * @method _IH_Pedido_QB whereCondicionEnvioCode($value)
     * @method _IH_Pedido_QB whereCondicion($value)
     * @method _IH_Pedido_QB whereCondicionCode($value)
     * @method _IH_Pedido_QB whereCondicionInt($value)
     * @method _IH_Pedido_QB whereCodigo($value)
     * @method _IH_Pedido_QB whereNotificacion($value)
     * @method _IH_Pedido_QB whereMotivo($value)
     * @method _IH_Pedido_QB whereResponsable($value)
     * @method _IH_Pedido_QB whereModificador($value)
     * @method _IH_Pedido_QB whereDevuelto($value)
     * @method _IH_Pedido_QB whereCantDevuelto($value)
     * @method _IH_Pedido_QB whereObservacionDevuelto($value)
     * @method _IH_Pedido_QB whereEstado($value)
     * @method _IH_Pedido_QB whereDaConfirmarDescarga($value)
     * @method _IH_Pedido_QB whereSustentoAdjunto($value)
     * @method _IH_Pedido_QB wherePathAdjuntoAnular($value)
     * @method _IH_Pedido_QB wherePathAdjuntoAnularDisk($value)
     * @method _IH_Pedido_QB wherePendienteAnulacion($value)
     * @method _IH_Pedido_QB whereUserAnulacionId($value)
     * @method _IH_Pedido_QB whereFechaAnulacion($value)
     * @method _IH_Pedido_QB whereFechaAnulacionConfirm($value)
     * @method _IH_Pedido_QB whereFechaAnulacionDenegada($value)
     * @method _IH_Pedido_QB whereCreatedAt($value)
     * @method _IH_Pedido_QB whereUpdatedAt($value)
     * @method _IH_Pedido_QB whereReturnedAt($value)
     * @method _IH_Pedido_QB whereEnvio($value)
     * @method _IH_Pedido_QB whereEstadoCondicionEnvio($value)
     * @method _IH_Pedido_QB whereEstadoCondicionPedido($value)
     * @method _IH_Pedido_QB whereEstadoSobre($value)
     * @method _IH_Pedido_QB whereEnvDestino($value)
     * @method _IH_Pedido_QB whereEnvDistrito($value)
     * @method _IH_Pedido_QB whereEnvZona($value)
     * @method _IH_Pedido_QB whereEnvZonaAsignada($value)
     * @method _IH_Pedido_QB whereEnvNombreClienteRecibe($value)
     * @method _IH_Pedido_QB whereEnvCelularClienteRecibe($value)
     * @method _IH_Pedido_QB whereEnvCantidad($value)
     * @method _IH_Pedido_QB whereEnvDireccion($value)
     * @method _IH_Pedido_QB whereEnvTracking($value)
     * @method _IH_Pedido_QB whereEnvReferencia($value)
     * @method _IH_Pedido_QB whereEnvNumregistro($value)
     * @method _IH_Pedido_QB whereEnvRotulo($value)
     * @method _IH_Pedido_QB whereEnvObservacion($value)
     * @method _IH_Pedido_QB whereEnvImporte($value)
     * @method _IH_Pedido_QB whereEstadoRuta($value)
     * @method _IH_Pedido_QB whereDireccionGrupo($value)
     * @method _IH_Pedido_QB whereFechaSalida($value)
     * @method _IH_Pedido_QB whereCambioDireccionSustento($value)
     * @method _IH_Pedido_QB whereIdentificador($value)
     * @method _IH_Pedido_QB whereExidentificador($value)
     * @method _IH_Pedido_QB whereIcelularAsesor($value)
     * @method _IH_Pedido_QB whereIcelularCliente($value)
     * @method _IH_Pedido_QB whereFechaEnvioOpCourier($value)
     * @method _IH_Pedido_QB whereCelularCliente($value)
     * @method _IH_Pedido_QB whereCambioDireccionAt($value)
     * @method _IH_Pedido_QB whereEstadoConsinsobre($value)
     * @method _IH_Pedido_QB whereFechaEnvioAtendidoOp($value)
     * @method _IH_Pedido_QB whereCondicionEnvioAt($value)
     * @method _IH_Pedido_QB whereEnvGmlink($value)
     * @method _IH_Pedido_QB whereCourierSyncAt($value)
     * @method _IH_Pedido_QB whereCourierFailedSyncAt($value)
     * @method _IH_Pedido_QB whereCourierSyncFinalized($value)
     * @method _IH_Pedido_QB whereCourierEstado($value)
     * @method _IH_Pedido_QB whereCourierData($value)
     * @method _IH_Pedido_QB whereCondicionEnvioAnterior($value)
     * @method _IH_Pedido_QB whereCondicionEnvioCodeAnterior($value)
     * @method _IH_Pedido_QB whereCodigoAnterior($value)
     * @method _IH_Pedido_QB wherePedidoidAnterior($value)
     * @method _IH_Pedido_QB whereEnvSustento($value)
     * @method _IH_Pedido_QB whereEstadoCorreccion($value)
     * @method _IH_Pedido_QB whereUserClavepedido($value)
     * @method _IH_Pedido_QB whereUserReg($value)
     * @method Pedido baseSole(array|string $columns = ['*'])
     * @method Pedido create(array $attributes = [])
     * @method _IH_Pedido_C|Pedido[] cursor()
     * @method Pedido|null|_IH_Pedido_C|Pedido[] find($id, array $columns = ['*'])
     * @method _IH_Pedido_C|Pedido[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Pedido|_IH_Pedido_C|Pedido[] findOrFail($id, array $columns = ['*'])
     * @method Pedido|_IH_Pedido_C|Pedido[] findOrNew($id, array $columns = ['*'])
     * @method Pedido first(array|string $columns = ['*'])
     * @method Pedido firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Pedido firstOrCreate(array $attributes = [], array $values = [])
     * @method Pedido firstOrFail(array $columns = ['*'])
     * @method Pedido firstOrNew(array $attributes = [], array $values = [])
     * @method Pedido firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Pedido forceCreate(array $attributes)
     * @method _IH_Pedido_C|Pedido[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Pedido_C|Pedido[] get(array|string $columns = ['*'])
     * @method Pedido getModel()
     * @method Pedido[] getModels(array|string $columns = ['*'])
     * @method _IH_Pedido_C|Pedido[] hydrate(array $items)
     * @method Pedido make(array $attributes = [])
     * @method Pedido newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Pedido[]|_IH_Pedido_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Pedido[]|_IH_Pedido_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Pedido sole(array|string $columns = ['*'])
     * @method Pedido updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Pedido_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Pedido_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_Pedido_QB asesoresDeOperarios()
     * @method _IH_Pedido_QB atendidos()
     * @method _IH_Pedido_QB cantidad($cantidad)
     * @method _IH_Pedido_QB celularClienteRecibe($celularClienteRecibe)
     * @method _IH_Pedido_QB conDireccionEnvio()
     * @method _IH_Pedido_QB consultarecojo($celularClienteRecibe, $cantidad, $tracking, $referencia, $numRegistro, $rotulo, $observacion, $gmLink, $importe, $zona, $destino, $direction, $nombredecliente, $distrito)
     * @method _IH_Pedido_QB currentUser()
     * @method _IH_Pedido_QB destino($destino)
     * @method _IH_Pedido_QB direccion($direction)
     * @method _IH_Pedido_QB distrito($distrito)
     * @method _IH_Pedido_QB gmlink($gmLink)
     * @method _IH_Pedido_QB importe($importe)
     * @method _IH_Pedido_QB noPagados()
     * @method _IH_Pedido_QB noPendingAnulation()
     * @method _IH_Pedido_QB nombreClienteRecibe($nombredecliente)
     * @method _IH_Pedido_QB numregistro($numRegistro)
     * @method _IH_Pedido_QB observacion($observacion)
     * @method _IH_Pedido_QB pagados()
     * @method _IH_Pedido_QB pendienteAnulacion()
     * @method _IH_Pedido_QB porAtender()
     * @method _IH_Pedido_QB porAtenderEstatus()
     * @method _IH_Pedido_QB referencia($referencia)
     * @method _IH_Pedido_QB rotulo($rotulo)
     * @method _IH_Pedido_QB segunRolUsuario($roles = [])
     * @method _IH_Pedido_QB sinDireccionEnvio()
     * @method _IH_Pedido_QB sinZonaAsignadaEnvio()
     * @method _IH_Pedido_QB tracking($tracking)
     * @method _IH_Pedido_QB zonaAsignada()
     * @method _IH_Pedido_QB zonaAsignadaEnvio($zona)
     * @method _IH_Pedido_QB roladmin()
     * @method _IH_Pedido_QB rolasesor()
     * @method _IH_Pedido_QB rolencargado()
     * @method _IH_Pedido_QB roljefedellamada()
     * @method _IH_Pedido_QB rolllamada()
     */
    class _IH_Pedido_QB extends _BaseBuilder {}
    
    /**
     * @method PedidosAnulacion|null getOrPut($key, $value)
     * @method PedidosAnulacion|$this shift(int $count = 1)
     * @method PedidosAnulacion|null firstOrFail($key = null, $operator = null, $value = null)
     * @method PedidosAnulacion|$this pop(int $count = 1)
     * @method PedidosAnulacion|null pull($key, $default = null)
     * @method PedidosAnulacion|null last(callable $callback = null, $default = null)
     * @method PedidosAnulacion|$this random(int|null $number = null)
     * @method PedidosAnulacion|null sole($key = null, $operator = null, $value = null)
     * @method PedidosAnulacion|null get($key, $default = null)
     * @method PedidosAnulacion|null first(callable $callback = null, $default = null)
     * @method PedidosAnulacion|null firstWhere(string $key, $operator = null, $value = null)
     * @method PedidosAnulacion|null find($key, $default = null)
     * @method PedidosAnulacion[] all()
     */
    class _IH_PedidosAnulacion_C extends _BaseCollection {
        /**
         * @param int $size
         * @return PedidosAnulacion[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_PedidosAnulacion_QB whereId($value)
     * @method _IH_PedidosAnulacion_QB wherePedidoId($value)
     * @method _IH_PedidosAnulacion_QB whereUserIdAsesor($value)
     * @method _IH_PedidosAnulacion_QB whereMotivoSolicitud($value)
     * @method _IH_PedidosAnulacion_QB whereEstadoApruebaAsesor($value)
     * @method _IH_PedidosAnulacion_QB whereUserIdEncargado($value)
     * @method _IH_PedidosAnulacion_QB whereMotivoSolEncargado($value)
     * @method _IH_PedidosAnulacion_QB whereEstadoApruebaEncargado($value)
     * @method _IH_PedidosAnulacion_QB whereUserIdAdministrador($value)
     * @method _IH_PedidosAnulacion_QB whereMotivoSolAdmin($value)
     * @method _IH_PedidosAnulacion_QB whereEstadoApruebaAdministrador($value)
     * @method _IH_PedidosAnulacion_QB whereUserIdJefeop($value)
     * @method _IH_PedidosAnulacion_QB whereMotivoJefeopAdmin($value)
     * @method _IH_PedidosAnulacion_QB whereEstadoApruebaJefeop($value)
     * @method _IH_PedidosAnulacion_QB whereCreatedAt($value)
     * @method _IH_PedidosAnulacion_QB whereUpdatedAt($value)
     * @method _IH_PedidosAnulacion_QB whereTotalAnular($value)
     * @method _IH_PedidosAnulacion_QB whereTipo($value)
     * @method _IH_PedidosAnulacion_QB whereFilesAsesorIds($value)
     * @method _IH_PedidosAnulacion_QB whereFilesEncargadoIds($value)
     * @method _IH_PedidosAnulacion_QB whereFilesadminIds($value)
     * @method _IH_PedidosAnulacion_QB whereFilesJefeopIds($value)
     * @method _IH_PedidosAnulacion_QB whereStateSolicitud($value)
     * @method _IH_PedidosAnulacion_QB whereResposableCreateAsesor($value)
     * @method _IH_PedidosAnulacion_QB whereResposableAprobEncargado($value)
     * @method _IH_PedidosAnulacion_QB whereFilesResponsableAsesor($value)
     * @method _IH_PedidosAnulacion_QB whereResposableAprobAdmin($value)
     * @method _IH_PedidosAnulacion_QB whereCantidad($value)
     * @method _IH_PedidosAnulacion_QB whereCantidadResta($value)
     * @method _IH_PedidosAnulacion_QB whereDifanterior($value)
     * @method PedidosAnulacion baseSole(array|string $columns = ['*'])
     * @method PedidosAnulacion create(array $attributes = [])
     * @method _IH_PedidosAnulacion_C|PedidosAnulacion[] cursor()
     * @method PedidosAnulacion|null|_IH_PedidosAnulacion_C|PedidosAnulacion[] find($id, array $columns = ['*'])
     * @method _IH_PedidosAnulacion_C|PedidosAnulacion[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method PedidosAnulacion|_IH_PedidosAnulacion_C|PedidosAnulacion[] findOrFail($id, array $columns = ['*'])
     * @method PedidosAnulacion|_IH_PedidosAnulacion_C|PedidosAnulacion[] findOrNew($id, array $columns = ['*'])
     * @method PedidosAnulacion first(array|string $columns = ['*'])
     * @method PedidosAnulacion firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method PedidosAnulacion firstOrCreate(array $attributes = [], array $values = [])
     * @method PedidosAnulacion firstOrFail(array $columns = ['*'])
     * @method PedidosAnulacion firstOrNew(array $attributes = [], array $values = [])
     * @method PedidosAnulacion firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method PedidosAnulacion forceCreate(array $attributes)
     * @method _IH_PedidosAnulacion_C|PedidosAnulacion[] fromQuery(string $query, array $bindings = [])
     * @method _IH_PedidosAnulacion_C|PedidosAnulacion[] get(array|string $columns = ['*'])
     * @method PedidosAnulacion getModel()
     * @method PedidosAnulacion[] getModels(array|string $columns = ['*'])
     * @method _IH_PedidosAnulacion_C|PedidosAnulacion[] hydrate(array $items)
     * @method PedidosAnulacion make(array $attributes = [])
     * @method PedidosAnulacion newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|PedidosAnulacion[]|_IH_PedidosAnulacion_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|PedidosAnulacion[]|_IH_PedidosAnulacion_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method PedidosAnulacion sole(array|string $columns = ['*'])
     * @method PedidosAnulacion updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_PedidosAnulacion_QB extends _BaseBuilder {}
    
    /**
     * @method Porcentaje|null getOrPut($key, $value)
     * @method Porcentaje|$this shift(int $count = 1)
     * @method Porcentaje|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Porcentaje|$this pop(int $count = 1)
     * @method Porcentaje|null pull($key, $default = null)
     * @method Porcentaje|null last(callable $callback = null, $default = null)
     * @method Porcentaje|$this random(int|null $number = null)
     * @method Porcentaje|null sole($key = null, $operator = null, $value = null)
     * @method Porcentaje|null get($key, $default = null)
     * @method Porcentaje|null first(callable $callback = null, $default = null)
     * @method Porcentaje|null firstWhere(string $key, $operator = null, $value = null)
     * @method Porcentaje|null find($key, $default = null)
     * @method Porcentaje[] all()
     */
    class _IH_Porcentaje_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Porcentaje[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Porcentaje_QB whereId($value)
     * @method _IH_Porcentaje_QB whereClienteId($value)
     * @method _IH_Porcentaje_QB whereNombre($value)
     * @method _IH_Porcentaje_QB wherePorcentaje($value)
     * @method _IH_Porcentaje_QB whereCreatedAt($value)
     * @method _IH_Porcentaje_QB whereUpdatedAt($value)
     * @method Porcentaje baseSole(array|string $columns = ['*'])
     * @method Porcentaje create(array $attributes = [])
     * @method _IH_Porcentaje_C|Porcentaje[] cursor()
     * @method Porcentaje|null|_IH_Porcentaje_C|Porcentaje[] find($id, array $columns = ['*'])
     * @method _IH_Porcentaje_C|Porcentaje[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Porcentaje|_IH_Porcentaje_C|Porcentaje[] findOrFail($id, array $columns = ['*'])
     * @method Porcentaje|_IH_Porcentaje_C|Porcentaje[] findOrNew($id, array $columns = ['*'])
     * @method Porcentaje first(array|string $columns = ['*'])
     * @method Porcentaje firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Porcentaje firstOrCreate(array $attributes = [], array $values = [])
     * @method Porcentaje firstOrFail(array $columns = ['*'])
     * @method Porcentaje firstOrNew(array $attributes = [], array $values = [])
     * @method Porcentaje firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Porcentaje forceCreate(array $attributes)
     * @method _IH_Porcentaje_C|Porcentaje[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Porcentaje_C|Porcentaje[] get(array|string $columns = ['*'])
     * @method Porcentaje getModel()
     * @method Porcentaje[] getModels(array|string $columns = ['*'])
     * @method _IH_Porcentaje_C|Porcentaje[] hydrate(array $items)
     * @method Porcentaje make(array $attributes = [])
     * @method Porcentaje newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Porcentaje[]|_IH_Porcentaje_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Porcentaje[]|_IH_Porcentaje_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Porcentaje sole(array|string $columns = ['*'])
     * @method Porcentaje updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Porcentaje_QB extends _BaseBuilder {}
    
    /**
     * @method Provincia|null getOrPut($key, $value)
     * @method Provincia|$this shift(int $count = 1)
     * @method Provincia|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Provincia|$this pop(int $count = 1)
     * @method Provincia|null pull($key, $default = null)
     * @method Provincia|null last(callable $callback = null, $default = null)
     * @method Provincia|$this random(int|null $number = null)
     * @method Provincia|null sole($key = null, $operator = null, $value = null)
     * @method Provincia|null get($key, $default = null)
     * @method Provincia|null first(callable $callback = null, $default = null)
     * @method Provincia|null firstWhere(string $key, $operator = null, $value = null)
     * @method Provincia|null find($key, $default = null)
     * @method Provincia[] all()
     */
    class _IH_Provincia_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Provincia[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Provincia baseSole(array|string $columns = ['*'])
     * @method Provincia create(array $attributes = [])
     * @method _IH_Provincia_C|Provincia[] cursor()
     * @method Provincia|null|_IH_Provincia_C|Provincia[] find($id, array $columns = ['*'])
     * @method _IH_Provincia_C|Provincia[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Provincia|_IH_Provincia_C|Provincia[] findOrFail($id, array $columns = ['*'])
     * @method Provincia|_IH_Provincia_C|Provincia[] findOrNew($id, array $columns = ['*'])
     * @method Provincia first(array|string $columns = ['*'])
     * @method Provincia firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Provincia firstOrCreate(array $attributes = [], array $values = [])
     * @method Provincia firstOrFail(array $columns = ['*'])
     * @method Provincia firstOrNew(array $attributes = [], array $values = [])
     * @method Provincia firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Provincia forceCreate(array $attributes)
     * @method _IH_Provincia_C|Provincia[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Provincia_C|Provincia[] get(array|string $columns = ['*'])
     * @method Provincia getModel()
     * @method Provincia[] getModels(array|string $columns = ['*'])
     * @method _IH_Provincia_C|Provincia[] hydrate(array $items)
     * @method Provincia make(array $attributes = [])
     * @method Provincia newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Provincia[]|_IH_Provincia_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Provincia[]|_IH_Provincia_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Provincia sole(array|string $columns = ['*'])
     * @method Provincia updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Provincia_QB extends _BaseBuilder {}
    
    /**
     * @method Ruc|null getOrPut($key, $value)
     * @method Ruc|$this shift(int $count = 1)
     * @method Ruc|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Ruc|$this pop(int $count = 1)
     * @method Ruc|null pull($key, $default = null)
     * @method Ruc|null last(callable $callback = null, $default = null)
     * @method Ruc|$this random(int|null $number = null)
     * @method Ruc|null sole($key = null, $operator = null, $value = null)
     * @method Ruc|null get($key, $default = null)
     * @method Ruc|null first(callable $callback = null, $default = null)
     * @method Ruc|null firstWhere(string $key, $operator = null, $value = null)
     * @method Ruc|null find($key, $default = null)
     * @method Ruc[] all()
     */
    class _IH_Ruc_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Ruc[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Ruc_QB whereId($value)
     * @method _IH_Ruc_QB whereNumRuc($value)
     * @method _IH_Ruc_QB whereUserId($value)
     * @method _IH_Ruc_QB whereClienteId($value)
     * @method _IH_Ruc_QB whereEmpresa($value)
     * @method _IH_Ruc_QB whereEstado($value)
     * @method _IH_Ruc_QB whereCreatedAt($value)
     * @method _IH_Ruc_QB whereUpdatedAt($value)
     * @method _IH_Ruc_QB wherePorcentaje($value)
     * @method Ruc baseSole(array|string $columns = ['*'])
     * @method Ruc create(array $attributes = [])
     * @method _IH_Ruc_C|Ruc[] cursor()
     * @method Ruc|null|_IH_Ruc_C|Ruc[] find($id, array $columns = ['*'])
     * @method _IH_Ruc_C|Ruc[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Ruc|_IH_Ruc_C|Ruc[] findOrFail($id, array $columns = ['*'])
     * @method Ruc|_IH_Ruc_C|Ruc[] findOrNew($id, array $columns = ['*'])
     * @method Ruc first(array|string $columns = ['*'])
     * @method Ruc firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Ruc firstOrCreate(array $attributes = [], array $values = [])
     * @method Ruc firstOrFail(array $columns = ['*'])
     * @method Ruc firstOrNew(array $attributes = [], array $values = [])
     * @method Ruc firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Ruc forceCreate(array $attributes)
     * @method _IH_Ruc_C|Ruc[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Ruc_C|Ruc[] get(array|string $columns = ['*'])
     * @method Ruc getModel()
     * @method Ruc[] getModels(array|string $columns = ['*'])
     * @method _IH_Ruc_C|Ruc[] hydrate(array $items)
     * @method Ruc make(array $attributes = [])
     * @method Ruc newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Ruc[]|_IH_Ruc_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Ruc[]|_IH_Ruc_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Ruc sole(array|string $columns = ['*'])
     * @method Ruc updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Ruc_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_Ruc_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_Ruc_QB extends _BaseBuilder {}
    
    /**
     * @method SituacionClientes|null getOrPut($key, $value)
     * @method SituacionClientes|$this shift(int $count = 1)
     * @method SituacionClientes|null firstOrFail($key = null, $operator = null, $value = null)
     * @method SituacionClientes|$this pop(int $count = 1)
     * @method SituacionClientes|null pull($key, $default = null)
     * @method SituacionClientes|null last(callable $callback = null, $default = null)
     * @method SituacionClientes|$this random(int|null $number = null)
     * @method SituacionClientes|null sole($key = null, $operator = null, $value = null)
     * @method SituacionClientes|null get($key, $default = null)
     * @method SituacionClientes|null first(callable $callback = null, $default = null)
     * @method SituacionClientes|null firstWhere(string $key, $operator = null, $value = null)
     * @method SituacionClientes|null find($key, $default = null)
     * @method SituacionClientes[] all()
     */
    class _IH_SituacionClientes_C extends _BaseCollection {
        /**
         * @param int $size
         * @return SituacionClientes[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_SituacionClientes_QB whereId($value)
     * @method _IH_SituacionClientes_QB whereClienteId($value)
     * @method _IH_SituacionClientes_QB whereSituacion($value)
     * @method _IH_SituacionClientes_QB whereFecha($value)
     * @method _IH_SituacionClientes_QB whereCantidadPedidos($value)
     * @method _IH_SituacionClientes_QB whereCreatedAt($value)
     * @method _IH_SituacionClientes_QB whereUpdatedAt($value)
     * @method _IH_SituacionClientes_QB whereFlagFp($value)
     * @method _IH_SituacionClientes_QB whereUserId($value)
     * @method _IH_SituacionClientes_QB whereUserIdentificador($value)
     * @method _IH_SituacionClientes_QB whereUserClavepedido($value)
     * @method SituacionClientes baseSole(array|string $columns = ['*'])
     * @method SituacionClientes create(array $attributes = [])
     * @method _IH_SituacionClientes_C|SituacionClientes[] cursor()
     * @method SituacionClientes|null|_IH_SituacionClientes_C|SituacionClientes[] find($id, array $columns = ['*'])
     * @method _IH_SituacionClientes_C|SituacionClientes[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method SituacionClientes|_IH_SituacionClientes_C|SituacionClientes[] findOrFail($id, array $columns = ['*'])
     * @method SituacionClientes|_IH_SituacionClientes_C|SituacionClientes[] findOrNew($id, array $columns = ['*'])
     * @method SituacionClientes first(array|string $columns = ['*'])
     * @method SituacionClientes firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method SituacionClientes firstOrCreate(array $attributes = [], array $values = [])
     * @method SituacionClientes firstOrFail(array $columns = ['*'])
     * @method SituacionClientes firstOrNew(array $attributes = [], array $values = [])
     * @method SituacionClientes firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method SituacionClientes forceCreate(array $attributes)
     * @method _IH_SituacionClientes_C|SituacionClientes[] fromQuery(string $query, array $bindings = [])
     * @method _IH_SituacionClientes_C|SituacionClientes[] get(array|string $columns = ['*'])
     * @method SituacionClientes getModel()
     * @method SituacionClientes[] getModels(array|string $columns = ['*'])
     * @method _IH_SituacionClientes_C|SituacionClientes[] hydrate(array $items)
     * @method SituacionClientes make(array $attributes = [])
     * @method SituacionClientes newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|SituacionClientes[]|_IH_SituacionClientes_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|SituacionClientes[]|_IH_SituacionClientes_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method SituacionClientes sole(array|string $columns = ['*'])
     * @method SituacionClientes updateOrCreate(array $attributes, array $values = [])
     * @method _IH_SituacionClientes_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_SituacionClientes_QB activoJoin($table, $estado = 1, $boolean = 'and')
     */
    class _IH_SituacionClientes_QB extends _BaseBuilder {}
    
    /**
     * @method TeamInvitation|null getOrPut($key, $value)
     * @method TeamInvitation|$this shift(int $count = 1)
     * @method TeamInvitation|null firstOrFail($key = null, $operator = null, $value = null)
     * @method TeamInvitation|$this pop(int $count = 1)
     * @method TeamInvitation|null pull($key, $default = null)
     * @method TeamInvitation|null last(callable $callback = null, $default = null)
     * @method TeamInvitation|$this random(int|null $number = null)
     * @method TeamInvitation|null sole($key = null, $operator = null, $value = null)
     * @method TeamInvitation|null get($key, $default = null)
     * @method TeamInvitation|null first(callable $callback = null, $default = null)
     * @method TeamInvitation|null firstWhere(string $key, $operator = null, $value = null)
     * @method TeamInvitation|null find($key, $default = null)
     * @method TeamInvitation[] all()
     */
    class _IH_TeamInvitation_C extends _BaseCollection {
        /**
         * @param int $size
         * @return TeamInvitation[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method TeamInvitation baseSole(array|string $columns = ['*'])
     * @method TeamInvitation create(array $attributes = [])
     * @method _IH_TeamInvitation_C|TeamInvitation[] cursor()
     * @method TeamInvitation|null|_IH_TeamInvitation_C|TeamInvitation[] find($id, array $columns = ['*'])
     * @method _IH_TeamInvitation_C|TeamInvitation[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method TeamInvitation|_IH_TeamInvitation_C|TeamInvitation[] findOrFail($id, array $columns = ['*'])
     * @method TeamInvitation|_IH_TeamInvitation_C|TeamInvitation[] findOrNew($id, array $columns = ['*'])
     * @method TeamInvitation first(array|string $columns = ['*'])
     * @method TeamInvitation firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method TeamInvitation firstOrCreate(array $attributes = [], array $values = [])
     * @method TeamInvitation firstOrFail(array $columns = ['*'])
     * @method TeamInvitation firstOrNew(array $attributes = [], array $values = [])
     * @method TeamInvitation firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method TeamInvitation forceCreate(array $attributes)
     * @method _IH_TeamInvitation_C|TeamInvitation[] fromQuery(string $query, array $bindings = [])
     * @method _IH_TeamInvitation_C|TeamInvitation[] get(array|string $columns = ['*'])
     * @method TeamInvitation getModel()
     * @method TeamInvitation[] getModels(array|string $columns = ['*'])
     * @method _IH_TeamInvitation_C|TeamInvitation[] hydrate(array $items)
     * @method TeamInvitation make(array $attributes = [])
     * @method TeamInvitation newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|TeamInvitation[]|_IH_TeamInvitation_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|TeamInvitation[]|_IH_TeamInvitation_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method TeamInvitation sole(array|string $columns = ['*'])
     * @method TeamInvitation updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_TeamInvitation_QB extends _BaseBuilder {}
    
    /**
     * @method Team|null getOrPut($key, $value)
     * @method Team|$this shift(int $count = 1)
     * @method Team|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Team|$this pop(int $count = 1)
     * @method Team|null pull($key, $default = null)
     * @method Team|null last(callable $callback = null, $default = null)
     * @method Team|$this random(int|null $number = null)
     * @method Team|null sole($key = null, $operator = null, $value = null)
     * @method Team|null get($key, $default = null)
     * @method Team|null first(callable $callback = null, $default = null)
     * @method Team|null firstWhere(string $key, $operator = null, $value = null)
     * @method Team|null find($key, $default = null)
     * @method Team[] all()
     */
    class _IH_Team_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Team[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Team baseSole(array|string $columns = ['*'])
     * @method Team create(array $attributes = [])
     * @method _IH_Team_C|Team[] cursor()
     * @method Team|null|_IH_Team_C|Team[] find($id, array $columns = ['*'])
     * @method _IH_Team_C|Team[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Team|_IH_Team_C|Team[] findOrFail($id, array $columns = ['*'])
     * @method Team|_IH_Team_C|Team[] findOrNew($id, array $columns = ['*'])
     * @method Team first(array|string $columns = ['*'])
     * @method Team firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Team firstOrCreate(array $attributes = [], array $values = [])
     * @method Team firstOrFail(array $columns = ['*'])
     * @method Team firstOrNew(array $attributes = [], array $values = [])
     * @method Team firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Team forceCreate(array $attributes)
     * @method _IH_Team_C|Team[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Team_C|Team[] get(array|string $columns = ['*'])
     * @method Team getModel()
     * @method Team[] getModels(array|string $columns = ['*'])
     * @method _IH_Team_C|Team[] hydrate(array $items)
     * @method Team make(array $attributes = [])
     * @method Team newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Team[]|_IH_Team_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Team[]|_IH_Team_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Team sole(array|string $columns = ['*'])
     * @method Team updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Team_QB extends _BaseBuilder {}
    
    /**
     * @method TipoMovimiento|null getOrPut($key, $value)
     * @method TipoMovimiento|$this shift(int $count = 1)
     * @method TipoMovimiento|null firstOrFail($key = null, $operator = null, $value = null)
     * @method TipoMovimiento|$this pop(int $count = 1)
     * @method TipoMovimiento|null pull($key, $default = null)
     * @method TipoMovimiento|null last(callable $callback = null, $default = null)
     * @method TipoMovimiento|$this random(int|null $number = null)
     * @method TipoMovimiento|null sole($key = null, $operator = null, $value = null)
     * @method TipoMovimiento|null get($key, $default = null)
     * @method TipoMovimiento|null first(callable $callback = null, $default = null)
     * @method TipoMovimiento|null firstWhere(string $key, $operator = null, $value = null)
     * @method TipoMovimiento|null find($key, $default = null)
     * @method TipoMovimiento[] all()
     */
    class _IH_TipoMovimiento_C extends _BaseCollection {
        /**
         * @param int $size
         * @return TipoMovimiento[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_TipoMovimiento_QB whereId($value)
     * @method _IH_TipoMovimiento_QB whereDescripcion($value)
     * @method _IH_TipoMovimiento_QB whereBanco($value)
     * @method TipoMovimiento baseSole(array|string $columns = ['*'])
     * @method TipoMovimiento create(array $attributes = [])
     * @method _IH_TipoMovimiento_C|TipoMovimiento[] cursor()
     * @method TipoMovimiento|null|_IH_TipoMovimiento_C|TipoMovimiento[] find($id, array $columns = ['*'])
     * @method _IH_TipoMovimiento_C|TipoMovimiento[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method TipoMovimiento|_IH_TipoMovimiento_C|TipoMovimiento[] findOrFail($id, array $columns = ['*'])
     * @method TipoMovimiento|_IH_TipoMovimiento_C|TipoMovimiento[] findOrNew($id, array $columns = ['*'])
     * @method TipoMovimiento first(array|string $columns = ['*'])
     * @method TipoMovimiento firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method TipoMovimiento firstOrCreate(array $attributes = [], array $values = [])
     * @method TipoMovimiento firstOrFail(array $columns = ['*'])
     * @method TipoMovimiento firstOrNew(array $attributes = [], array $values = [])
     * @method TipoMovimiento firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method TipoMovimiento forceCreate(array $attributes)
     * @method _IH_TipoMovimiento_C|TipoMovimiento[] fromQuery(string $query, array $bindings = [])
     * @method _IH_TipoMovimiento_C|TipoMovimiento[] get(array|string $columns = ['*'])
     * @method TipoMovimiento getModel()
     * @method TipoMovimiento[] getModels(array|string $columns = ['*'])
     * @method _IH_TipoMovimiento_C|TipoMovimiento[] hydrate(array $items)
     * @method TipoMovimiento make(array $attributes = [])
     * @method TipoMovimiento newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|TipoMovimiento[]|_IH_TipoMovimiento_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|TipoMovimiento[]|_IH_TipoMovimiento_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method TipoMovimiento sole(array|string $columns = ['*'])
     * @method TipoMovimiento updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_TipoMovimiento_QB extends _BaseBuilder {}
    
    /**
     * @method Titular|null getOrPut($key, $value)
     * @method Titular|$this shift(int $count = 1)
     * @method Titular|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Titular|$this pop(int $count = 1)
     * @method Titular|null pull($key, $default = null)
     * @method Titular|null last(callable $callback = null, $default = null)
     * @method Titular|$this random(int|null $number = null)
     * @method Titular|null sole($key = null, $operator = null, $value = null)
     * @method Titular|null get($key, $default = null)
     * @method Titular|null first(callable $callback = null, $default = null)
     * @method Titular|null firstWhere(string $key, $operator = null, $value = null)
     * @method Titular|null find($key, $default = null)
     * @method Titular[] all()
     */
    class _IH_Titular_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Titular[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method Titular baseSole(array|string $columns = ['*'])
     * @method Titular create(array $attributes = [])
     * @method _IH_Titular_C|Titular[] cursor()
     * @method Titular|null|_IH_Titular_C|Titular[] find($id, array $columns = ['*'])
     * @method _IH_Titular_C|Titular[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Titular|_IH_Titular_C|Titular[] findOrFail($id, array $columns = ['*'])
     * @method Titular|_IH_Titular_C|Titular[] findOrNew($id, array $columns = ['*'])
     * @method Titular first(array|string $columns = ['*'])
     * @method Titular firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Titular firstOrCreate(array $attributes = [], array $values = [])
     * @method Titular firstOrFail(array $columns = ['*'])
     * @method Titular firstOrNew(array $attributes = [], array $values = [])
     * @method Titular firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Titular forceCreate(array $attributes)
     * @method _IH_Titular_C|Titular[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Titular_C|Titular[] get(array|string $columns = ['*'])
     * @method Titular getModel()
     * @method Titular[] getModels(array|string $columns = ['*'])
     * @method _IH_Titular_C|Titular[] hydrate(array $items)
     * @method Titular make(array $attributes = [])
     * @method Titular newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Titular[]|_IH_Titular_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Titular[]|_IH_Titular_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Titular sole(array|string $columns = ['*'])
     * @method Titular updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Titular_QB extends _BaseBuilder {}
    
    /**
     * @method UpdateMovimiento|null getOrPut($key, $value)
     * @method UpdateMovimiento|$this shift(int $count = 1)
     * @method UpdateMovimiento|null firstOrFail($key = null, $operator = null, $value = null)
     * @method UpdateMovimiento|$this pop(int $count = 1)
     * @method UpdateMovimiento|null pull($key, $default = null)
     * @method UpdateMovimiento|null last(callable $callback = null, $default = null)
     * @method UpdateMovimiento|$this random(int|null $number = null)
     * @method UpdateMovimiento|null sole($key = null, $operator = null, $value = null)
     * @method UpdateMovimiento|null get($key, $default = null)
     * @method UpdateMovimiento|null first(callable $callback = null, $default = null)
     * @method UpdateMovimiento|null firstWhere(string $key, $operator = null, $value = null)
     * @method UpdateMovimiento|null find($key, $default = null)
     * @method UpdateMovimiento[] all()
     */
    class _IH_UpdateMovimiento_C extends _BaseCollection {
        /**
         * @param int $size
         * @return UpdateMovimiento[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_UpdateMovimiento_QB whereId($value)
     * @method _IH_UpdateMovimiento_QB whereObs($value)
     * @method _IH_UpdateMovimiento_QB whereValoresAnt($value)
     * @method _IH_UpdateMovimiento_QB whereValoresAct($value)
     * @method _IH_UpdateMovimiento_QB whereFechaCreacion($value)
     * @method _IH_UpdateMovimiento_QB whereCreatedAt($value)
     * @method _IH_UpdateMovimiento_QB whereUpdatedAt($value)
     * @method UpdateMovimiento baseSole(array|string $columns = ['*'])
     * @method UpdateMovimiento create(array $attributes = [])
     * @method _IH_UpdateMovimiento_C|UpdateMovimiento[] cursor()
     * @method UpdateMovimiento|null|_IH_UpdateMovimiento_C|UpdateMovimiento[] find($id, array $columns = ['*'])
     * @method _IH_UpdateMovimiento_C|UpdateMovimiento[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method UpdateMovimiento|_IH_UpdateMovimiento_C|UpdateMovimiento[] findOrFail($id, array $columns = ['*'])
     * @method UpdateMovimiento|_IH_UpdateMovimiento_C|UpdateMovimiento[] findOrNew($id, array $columns = ['*'])
     * @method UpdateMovimiento first(array|string $columns = ['*'])
     * @method UpdateMovimiento firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method UpdateMovimiento firstOrCreate(array $attributes = [], array $values = [])
     * @method UpdateMovimiento firstOrFail(array $columns = ['*'])
     * @method UpdateMovimiento firstOrNew(array $attributes = [], array $values = [])
     * @method UpdateMovimiento firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method UpdateMovimiento forceCreate(array $attributes)
     * @method _IH_UpdateMovimiento_C|UpdateMovimiento[] fromQuery(string $query, array $bindings = [])
     * @method _IH_UpdateMovimiento_C|UpdateMovimiento[] get(array|string $columns = ['*'])
     * @method UpdateMovimiento getModel()
     * @method UpdateMovimiento[] getModels(array|string $columns = ['*'])
     * @method _IH_UpdateMovimiento_C|UpdateMovimiento[] hydrate(array $items)
     * @method UpdateMovimiento make(array $attributes = [])
     * @method UpdateMovimiento newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|UpdateMovimiento[]|_IH_UpdateMovimiento_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|UpdateMovimiento[]|_IH_UpdateMovimiento_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method UpdateMovimiento sole(array|string $columns = ['*'])
     * @method UpdateMovimiento updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_UpdateMovimiento_QB extends _BaseBuilder {}
    
    /**
     * @method User|null getOrPut($key, $value)
     * @method User|$this shift(int $count = 1)
     * @method User|null firstOrFail($key = null, $operator = null, $value = null)
     * @method User|$this pop(int $count = 1)
     * @method User|null pull($key, $default = null)
     * @method User|null last(callable $callback = null, $default = null)
     * @method User|$this random(int|null $number = null)
     * @method User|null sole($key = null, $operator = null, $value = null)
     * @method User|null get($key, $default = null)
     * @method User|null first(callable $callback = null, $default = null)
     * @method User|null firstWhere(string $key, $operator = null, $value = null)
     * @method User|null find($key, $default = null)
     * @method User[] all()
     */
    class _IH_User_C extends _BaseCollection {
        /**
         * @param int $size
         * @return User[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_User_QB whereId($value)
     * @method _IH_User_QB whereName($value)
     * @method _IH_User_QB whereEmail($value)
     * @method _IH_User_QB whereEmailVerifiedAt($value)
     * @method _IH_User_QB wherePassword($value)
     * @method _IH_User_QB whereTwoFactorSecret($value)
     * @method _IH_User_QB whereTwoFactorRecoveryCodes($value)
     * @method _IH_User_QB whereRememberToken($value)
     * @method _IH_User_QB whereLetra($value)
     * @method _IH_User_QB whereEstado($value)
     * @method _IH_User_QB whereRol($value)
     * @method _IH_User_QB whereSupervisor($value)
     * @method _IH_User_QB whereOperario($value)
     * @method _IH_User_QB whereLlamada($value)
     * @method _IH_User_QB whereJefe($value)
     * @method _IH_User_QB whereIdentificador($value)
     * @method _IH_User_QB whereExidentificador($value)
     * @method _IH_User_QB whereUnificado($value)
     * @method _IH_User_QB whereMetaPedido($value)
     * @method _IH_User_QB whereMetaCobro($value)
     * @method _IH_User_QB whereCelular($value)
     * @method _IH_User_QB whereProvincia($value)
     * @method _IH_User_QB whereDistrito($value)
     * @method _IH_User_QB whereDireccion($value)
     * @method _IH_User_QB whereReferencia($value)
     * @method _IH_User_QB whereCurrentTeamId($value)
     * @method _IH_User_QB whereProfilePhotoPath($value)
     * @method _IH_User_QB whereExcluirMeta($value)
     * @method _IH_User_QB whereZona($value)
     * @method _IH_User_QB whereCreatedAt($value)
     * @method _IH_User_QB whereUpdatedAt($value)
     * @method _IH_User_QB whereMetaPedido2($value)
     * @method _IH_User_QB whereVidasTotal($value)
     * @method _IH_User_QB whereVidasRestantes($value)
     * @method _IH_User_QB whereCantVidasCero($value)
     * @method _IH_User_QB whereMetaQuincena($value)
     * @method _IH_User_QB whereBirthday($value)
     * @method _IH_User_QB whereClavePedidos($value)
     * @method User baseSole(array|string $columns = ['*'])
     * @method User create(array $attributes = [])
     * @method _IH_User_C|User[] cursor()
     * @method User|null|_IH_User_C|User[] find($id, array $columns = ['*'])
     * @method _IH_User_C|User[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method User|_IH_User_C|User[] findOrFail($id, array $columns = ['*'])
     * @method User|_IH_User_C|User[] findOrNew($id, array $columns = ['*'])
     * @method User first(array|string $columns = ['*'])
     * @method User firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method User firstOrCreate(array $attributes = [], array $values = [])
     * @method User firstOrFail(array $columns = ['*'])
     * @method User firstOrNew(array $attributes = [], array $values = [])
     * @method User firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method User forceCreate(array $attributes)
     * @method _IH_User_C|User[] fromQuery(string $query, array $bindings = [])
     * @method _IH_User_C|User[] get(array|string $columns = ['*'])
     * @method User getModel()
     * @method User[] getModels(array|string $columns = ['*'])
     * @method _IH_User_C|User[] hydrate(array $items)
     * @method User make(array $attributes = [])
     * @method User newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|User[]|_IH_User_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|User[]|_IH_User_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method User sole(array|string $columns = ['*'])
     * @method User updateOrCreate(array $attributes, array $values = [])
     * @method _IH_User_QB activo($estado = 1, $boolean = 'and')
     * @method _IH_User_QB activoJoin($table, $estado = 1, $boolean = 'and')
     * @method _IH_User_QB incluidoMeta()
     * @method _IH_User_QB permission(array|Collection|int|Permission|string $permissions)
     * @method _IH_User_QB rol($rol)
     * @method _IH_User_QB rolAllAsesor()
     * @method _IH_User_QB rolAsesor()
     * @method _IH_User_QB rolSupervisor()
     * @method _IH_User_QB role(array|Collection|int|Role|string $roles, string $guard = null)
     */
    class _IH_User_QB extends _BaseBuilder {}
}