<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\EndEmailsSendRepository;

class CrudEndEmailsSendService extends BaseCrudService
{
    protected $repository;

    public function __construct(EndEmailsSendRepository $repository)
    {
        $this->repository = $repository;
    }
}
