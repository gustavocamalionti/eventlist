<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\FormSubjectRepository;

class CrudFormSubjectService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormSubjectRepository $repository)
    {
        $this->repository = $repository;
    }
}
