<?php

namespace App\Services\Systems\Tenant\Crud;

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
