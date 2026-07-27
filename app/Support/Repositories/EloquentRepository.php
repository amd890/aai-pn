<?php

namespace App\Support\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements RepositoryInterface
{
    protected Model $model;
    protected array $with = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function query(): Builder
    {
        $query = $this->model->newQuery();
        if (! empty($this->with)) {
            $query->with($this->with);
        }
        return $query;
    }

    public function with(array|string $relations): static
    {
        $this->with = is_string($relations) ? func_get_args() : $relations;
        return $this;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->query()->find($id, $columns);
    }

    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        return $this->query()->findOrFail($id, $columns);
    }

    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->query()->where($field, $value)->first($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        return $model->fresh();
    }

    public function delete(int|string $id): bool
    {
        $model = $this->findOrFail($id);
        return (bool) $model->delete();
    }
}
