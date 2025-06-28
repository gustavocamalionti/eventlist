<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Errors;
use App\Libs\Actions;
use App\Models\Store;
use App\Models\Voucher;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\DB;

use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Services\Crud\CrudBuyService;
use App\Services\Crud\CrudLinkService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStoreService;
use Illuminate\Database\QueryException;
use App\Services\Bases\BaseRulesService;
use App\Services\Crud\CrudVoucherService;
use App\Services\Crud\CrudParameterService;
use App\Services\Crud\CrudFormSubjectService;
use App\Http\Controllers\Controller as Controller;

/**
 * Controller responsible for handling various dashboard and related operations in the panel.
 */
class PanelController extends Controller
{
    protected $crudParameterService;
    protected $crudCitieService;
    protected $crudStoreService;
    protected $crudFormSubjectService;
    protected $crudLinkService;
    protected $crudBuyService;
    protected $crudVoucherService;
    protected $baseRulesService;

    /**
     * Class constructor, initializes necessary services.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudCitieService $crudCitieService Service for handling cities.
     * @param CrudStoreService $crudStoreService Service for handling stores.
     * @param CrudFormSubjectService $crudFormSubjectService Service for handling form subjects.
     * @param CrudLinkService $crudFormSubjectService Service for handling form subjects.
     * @param CrudBuyService $CrudBuyService Service for handling buys.
     * @param CrudVoucherService $CrudVoucherService Service for handling vouchers.
     * @param BaseRulesService $baseRulesService Service for base rules and authentication.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudCitieService $crudCitieService,
        CrudStoreService $crudStoreService,
        CrudFormSubjectService $crudFormSubjectService,
        CrudLinkService $crudLinkService,

        CrudBuyService $crudBuyService,
        CrudVoucherService $crudVoucherService,

        BaseRulesService $baseRulesService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudCitieService = $crudCitieService;
        $this->crudStoreService = $crudStoreService;
        $this->crudFormSubjectService = $crudFormSubjectService;
        $this->crudLinkService = $crudLinkService;

        $this->crudBuyService = $crudBuyService;
        $this->crudVoucherService = $crudVoucherService;

        $this->baseRulesService = $baseRulesService;
    }

    /**
     * Displays the dashboard page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the dashboard view with relevant data or an error response in case of failure.
     */
    public function index()
    {
        try {
            $pageTitle = ViewsModules::PANEL_DASHBOARD;
            $parameters = $this->crudParameterService->findById(1);
            $user = $this->baseRulesService->getUserAuth();

            $buysReceivedList = $this->crudBuyService->getAll(
                ["status" => EnumStatusBuies::WEBHOOK_PAYMENT_SUCCEEDED],
                ["updated_at" => EnumOrderBy::ASC]
            );

            $buysConfirmedList = $this->crudBuyService->getAll(
                ["status" => EnumStatusBuies::WEBHOOK_PAYMENT_PROCESSING],
                ["updated_at" => EnumOrderBy::ASC]
            );

            $buysAwaitingList = $this->crudBuyService->getAll(
                ["status" => EnumStatusBuies::INTERNAL_PAYMENT_WAITING],
                ["updated_at" => EnumOrderBy::ASC]
            );

            $buysReceived = [
                "qtd" => $buysReceivedList->count(),
                "value" => array_sum(array_column($buysReceivedList->toArray(), "value")),
                "net_value" => array_sum(array_column($buysReceivedList->toArray(), "net_value")),
            ];

            $buysConfirmed = [
                "qtd" => $buysConfirmedList->count(),
                "value" => array_sum(array_column($buysConfirmedList->toArray(), "value")),
                "net_value" => array_sum(array_column($buysConfirmedList->toArray(), "net_value")),
            ];

            $buysAwaiting = [
                "qtd" => $buysAwaitingList->count(),
                "value" => array_sum(array_column($buysAwaitingList->toArray(), "value")),
                "net_value" => array_sum(array_column($buysAwaitingList->toArray(), "net_value")),
            ];

            $vouchers = $this->crudVoucherService->getAll(
                ["active" => EnumStatus::ACTIVE],
                ["updated_at" => EnumOrderBy::DESC]
            );

            $qtdStores = Store::whereHas("vouchers", function ($query) {
                $query->where("active", EnumStatus::ACTIVE);
            })->count();

            return view(
                "panel.pages.dashboard.dashboard",
                compact(
                    "pageTitle",
                    "parameters",
                    "user",
                    "buysReceived",
                    "buysConfirmed",
                    "buysAwaiting",
                    "vouchers",
                    "qtdStores"
                )
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_DASHBOARD,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_DASHBOARD,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
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
     * Retrieves a list of stores for a given city.
     *
     * @param int $citiesId ID of the city to filter stores.
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of stores in the city ordered by name.
     */
    public function brandsForStores($brandsId = null, $originStoresId = null)
    {
        $stores = [];
        if ($brandsId != null) {
            if ($originStoresId != null) {
                $store = $this->crudStoreService->findById($originStoresId);

                $groupEconomicId = $store->group_economic_id;

                if ($groupEconomicId == null) {
                    array_push($stores, $store);
                } else {
                    $stores = $this->crudStoreService->getAll(
                        [
                            "brands_id" => $brandsId,
                            "group_economic_id" => $groupEconomicId,
                            "active" => EnumStatus::ACTIVE,
                        ],
                        ["name" => EnumOrderBy::ASC]
                    );
                }
            } else {
                $stores = $this->crudStoreService->getAll(
                    [
                        "brands_id" => $brandsId,
                        "active" => EnumStatus::ACTIVE,
                    ],
                    ["name" => EnumOrderBy::ASC]
                );
            }
        }
        return $stores;
    }

    /**
     * Checks if there are any stores in a given state.
     *
     * @param int $statesId ID of the state to check for stores.
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of active stores in the state, excluding store ID 1.
     */
    public function statesExistStores($statesId = null)
    {
        $listStores = [];
        if ($statesId != null) {
            $query = Store::select("stores.*")
                ->join("cities", "stores.cities_id", "cities.id")
                ->join("states", "cities.states_id", "states.id")
                ->where("stores.active", EnumStatus::ACTIVE)
                ->where("stores.id", "!=", 1);

            if (!is_null($statesId)) {
                $query = $query->where("cities.states_id", $statesId);
            }

            $listStores = $query->orderBy("stores.name", EnumOrderBy::ASC)->get();
        }
        return $listStores;
    }

    /**
     * Filters stores based on the provided state, city, and store IDs.
     *
     * @param mixed $statesId State ID to filter by.
     * @param mixed $citiesId City ID to filter by.
     * @param mixed $storesId Store ID to filter by.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered stores and their corresponding grid view.
     */
    public function filterStoreCard($statesId, $citiesId, $storesId)
    {
        $statesId = $statesId == "false" ? null : $statesId;
        $citiesId = $citiesId == "false" ? null : $citiesId;
        $storesId = $storesId == "false" ? null : $storesId;

        $query = Store::select("stores.*")
            ->join("cities", "stores.cities_id", "cities.id")
            ->join("states", "cities.states_id", "states.id")
            ->where("stores.active", EnumStatus::ACTIVE)
            ->where("stores.id", "!=", 1);

        if (!is_null($statesId)) {
            $query = $query->where("cities.states_id", $statesId);
        }

        if (!is_null($citiesId)) {
            $query = $query->where("stores.cities_id", $citiesId);
        }

        if (!is_null($storesId)) {
            $query = $query->where("stores.id", $storesId);
        }

        $listStores = $query->orderBy("stores.name", EnumOrderBy::ASC)->get();
        return response()->json([
            "status" => 1,
            "grid" => view("site.includes.store_info", compact("listStores"))->render(),
        ]);
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
