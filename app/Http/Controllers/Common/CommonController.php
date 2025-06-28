<?php

namespace App\Http\Controllers\Common;

use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;

use App\Services\BaseRulesService;

use App\Services\Crud\CrudLinkService;

use App\Http\Controllers\Common\Controller;
use App\Services\Common\Crud\CrudCitieService;
use App\Services\Systems\Tenant\Crud\CrudBuyService;
use App\Services\Systems\Tenant\Crud\CrudVoucherService;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Services\Systems\Tenant\Crud\CrudFormSubjectService;

/**
 * Controller responsible for handling various dashboard and related operations in the panel.
 */
class CommonController extends Controller
{
    protected $crudParameterService;
    protected $crudCitieService;
    // protected $crudFormSubjectService;
    // protected $crudLinkService;
    // protected $crudBuyService;
    // protected $crudVoucherService;
    protected $baseRulesService;

    public function __construct(
        CrudParameterService $crudParameterService,
        CrudCitieService $crudCitieService,
        // CrudFormSubjectService $crudFormSubjectService,
        // CrudLinkService $crudLinkService,

        // CrudBuyService $crudBuyService,
        // CrudVoucherService $crudVoucherService,

        BaseRulesService $baseRulesService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudCitieService = $crudCitieService;
        // $this->crudFormSubjectService = $crudFormSubjectService;
        // $this->crudLinkService = $crudLinkService;

        // $this->crudBuyService = $crudBuyService;
        // $this->crudVoucherService = $crudVoucherService;

        $this->baseRulesService = $baseRulesService;
    }

    /**
     * Retrieves a list of cities for a given state.
     *
     * @param int $statesId ID of the state to filter cities.
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of cities in the state ordered by name.
     */
    public function cities($statesId = null)
    {
        $cities = [];
        if ($statesId != null) {
            $cities = $this->crudCitieService->getAllByColumn("states_id", $statesId, EnumStatus::ALL);
            $cities = $this->crudCitieService->orderCollection($cities, "name", EnumOrderBy::ASC);
        }

        return $cities;
    }

    /**
     * Retrieves a list of stores for a given city.
     *
     * @param int $citiesId ID of the city to filter stores.
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of stores in the city ordered by name.
     */
    public function stores($citiesId = null)
    {
        $stores = [];
        if ($citiesId != null) {
            $stores = $this->crudStoreService->getAllByColumn("cities_id", $citiesId, EnumStatus::ALL);

            $stores = $this->crudStoreService->orderCollection($stores, "name", EnumOrderBy::ASC);
        }
        return $stores;
    }

    /**
     * Retrieves a list of form subjects for a given form.
     *
     * @param int $formId ID of the form to filter form subjects.
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of form subjects ordered by name.
     */
    public function cascadeFormSubjects($formId)
    {
        $formSubjects = $this->crudFormSubjectService->getAllByColumn("forms_id", $formId, EnumStatus::ALL);

        $formSubjects = $this->crudFormSubjectService->orderCollection($formSubjects, "name", EnumOrderBy::ASC);

        return $formSubjects;
    }

    /**
     * Retrieves a list of links for a populate blade component select.
     *
     * @param int $id it would be possible to pass the id of a field if it were relevant
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of categories ordered by name.
     */
    public function updateFieldLinks($id)
    {
        $links = $this->crudLinkService->getAll([], ["name" => "ASC"]);

        return $links;
    }
}
