<?php

namespace App\Services\Systems\Master\Crud;

use App\Repositories\FormRepository;
use App\Services\BaseCrudService;

class CrudFormService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormRepository $repository)
    {
        $this->repository = $repository;
    }
}
