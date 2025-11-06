<?php

namespace App\Repositories;

use App\Contract\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findByField(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($field, $value)->first($columns);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    public function delete(Model $model): bool
    {
        return $model->delete() ?? false;
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }
    public function findWhere(array $where, array $columns = ['*']): Collection
    {
        $query = $this->model->query();

        foreach ($where as $field => $value) {
            // Soporta condiciones complejas: ['field', 'operator', 'value']
            if (is_array($value)) {
                $query->where(...$value);
            } else {
                $query->where($field, '=', $value);
            }
        }

        return $query->get($columns);
    }
    public function firstWhere(array $where): ?Model
    {
        $query = $this->model->query();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $query->where(...$value);
            } else {
                $query->where($field, '=', $value);
            }
        }

        return $query->first();
    }

    public function getModel(): Model
    {
        return $this->model;
    }
    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }
}
