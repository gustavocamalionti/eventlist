<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\FormConfigRepository;

class CrudFormConfigService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormConfigRepository $repository)
    {
        $this->repository = $repository;
    }
}
