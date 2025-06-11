<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\CustomColorRepository;

class CrudCustomColorService extends BaseCrudService
{
    protected $repository;

    public function __construct(CustomColorRepository $repository)
    {
        $this->repository = $repository;
    }
}
