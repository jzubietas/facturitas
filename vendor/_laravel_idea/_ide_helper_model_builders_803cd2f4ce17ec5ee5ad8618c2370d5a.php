<?php //37001f47d6022d70722519daf6e8ba7e
/** @noinspection all */

namespace Illuminate\Notifications {

    use LaravelIdea\Helper\_BaseCollection;

    /**
     * @method DatabaseNotification|null getOrPut($key, $value)
     * @method DatabaseNotification|$this shift(int $count = 1)
     * @method DatabaseNotification|null firstOrFail($key = null, $operator = null, $value = null)
     * @method DatabaseNotification|$this pop(int $count = 1)
     * @method DatabaseNotification|null pull($key, $default = null)
     * @method DatabaseNotification|null last(callable $callback = null, $default = null)
     * @method DatabaseNotification|$this random(int|null $number = null)
     * @method DatabaseNotification|null sole($key = null, $operator = null, $value = null)
     * @method DatabaseNotification|null get($key, $default = null)
     * @method DatabaseNotification|null first(callable $callback = null, $default = null)
     * @method DatabaseNotification|null firstWhere(string $key, $operator = null, $value = null)
     * @method DatabaseNotification|null find($key, $default = null)
     * @method DatabaseNotification[] all()
     */
    class DatabaseNotificationCollection extends _BaseCollection {
        /**
         * @param int $size
         * @return DatabaseNotification[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }
}
