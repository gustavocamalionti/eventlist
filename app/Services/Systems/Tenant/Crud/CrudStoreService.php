<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Repositories\StoreRepository;
use App\Services\BaseCrudService;

class CrudStoreService extends BaseCrudService
{
    protected $repository;

    public function __construct(StoreRepository $repository)
    {
        $this->repository = $repository;
    }
}
