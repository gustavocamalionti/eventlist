<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\CustomerRepository;

class CrudCustomerService extends BaseCrudService
{
    protected $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }
}
