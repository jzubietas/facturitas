<?php //152b501e09e8fea9b7c10d23dfb1cfae
/** @noinspection all */

namespace Spatie\MediaLibrary\MediaCollections\Models\Collections {

    use LaravelIdea\Helper\_BaseCollection;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
    class MediaCollection extends _BaseCollection {
        /**
         * @param int $size
         * @return Media[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
}
