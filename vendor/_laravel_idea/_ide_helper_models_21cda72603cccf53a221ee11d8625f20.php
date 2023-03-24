<?php //8d54265936126627e0c6a717270acce8
/** @noinspection all */

namespace Laravel\Jetstream {

    use App\Models\Team;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use LaravelIdea\Helper\App\Models\_IH_Team_QB;
    use LaravelIdea\Helper\Laravel\Jetstream\_IH_TeamInvitation_C;
    use LaravelIdea\Helper\Laravel\Jetstream\_IH_TeamInvitation_QB;

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
}
