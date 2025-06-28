<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\FormSubjectRepository;

class CrudFormSubjectService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormSubjectRepository $repository)
    {
        $this->repository = $repository;
    }
}
