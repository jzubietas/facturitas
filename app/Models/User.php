<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use TwoFactorAuthenticatable;

    use HasRoles;


    const ROL_ADMIN = "Administrador";
    const ROL_JEFE_OPERARIO = "Jefe de operaciones";
    const ROL_OPERARIO = "Operario";
    const ROL_ENCARGADO = "Encargado";
    const ROL_LLAMADAS = "Llamadas";
    const ROL_ASESOR = "Asesor";
    const ROL_LOGISTICA = "Logística";
    const ROL_JEFE_LLAMADAS = "Jefe de llamadas";
    const ROL_ASISTENTE_PAGOS = "Asistente de Pagos";
    const ROL_APOYO_ADMINISTRATIVO = "Apoyo administrativo";


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
        'profile_photo_path'
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url'
    ];


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

        return '/../storage/users/' . $user->profile_photo_path;
    }
    /* public function pedidos()
    {
        return $this->hasMany('App\Models\Pedido');
    } */
}
