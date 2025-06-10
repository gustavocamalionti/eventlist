<?php

namespace App\Services\Crud;

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
