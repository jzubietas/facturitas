<?php //de604664f8abaac0ec7ad9b621e4d752
/** @noinspection all */

namespace Laravel\Telescope\Storage {

    use Illuminate\Database\Eloquent\Model;
    use LaravelIdea\Helper\Laravel\Telescope\Storage\_IH_EntryModel_C;
    use LaravelIdea\Helper\Laravel\Telescope\Storage\_IH_EntryModel_QB;

    /**
     * @method static _IH_EntryModel_QB onWriteConnection()
     * @method _IH_EntryModel_QB newQuery()
     * @method static _IH_EntryModel_QB on(null|string $connection = null)
     * @method static _IH_EntryModel_QB query()
     * @method static _IH_EntryModel_QB with(array|string $relations)
     * @method _IH_EntryModel_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_EntryModel_C|EntryModel[] all()
     * @mixin _IH_EntryModel_QB
     */
    class EntryModel extends Model {}
}
