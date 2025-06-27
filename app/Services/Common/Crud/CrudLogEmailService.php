<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\LogEmailRepository;

class CrudLogEmailService extends BaseCrudService
{
    protected $repository;

    public function __construct(LogEmailRepository $repository)
    {
        $this->repository = $repository;
    }
}
