<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\LogAuditRepository;

class CrudLogAuditService extends BaseCrudService
{
    protected $repository;

    public function __construct(LogAuditRepository $repository)
    {
        $this->repository = $repository;
    }
}
