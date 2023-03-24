<?php //f5007a0017909ed038a35ca639aa665e
/** @noinspection all */

namespace Database\Factories {

    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @method $this hasDetalle_pagos(int $count = 1, $attributes = [])
     * @method $this hasPago_pedidos(int $count = 1, $attributes = [])
     * @method $this forUser($attributes = [])
     */
    class PagoFactory extends Factory {}

    /**
     * @method $this forOwner($attributes = [])
     * @method $this hasTeamInvitations(int $count = 1, $attributes = [])
     * @method $this hasUsers(int $count = 1, $attributes = [])
     */
    class TeamFactory extends Factory {}

    /**
     * @method $this forAsesoroperario($attributes = [])
     * @method $this forEncargado($attributes = [])
     * @method $this hasNotifications(int $count = 1, $attributes = [])
     * @method $this hasPedidos(int $count = 1, $attributes = [])
     * @method $this hasPedidosActivos(int $count = 1, $attributes = [])
     * @method $this hasPermissions(int $count = 1, $attributes = [])
     * @method $this hasReadNotifications(int $count = 1, $attributes = [])
     * @method $this hasRoles(int $count = 1, $attributes = [])
     * @method $this hasTokens(int $count = 1, $attributes = [])
     * @method $this hasUnreadNotifications(int $count = 1, $attributes = [])
     */
    class UserFactory extends Factory {}
}
