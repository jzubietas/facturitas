<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable {
        unreadNotifications as unreadNotificationsLimits;
    }
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;

    use HasRoles;
    use CommonModel;


    const ROL_ADMIN = "Administrador";
    const ROL_ASESOR_ADMINISTRATIVO = "ASESOR ADMINISTRATIVO";
    const ROL_JEFE_OPERARIO = "Jefe de operaciones";
    const ROL_JEFE_COURIER = "Jefe de courier";
    const ROL_OPERARIO = "Operario";
    const ROL_ENCARGADO = "Encargado";
    const ROL_LLAMADAS = "Llamadas";
    const ROL_COBRANZAS = "COBRANZAS";
    const ROL_ASESOR = "Asesor";
    const ROL_LOGISTICA = "Logística";
    const ROL_JEFE_LLAMADAS = "Jefe de llamadas";
    const ROL_ASISTENTE_PAGOS = "Asistente de Pagos";
    const ROL_APOYO_ADMINISTRATIVO = "Apoyo administrativo";
    const ROL_FORMACION = "FORMACIÓN";
    const ROL_MOTORIZADO = "MOTORIZADO";
    const ROL_PRESENTACION = "PRESENTACION";




    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'identificador',
        'exidentificador',
        'estado',
        'equipo',
        'meta_pedido',
        'meta_pedido_2',
        'meta_cobro',
        'supervisor',
        'operario',
        'llamada',
        'jefe',
        'celular',
        'provincia',
        'distrito',
        'direccion',
        'referencia',
        'profile_photo_path',
        'excluir_meta',
        'vidas_total',
        'vidas_restantes',
        'cant_vidas_cero',
        'meta_quincena',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'meta_cobro' => 'double',
        'excluir_meta' => 'bool',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url'
    ];

    public function encargado()
    {
        return $this->belongsTo(self::class, 'supervisor');
    }

    public function asesoroperario()
    {
        return $this->belongsTo(self::class, 'operario');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'user_id');
    }

    public function pedidosActivos()
    {
        return $this->pedidos()->where('pedidos.estado', '<>', '0');
    }

    public function adminlte_desc()
    {
        $user = User::find(Auth()->user()->id);
        $roles = $user->getRoleNames();

        if ($roles->count()) {
            foreach ($roles as $rol) {
                $rol = $rol;
            }
        } else {
            $rol = 'Sin rol asignado';
        }
        return $rol;
    }

    public function adminlte_profile_url()
    {
        return 'user/profile';
    }

    public function adminlte_image()
    {
        $user = User::find(Auth()->user()->id);

        switch ($user->id) {
            case 1:
                return asset('/imagenes/avatar-admin.png');
                break;
            case 13:
                return asset('/imagenes/avatar-jefe-asesor.png');
            default:
                return asset('/imagenes/avatar-asesor.png');
        }

        return '/../storage/users/' . $user->profile_photo_path;
    }

    /* public function pedidos()
    {
        return $this->hasMany('App\Models\Pedido');
    } */
    public function unreadNotifications()
    {
        return $this->unreadNotificationsLimits()->limit(15);
    }

    public function scopeRol($query, $rol)
    {
        return $query->whereIn($this->qualifyColumn('rol'), \Arr::wrap($rol));
    }

    public function scopeRolAsesor($query)
    {
        return $query->where($this->qualifyColumn('rol'), '=', self::ROL_ASESOR);
    }
  public function scopeRolSupervisor($query)
  {
    return $query->where($this->qualifyColumn('rol'), '=', self::ROL_ENCARGADO);
  }

    public function scopeRolAllAsesor($query)
    {
        return $query->whereIn($this->qualifyColumn('rol'),[self::ROL_ASESOR,self::ROL_ASESOR_ADMINISTRATIVO]);
    }

    public function scopeIncluidoMeta($query)
    {
        return $query->where($this->qualifyColumn('excluir_meta'), '=', '0');
    }


}
