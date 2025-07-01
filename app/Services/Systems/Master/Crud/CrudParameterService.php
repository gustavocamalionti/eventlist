<?php

namespace App\Services\Systems\Master\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Systems\Master\ParameterRepository;

class CrudParameterService extends BaseCrudService
{
    protected $repository;

    public function __construct(ParameterRepository $repository)
    {
        $this->repository = $repository;
    }
}
