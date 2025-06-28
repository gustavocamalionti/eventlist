<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Tenant\BuyRepository;

class CrudBuyService extends BaseCrudService
{
    protected $repository;

    public function __construct(BuyRepository $repository)
    {
        $this->repository = $repository;
    }
}
