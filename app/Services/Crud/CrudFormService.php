<?php

namespace App\Services\Crud;

use App\Repositories\FormRepository;
use App\Services\Bases\BaseCrudService;

class CrudFormService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormRepository $repository)
    {
        $this->repository = $repository;
    }
}
