<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\PositionRepository;

class CrudPositionService extends BaseCrudService
{
    protected $repository;

    public function __construct(PositionRepository $repository)
    {
        $this->repository = $repository;
    }
}
