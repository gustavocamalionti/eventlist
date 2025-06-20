<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\LogErrorRepository;

class CrudLogErrorService extends BaseCrudService
{
    protected $repository;
    protected $actionRepository;

    public function __construct(LogErrorRepository $repository)
    {
        $this->repository = $repository;
    }
}
