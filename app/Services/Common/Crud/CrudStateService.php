<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\StateRepository;

class CrudStateService extends BaseCrudService
{
    protected $repository;

    public function __construct(StateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Em certos momentos é necessário retornar para a view todos os estados onde existem lojas participantes do clube de benefícios
     */
    public function getStatesClubActive()
    {
        return $this->repository->getStatesClubActive();
    }
}
