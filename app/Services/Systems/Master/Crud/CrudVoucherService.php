<?php

namespace App\Services\Systems\Master\Crud;

use App\Repositories\VoucherRepository;
use App\Services\BaseCrudService;

class CrudVoucherService extends BaseCrudService
{
    protected $repository;

    public function __construct(VoucherRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getVoucherGroupBySize($buysId)
    {
        return $this->repository->getVoucherGroupBySize($buysId);
    }
}
