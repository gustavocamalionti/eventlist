<?php

namespace App\Services\Systems\Master\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Systems\Master\BuyRepository;

class CrudBuyService extends BaseCrudService
{
    protected $repository;

    public function __construct(BuyRepository $repository)
    {
        $this->repository = $repository;
    }
}
