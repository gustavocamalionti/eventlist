<?php

namespace App\Services\Common\Crud;

use App\Services\BaseCrudService;
use App\Repositories\Common\WebhookRepository;

class CrudWebhookService extends BaseCrudService
{
    protected $repository;

    public function __construct(WebhookRepository $repository)
    {
        $this->repository = $repository;
    }
}
