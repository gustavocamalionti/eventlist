<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\LogErrorRepository;

class CrudLogErrorService extends BaseCrudService
{
    protected $repository;
    protected $actionRepository;

    public function __construct(LogErrorRepository $repository)
    {
        $this->repository = $repository;
    }
}
