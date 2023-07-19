<?php

namespace Project\Infrastructure\Secondary\Database\Base;

use Project\Domain\Base\BaseDomainModel;
use Project\Domain\Base\Condition;
use Project\Domain\Base\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as CollectionDao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginatorClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

/*
 * Keep all the common repository declarations in Base Repository and extend it
 */

class EloquentBaseRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Model
     */
    protected Model $modelDAO;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        /*
         * @deprecated
         */
        $this->model = $model;
        $this->modelDAO = $model;
    }

    /**
     * @param null|int $id
     * @return Builder|Model
     */
    protected function createModelDAO(int $id = null)
    {
        return $id ?
            $this->createQuery()->where($this->modelDAO->getKeyName(), $id)->firstOrFail() :
            $this->modelDAO->newInstance();
    }

    /**
     * @return Builder
     */
    protected function createQuery(): Builder
    {
        return $this->modelDAO->newModelQuery();
    }

    /**
     * @param Filter $filter
     * @return Collection
     */
    public function getList(Filter $filter): Collection
    {
        $query = $this->createQuery();

        if ($filter->getConditions()->count()) {
            $this->mapConditionsToQuery($query, $filter->getConditions());
        }
        if ($filter->getLimit()) {
            $query->limit($filter->getLimit());
        }
        if ($filter->getOffset()) {
            $query->offset($filter->getOffset());
        }

        return $this->transformCollection($query->get());
    }

    public function getField(Filter $filter, $fieldName): array
    {
        $query = $this->createQuery();
        if ($filter->getConditions()->count()) {
            $this->mapConditionsToQuery($query, $filter->getConditions());
        }

        return $query->pluck($fieldName)->toArray();
    }
    /**
     * @param Filter $filter
     * @return array
     */
    public function getListIds(Filter $filter): array
    {
        $query = $this->createQuery();

        if ($filter->getConditions()->count()) {
            $this->mapConditionsToQuery($query, $filter->getConditions());
        }
        if ($filter->getLimit()) {
            $query->limit($filter->getLimit());
        }
        if ($filter->getOffset()) {
            $query->offset($filter->getOffset());
        }

        return $query->pluck('id')->toArray();
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->transformCollection($this->modelDAO->newModelQuery()->get());
    }

    /**
     * @param Collection|BaseDomainModel[] $models
     * @return bool
     */
    public function saveAll(Collection $models): bool
    {
        foreach ($models as $model) {
            $this->createModelDAO($model->getId())->saveData($model);
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->createQuery()->where($this->modelDAO->getKey(), $id)->delete();
    }

    /**
     * Create domain model collection from modelDao collection
     *
     * @param CollectionDao $modelDaoCollection
     * @return Collection
     */
    protected function transformCollection(CollectionDao $modelDaoCollection): Collection
    {
        $domainModelCollection = new Collection();

        /* @var BaseModel $item */
        foreach ($modelDaoCollection as $item) {
            try {
                $domainModelCollection->add($item->toDomainEntity());
            } catch (Throwable $e) {
                Log::error($e);
            }
        }

        return $domainModelCollection;
    }

    protected function transformArray(array $modelDaoArray): array
    {
        $domainModelArray = [];

        /* @var BaseModel $item */
        foreach ($modelDaoArray as $item) {
            $domainModelArray[] = $item->toDomainEntity();
        }

        return $domainModelArray;
    }

    /**
     * @param LengthAwarePaginatorClass $paginator
     * @return LengthAwarePaginatorClass
     */
    protected function transformPaginator(LengthAwarePaginatorClass $paginator)
    {
        return new LengthAwarePaginatorClass(
            $this->transformArray($paginator->items()),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            $paginator->getOptions()
        );
    }

    /**
     * @param array $params
     * @return Collection
     */
    public function getSpecificDomainModelCollections(array $params): Collection
    {
        $query = $this->modelDAO->newModelQuery();

        if (!empty($params['search']))
            $query = $this->applySearch($query, $params['search']);

        if (!empty($params['filters']))
            $query = $this->applyFilters($query, $params['filters']);

        $eloquentModelCollection = $query->get();

        return $this->transformCollection($eloquentModelCollection);
    }

    /**
     * @param Builder $query
     * @param Collection | Condition[] $conditions
     */
    protected function mapConditionsToQuery(Builder $query, Collection $conditions): void
    {
        $conditions->each(function ($condition) use ($query) {
            /* @var Condition $condition */
            switch ($condition->getType()) {
                case Condition::EQ:
                    $query->where($condition->getField(), $condition->getValue());
                    break;

                case Condition::NOTEQ:
                    $query->where($condition->getField(), '!=', $condition->getValue());
                    break;

                case Condition::IN:
                    $query->whereIn($condition->getField(), $condition->getValue());
                    break;

                case Condition::INRAW:
                    $query->whereIntegerInRaw($condition->getField(), $condition->getValue());
                    break;

                case Condition::NOTIN:
                    $query->whereNotIn($condition->getField(), $condition->getValue());
                    break;

                case Condition::GT:
                    $query->where($condition->getField(), '>', $condition->getValue());
                    break;

                case Condition::GTEQ:
                    $query->where($condition->getField(), '>=', $condition->getValue());
                    break;

                case Condition::LT:
                    $query->where($condition->getField(), '<', $condition->getValue());
                    break;

                case Condition::LTEQ:
                    $query->where($condition->getField(), '<=', $condition->getValue());
                    break;

                case Condition::NULL:
                    $query->whereNull($condition->getField());
                    break;

                case Condition::NOTNULL:
                    $query->whereNotNull($condition->getField());
                    break;

                case Condition::LIKE:
                    $query->where($condition->getField(), 'like', '%' . $condition->getValue() . '%');
                    break;
            }
        });
    }

    /**
     * @param Builder $query
     * @param array $searches
     * @return Builder
     */
    private function applySearch(Builder $query, array $searches): Builder
    {
        foreach ($searches as $column => $value)
            $query = $query->where($column, 'LIKE', '%' . $value . '%');

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $column => $value)
            $query = $query->where($column, $value);

        return $query;
    }

    /**
     * @return CollectionDao
     * @deprecated
     *
     */
    public function getAllModelDao(): CollectionDao
    {
        return $this->createQuery()->get();
    }

    /**
     * Get the initialized model
     *
     * @return BaseModel
     * @deprecated
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get filter for the model
     *
     * @return array
     * @deprecated
     */
    public function getFilter(): array
    {
        return $this->model->getFilter();
    }

    /**
     * Build index query for the model
     *
     * @param array $param
     * @return Builder
     * @deprecated
     */
    public function buildIndexQuery(array $param): Builder
    {
        return $this->model->buildIndexQuery($param);
    }

    /**
     * Paginate over the model
     *
     * @return LengthAwarePaginator
     * @deprecated
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->model->newModelQuery()->paginate();
    }

    /**
     * @param int $id
     * @return BaseDomainModel|null
     */
    public function findById(int $id): ?BaseDomainModel
    {
        $model = $this->model->newModelQuery()->find($id);

        return $model?->toDomainEntity() ?? null;
    }

    /**
     * Find by Ids
     *
     * @param array $ids
     * @return Builder
     * @deprecated
     */
    public function findByIds(array $ids): Builder
    {
        return $this->model->newModelQuery()->whereIn('id', $ids);
    }

    /**
     * Get model data by key
     *
     * @param string $key
     * @return Builder
     * @deprecated
     */
    public function findByKey(string $key): Builder
    {
        return $this->model->newModelQuery()->where('key', $key);
    }

    /**
     * Create New data
     *
     * @param array $data
     * @return Model
     * @deprecated
     */
    public function create(array $data): Model
    {
        return $this->model->newModelQuery()->create($data);
    }

    /**
     * Update an existing data
     *
     * @param $value
     * @param array $data
     * @param string $column
     * @return bool
     * @deprecated
     */
    public function update($value, array $data, string $column = 'id'): bool
    {
        return $this->model->newModelQuery()->where($column, $value)->update($data);
    }

    /**
     * @param $value
     * @param string $column
     * @return bool
     * @throws \Exception
     * @deprecated
     *
     */
    public function deleteModelDao($value, string $column = 'id'): bool
    {
        return $this->createQuery()->where($column, $value)->delete();
    }

    /**
     * Force delete an existing record
     *
     * @param $value
     * @param string $column
     * @return bool
     * @deprecated
     */
    public function forceDelete($value, string $column = 'id'): bool
    {
        return $this->model->newModelQuery()->where($column, $value)->forceDelete();
    }

    /**
     * Create multiple new data
     *
     * @param array $data
     * @return Model
     * @deprecated
     */
    public function createMany(array $data): Model
    {
        return $this->model->createMany($data);
    }

    /**
     * Create or update multiple data
     * @param $values
     * @param $uniqueBy
     * @param null $update
     * @return mixed
     */
    public function upsert($values, $uniqueBy, $update = null): mixed
    {
        return $this->model->upsert($values, $uniqueBy, $update);
    }

    /**
     * Create New data if it doesnt exist
     *
     * @param array $data
     * @param array $additionalData
     * @return mixed
     * @deprecated
     */
    public function firstOrCreate(array $data, array $additionalData = []): Model
    {
        return $this->model->newModelQuery()->firstOrCreate($data, $additionalData);
    }

    /**
     * Update the data if exists or create a new one
     *
     * @param array $data
     * @param array $additionalData
     * @return Builder|Model
     * @deprecated
     */
    public function updateOrCreate(array $data, array $additionalData = []): Builder|Model
    {
        return $this->model->newModelQuery()->updateOrCreate($data, $additionalData);
    }

    /**
     * Replicate a model based on the given instance
     *
     * @param $modelInstance
     * @param array $fill
     * @return mixed
     */
    public function replicate(Model $modelInstance, array $fill = [])
    {
        return $modelInstance->replicate()->fill($fill);
    }

    /**
     * Bulk insert data
     *
     * @param array $data
     * @return bool
     * @deprecated
     */
    public function insert(array $data): bool
    {
        return $this->model->newModelQuery()->insert($data);
    }

    /**
     * Truncate the model
     * @deprecated
     */
    public function truncate()
    {
        $this->model->newModelQuery()->truncate();
    }

    public function deleteByIds(array $ids): bool
    {
        return $this->createQuery()->whereIn('id', $ids)->delete();
    }
}
