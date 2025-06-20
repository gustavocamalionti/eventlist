<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\CustomColorRepository;

class CrudCustomColorService extends BaseCrudService
{
    protected $repository;

    public function __construct(CustomColorRepository $repository)
    {
        $this->repository = $repository;
    }
}
