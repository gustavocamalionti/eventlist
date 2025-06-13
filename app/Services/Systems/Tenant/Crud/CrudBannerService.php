<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Repositories\BannerRepository;
use App\Services\Bases\BaseCrudService;
use App\Services\Panel\Rules\RulesFilesService;

class CrudBannerService extends BaseCrudService
{
    //declaração de funções
    protected $rulesFilesService;
    protected $repository;

    public function __construct(
        //injeções de services
        RulesFilesService $rulesFilesService,

        //inveções de repositories
        BannerRepository $repository
    ) {
        //services
        $this->rulesFilesService = $rulesFilesService;

        //repositories
        $this->repository = $repository;
    }

    /**
     * Pegar todos os banners que estão ativos e dentro das datas de programação.
     */
    public function getBannersActiveProgramming()
    {
        return $this->repository->getBannersActiveProgramming();
    }
}
