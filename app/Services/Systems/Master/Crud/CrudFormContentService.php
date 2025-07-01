<?php

namespace App\Services\Systems\Master\Crud;

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
