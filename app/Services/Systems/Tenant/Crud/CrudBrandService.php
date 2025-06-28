<?php

namespace App\Services\Systems\Tenant\Crud;

use App\Libs\Enums\EnumOrderBy;
use App\Services\BaseCrudService;
use App\Repositories\BrandRepository;
use App\Services\Crud\CrudStoreService;

class CrudBrandService extends BaseCrudService
{
    protected $repository;
    protected $crudStoreService;

    public function __construct(BrandRepository $repository)
    {
        $this->repository = $repository;
        $this->crudStoreService = app(CrudStoreService::class);
    }

    public function getBrandsByGroupEconomic($groupEconomicId)
    {
        $storesByGroupEconomic = $this->crudStoreService->getAll(["group_economic_id" => $groupEconomicId]);
        // dd($groupEconomicId, $storesByGroupEconomic);
        $uniqueBrandsIds = array_values(
            array_unique(
                array_map(function ($item) {
                    return $item["brands_id"];
                }, $storesByGroupEconomic->toArray())
            )
        );

        $brands = $this->repository->getAllByColumnWithArray("id", $uniqueBrandsIds);

        return $brands->orderBy("id", EnumOrderBy::ASC)->get();
    }
}
