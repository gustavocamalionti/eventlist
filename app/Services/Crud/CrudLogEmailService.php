<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\Enterprise\LogEmailRepository;

class CrudLogEmailService extends BaseCrudService
{
    protected $repository;

    public function __construct(LogEmailRepository $repository)
    {
        $this->repository = $repository;
    }
}
