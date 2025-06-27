<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\CustomColorRepository;

class CrudCustomColorService extends BaseCrudService
{
    protected $repository;

    public function __construct(CustomColorRepository $repository)
    {
        $this->repository = $repository;
    }
}
