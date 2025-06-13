<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\MethodPaymentRepository;

class CrudMethodPaymentService extends BaseCrudService
{
    protected $repository;

    public function __construct(MethodPaymentRepository $repository)
    {
        $this->repository = $repository;
    }
}
