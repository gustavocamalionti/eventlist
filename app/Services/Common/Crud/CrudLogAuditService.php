<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\LogAuditRepository;

class CrudLogAuditService extends BaseCrudService
{
    protected $repository;

    public function __construct(LogAuditRepository $repository)
    {
        $this->repository = $repository;
    }
}
