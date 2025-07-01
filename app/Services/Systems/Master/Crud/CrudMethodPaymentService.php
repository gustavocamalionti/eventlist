<?php

namespace App\Services\Systems\Master\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Systems\Master\MethodPaymentRepository;

class CrudMethodPaymentService extends BaseCrudService
{
    protected $repository;

    public function __construct(MethodPaymentRepository $repository)
    {
        $this->repository = $repository;
    }
}
