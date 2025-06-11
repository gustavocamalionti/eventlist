<?php

namespace App\Repositories;

use App\Libs\Enums\EnumStatus;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotEntityDefined;

class BaseRepository
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function resolveEntity()
    {
        if (!method_exists($this, "entity")) {
            throw new NotEntityDefined();
        }

        return app($this->entity());
    }

    public function getAll(
        $arrayWheres = [],
        $arrayOrder = [],
        $limit = 0,
        $returnArray = true,
        $withTrashed = false,
        $withParameters = [],
        $whereRelation = []
    ) {
        $query = $this->entity;

        if ($withTrashed) {
            $query = $query->withTrashed(true);
        }

        if (!empty($withParameters)) {
            $query = $query->with($withParameters);
        }

        if (!empty($whereRelation)) {
            $query = $query->whereRelation($whereRelation[0], $whereRelation[1], $whereRelation[2], $whereRelation[3]);
        }

        foreach ($arrayWheres as $column => $value) {
            if (($column == "active" && $value != EnumStatus::ALL) || $column != "active") {
                $query = $query->where($column, $value);
            }
        }

        foreach ($arrayOrder as $column => $orientation) {
            $query = $query->orderBy($column, $orientation);
        }

        if ($limit > 0) {
            $query = $query->limit($limit);
        }

        if ($returnArray) {
            return $query->get();
        }

        return $query;
    }

    public function getAllByColumn($columnName, $value, $statusActive, $arrayWheres)
    {
        $entity = $this->entity;
        if ($statusActive == EnumStatus::ALL) {
            $entity = $entity->where($columnName, $value);

            foreach ($arrayWheres as $key => $value) {
                $entity = $entity->where($key, $value);
            }

            return $entity->get();
        }

        $entity = $entity->where($columnName, $value)->where("active", $statusActive);

        foreach ($arrayWheres as $key => $value) {
            $entity->where($key, $value);
        }
        return $entity->get();
    }

    public function getAllByColumnWithArray($columnName, $arrayItems)
    {
        $entity = $this->entity;
        foreach ($arrayItems as $item) {
            $entity = $entity->orWhere($columnName, $item);
        }

        return $entity;
    }

    public function getMax($column)
    {
        return $this->entity->max($column);
    }
    public function getMin($column)
    {
        return $this->entity->min($column);
    }

    public function findById($id)
    {
        return $this->entity->find($id);
    }

    public function save(array $data)
    {
        return $this->entity->create($data);
    }

    public function update($id, array $data, bool $typeReturn = false)
    {
        $entity = $this->findById($id)->fill($data);
        $returnBool = $entity->save();

        return !$typeReturn ? $returnBool : $entity;
    }

    public function delete($id)
    {
        return $this->entity->find($id)->delete();
    }

    public function updateAll($array_of_ids, $data_associative)
    {
        $this->entity->whereIn("id", $array_of_ids)->update($data_associative);
    }

    public function transactionLevel()
    {
        return DB::transactionLevel();
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function rollBackTransaction(): void
    {
        DB::rollBack();
    }

    public function commitTransaction(): void
    {
        DB::commit();
    }
}
