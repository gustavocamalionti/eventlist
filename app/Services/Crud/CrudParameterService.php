<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\Tenant\ParameterRepository;

class CrudParameterService extends BaseCrudService
{
    protected $repository;

    public function __construct(ParameterRepository $repository)
    {
        $this->repository = $repository;
    }
}
