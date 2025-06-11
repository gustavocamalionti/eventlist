<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\FormContentRepository;

class CrudFormContentService extends BaseCrudService
{
    protected $repository;

    public function __construct(FormContentRepository $repository)
    {
        $this->repository = $repository;
    }
}
