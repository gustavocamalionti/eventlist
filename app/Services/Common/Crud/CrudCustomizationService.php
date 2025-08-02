<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\CustomizationRepository;

class CrudCustomizationService extends BaseCrudService
{
    protected $repository;

    public function __construct(CustomizationRepository $repository)
    {
        $this->repository = $repository;
    }
}
