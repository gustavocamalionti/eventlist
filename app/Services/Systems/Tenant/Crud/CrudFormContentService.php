<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\FormContentRepository;

class CrudFormContentService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormContentRepository $repository)
    {
        $this->repository = $repository;
    }
}
