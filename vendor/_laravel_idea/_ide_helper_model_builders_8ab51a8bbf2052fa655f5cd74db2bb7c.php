<?php //c28fda8c14ba5baaa103ad76674ccbee
/** @noinspection all */

namespace LaravelIdea\Helper\Spatie\MediaLibrary\MediaCollections\Models {

    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Database\Query\Expression;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use LaravelIdea\Helper\_BaseBuilder;
    use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
     * @method MediaCollection|Media[] cursor()
     * @method Media|null|MediaCollection|Media[] find($id, array $columns = ['*'])
     * @method MediaCollection|Media[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Media|MediaCollection|Media[] findOrFail($id, array $columns = ['*'])
     * @method Media|MediaCollection|Media[] findOrNew($id, array $columns = ['*'])
     * @method Media first(array|string $columns = ['*'])
     * @method Media firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Media firstOrCreate(array $attributes = [], array $values = [])
     * @method Media firstOrFail(array $columns = ['*'])
     * @method Media firstOrNew(array $attributes = [], array $values = [])
     * @method Media firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Media forceCreate(array $attributes)
     * @method MediaCollection|Media[] fromQuery(string $query, array $bindings = [])
     * @method MediaCollection|Media[] get(array|string $columns = ['*'])
     * @method Media getModel()
     * @method Media[] getModels(array|string $columns = ['*'])
     * @method MediaCollection|Media[] hydrate(array $items)
     * @method Media make(array $attributes = [])
     * @method Media newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Media[]|MediaCollection paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Media[]|MediaCollection simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Media sole(array|string $columns = ['*'])
     * @method Media updateOrCreate(array $attributes, array $values = [])
     * @method _IH_Media_QB ordered()
     */
    class _IH_Media_QB extends _BaseBuilder {}
}
