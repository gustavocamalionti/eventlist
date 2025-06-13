<?php

namespace App\Services;

use App\Services\BaseRulesService;

class BaseCrudService extends BaseRulesService
{
    /***********************************************************************************
     * Metods CRUD
     * *********************************************************************************/
    public function getAll(
        $arrayWheres = [],
        $arrayOrder = [],
        $limit = 0,
        $returnArray = true,
        $withTrashed = false,
        $withParameters = [],
        $whereRelation = []
    ) {
        return $this->repository->getAll(
            $arrayWheres,
            $arrayOrder,
            $limit,
            $returnArray,
            $withTrashed,
            $withParameters,
            $whereRelation
        );
    }

    public function getAllByColumn($columnName, $value, $statusActive, $arrayWheres = [])
    {
        return $this->repository->getAllByColumn($columnName, $value, $statusActive, $arrayWheres);
    }

    public function getMax($column)
    {
        return $this->repository->getMax($column);
    }

    public function getMin($column)
    {
        return $this->repository->getMin($column);
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function save(array $data)
    {
        return $this->repository->save($data);
    }

    public function update($id, array $data, bool $typeReturn = false)
    {
        return $this->repository->update($id, $data, $typeReturn);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function updateAll($array_of_ids, $data_associative)
    {
        $this->repository->updateAll($array_of_ids, $data_associative);
    }

    /**
     * Definir a nova ordem dos itens, tomando como referencia o n° de ordenação do ultimo.
     */
    public function defineOrder($decision, $column)
    {
        return $decision ? $this->repository->getMax($column) + 1 : 0;
    }

    /**
     * Atualizar a ordem de todos os itens do array ($arrayId) de forma massiva.
     */

    public function updateOrder($arrayId)
    {
        $count = 1;
        foreach ($arrayId as $id) {
            $model = $this->repository->findById($id);
            $model->update(["order" => $count]);
            $count++;
        }

        return true;
    }

    public function entity()
    {
        return $this->repository->resolveEntity();
    }

    /***********************************************************************************
     * Controles de transação
     * *********************************************************************************/
    public function beginTransaction(): void
    {
        $this->repository->beginTransaction();
    }

    public function rollBackTransaction(): void
    {
        $this->repository->rollBackTransaction();
    }

    public function commitTransaction(): void
    {
        $this->repository->commitTransaction();
    }

    public function transactionLevel()
    {
        return $this->repository->transactionLevel();
    }
}
