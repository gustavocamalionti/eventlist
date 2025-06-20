<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Master\LogEmailRepository;

class CrudLogEmailService extends BaseCrudService
{
    protected $repository;

    public function __construct(LogEmailRepository $repository)
    {
        $this->repository = $repository;
    }
}
