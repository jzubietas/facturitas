<?php //212f6f5bdbeb3fa97cd708d6e0814b7b
/** @noinspection all */

namespace Spatie\MediaLibrary\MediaCollections\Models {

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    use Illuminate\Support\Carbon;
    use LaravelIdea\Helper\Spatie\MediaLibrary\MediaCollections\Models\_IH_Media_QB;
    use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

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
     * @property-read string $extension attribute
     * @property-read string $human_readable_size attribute
     * @property-read string $type attribute
     * @property Model $model
     * @method MorphTo model()
     * @method static _IH_Media_QB onWriteConnection()
     * @method _IH_Media_QB newQuery()
     * @method static _IH_Media_QB on(null|string $connection = null)
     * @method static _IH_Media_QB query()
     * @method static _IH_Media_QB with(array|string $relations)
     * @method _IH_Media_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static MediaCollection|Media[] all()
     * @mixin _IH_Media_QB
     */
    class Media extends Model {}
}
