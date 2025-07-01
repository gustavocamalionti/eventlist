<?php

namespace App\Services\Systems\Master\Crud;

use App\Services\BaseCrudService;
use App\Repositories\EndEmailsSendRepository;

class CrudEndEmailsSendService extends BaseCrudService
{
    protected $repository;

    public function __construct(EndEmailsSendRepository $repository)
    {
        $this->repository = $repository;
    }
}
