<?php //b68eadf1e739fef05acff54e8302eeb7
/** @noinspection all */

namespace LaravelIdea\Helper\Laravel\Jetstream {

    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Database\Query\Expression;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use Laravel\Jetstream\TeamInvitation;
    use LaravelIdea\Helper\_BaseBuilder;
    use LaravelIdea\Helper\_BaseCollection;

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
}
