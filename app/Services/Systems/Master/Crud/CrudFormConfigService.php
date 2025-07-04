<?php

namespace App\Services\Systems\Master\Crud;

use App\Services\BaseCrudService;
use App\Repositories\FormConfigRepository;

class CrudFormConfigService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormConfigRepository $repository)
    {
        $this->repository = $repository;
    }
}
