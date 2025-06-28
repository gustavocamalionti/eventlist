<?php

namespace App\Services\Common\Crud;

use App\Libs\Enums\EnumStatus;
use App\Services\BaseCrudService;
use App\Repositories\Common\CitieRepository;

class CrudCitieService extends BaseCrudService
{
    protected $repository;

    public function __construct(CitieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Em certos momentos é necessário retornar para a view todas as cidades que existem no estado da loja.
     */
    public function getCitiesByStates($stores)
    {
        $existsStore = isset($stores->cities->states) ? true : false;
        return $existsStore
            ? $this->repository->getAllByColumn("states_id", $stores->cities->states_id, EnumStatus::ALL, [])
            : null;
    }

    /**
     * Em certos momentos é necessário retornar para a view todas as cidades onde existem ao menos 1 loja.
     */
    public function getCitiesByStateStores($statesId)
    {
        return $this->repository->getCitiesByStateStore($statesId);
    }

    /**
     * Em certos momentos é necessário retornar para a view todos os estados onde existem lojas participantes do clube de benefícios
     */
    public function getCitiesClubActive($statesId)
    {
        return $this->repository->getCitiesClubActive($statesId);
    }
}
