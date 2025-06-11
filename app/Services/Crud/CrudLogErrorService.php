<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
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
