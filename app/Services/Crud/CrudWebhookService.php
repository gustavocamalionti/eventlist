<?php

namespace App\Services\Crud;

use App\Services\Bases\BaseCrudService;
use App\Repositories\Tenant\WebhookRepository;

class CrudWebhookService extends BaseCrudService
{
    protected $repository;

    public function __construct(WebhookRepository $repository)
    {
        $this->repository = $repository;
    }
}
