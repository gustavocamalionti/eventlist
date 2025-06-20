<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Tenant\WebhookRepository;

class CrudWebhookService extends BaseCrudService
{
    protected $repository;

    public function __construct(WebhookRepository $repository)
    {
        $this->repository = $repository;
    }
}
