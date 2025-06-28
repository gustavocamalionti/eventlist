<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Models\Store;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use Yajra\DataTables\DataTables;
use App\Libs\Enums\EnumErrorsType;

use Illuminate\Support\Facades\Log;
use App\Services\Crud\CrudBrandService;

use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudStoreService;
use Illuminate\Database\QueryException;
use App\Services\Crud\CrudVoucherService;
use App\Services\Crud\CrudLogAuditService;

use App\Services\Crud\CrudParameterService;
use App\Http\Requests\Panel\SaveStoresRequest;
use App\Http\Requests\Panel\UpdateStoresRequest;
use App\Http\Controllers\Controller as Controller;
use App\Services\Panel\Rules\RulesMaintenanceService;

/**
 * Controller responsible for handling store-related actions in the admin panel.
 */
class StoreController extends Controller
{
    protected $crudParameterService;
    protected $crudStoreService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $crudLogAuditService;
    protected $crudBrandService;
    protected $crudVoucherService;

    protected $rulesArchivedService;
    protected $rulesMaintenanceService;

    /**
     * Class constructor, initializes necessary services and middleware for authorization.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameter-related actions.
     * @param CrudStoreService $crudStoreService Service for handling store-related actions.
     * @param CrudStateService $crudStateService Service for handling state-related actions.
     * @param CrudCitieService $crudCitieService Service for handling city-related actions.
     * @param crudBrandService $crudBrandService Service for handling brand-related actions.
     * @param CrudVoucherService $crudVoucherService Service for handling voucher-related actions.
     * @param CrudLogAuditService $crudLogAuditService Service for handling log audit actions.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for handling rules maintenance actions.
     * @return void
     */
    public function __construct(
        CrudParameterService $crudParameterService,
        CrudStoreService $crudStoreService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        CrudBrandService $crudBrandService,
        CrudVoucherService $crudVoucherService,
        CrudLogAuditService $crudLogAuditService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudStoreService = $crudStoreService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->crudBrandService = $crudBrandService;
        $this->crudVoucherService = $crudVoucherService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_stores")->only(["storesList", "storesFilters"]);
        $this->middleware("can:read_stores_audit")->only(["getStoreHistory"]);
        $this->middleware("can:create_stores")->only(["storesMaintenance", "storesStore"]);
        $this->middleware("can:update_stores")->only(["storesMaintenance", "storesUpdate"]);
        $this->middleware("can:delete_stores")->only(["storesDelete"]);
    }

    /**
     * Displays the list of stores in the admin panel.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the store list view with necessary data or an error response in case of failure.
     */
    public function storesList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_STORES;
            $parameters = $this->crudParameterService->findById(1);
            $brands = $this->crudBrandService->getAll();
            return view("panel.pages.stores.stores_list", compact("pageTitle", "parameters", "brands"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filters the list of stores based on the given criteria (e.g., status, sorting).
     *
     * @param Request $request The incoming request with filter parameters.
     * @return \Illuminate\Http\JsonResponse Returns the filtered store list as a DataTables response.
     */
    public function storesFilters(Request $request)
    {
        try {
            $query = Store::query()
                ->where("id", "!=", 1)
                ->select("*") // ou os campos que precisar
                ->selectSub(function ($q) {
                    $q->selectRaw("COUNT(*)")
                        ->from("vouchers")
                        ->whereColumn("vouchers.stores_id", "stores.id")
                        ->where("vouchers.active", EnumStatus::ACTIVE);
                }, "qtd_vouchers")
                ->selectSub(function ($q) {
                    $q->selectRaw("CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END")
                        ->from("vouchers")
                        ->whereColumn("vouchers.stores_id", "stores.id")
                        ->where("vouchers.active", EnumStatus::ACTIVE);
                }, "is_subscribe")
                ->selectSub(function ($q) {
                    $q->selectRaw("GROUP_CONCAT(vouchers.tshirt SEPARATOR ', ')")
                        ->from("vouchers")
                        ->whereColumn("vouchers.stores_id", "stores.id")
                        ->where("vouchers.active", EnumStatus::ACTIVE);
                }, "tshirts");

            if ($request->selFilterStatus != "") {
                $query->where("active", $request->selFilterStatus);
            }

            if ($request->selFilterBrands != "") {
                $query->where("brands_id", $request->selFilterBrands);
            }

            // Como is_subscribe não é um campo real da tabela stores
            // (é uma subquery com alias), o Laravel/Eloquent não
            // permite fazer o filtro diretamente
            if ($request->selFilterSubscribe != "") {
                $operator = $request->selFilterSubscribe ? "> 0" : "= 0";

                //Somente retorne as lojas (stores) para as quais a quantidade
                // de vouchers ativos seja maior que zero ou igual a zero,
                // dependendo do valor de $request->selFilterSubscribe.
                $query->whereRaw(
                    "(
                        SELECT COUNT(*)
                        FROM vouchers
                        WHERE vouchers.stores_id = stores.id
                        AND vouchers.active = ?
                    ) $operator",
                    [EnumStatus::ACTIVE]
                );
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "brands_id",
                2 => "name",
                3 => "states",
                4 => "email",
                5 => "cnpj",
                6 => "phone1",
                7 => "is_subscribe",
                8 => "qtd_vouchers",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");
            $orderBy = $columns[$orderColumnIndex] ?? "name";

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection)->get();
            } else {
                $query->orderBy("name", "ASC")->get();
            }

            return DataTables::of($query)
                ->addColumn("actions", function ($item) {
                    return view("panel.pages.stores._includes.actions_grid", compact("item"))->render();
                })
                ->addColumn("zipcode", function ($item) {
                    return Utils::MaskFields($item->zipcode, "#####-###");
                })
                ->addColumn("brands_id", function ($item) {
                    return $item->brands->title;
                })
                ->addColumn("cnpj", function ($item) {
                    return Utils::MaskFields($item->cnpj, "##.###.###/####-##");
                })
                ->addColumn("phone_cell", function ($item) {
                    return Utils::MaskPhone($item->phone1);
                })
                ->addColumn("states", function ($item) {
                    return $item->cities->states->initials;
                })
                ->addColumn("cities", function ($item) {
                    return $item->cities->name;
                })
                ->addColumn("is_subscribe", function ($item) {
                    return "<span class='badge " .
                        ($item->is_subscribe ? "bg-success" : "bg-danger") .
                        "'>" .
                        ($item->is_subscribe ? "Sim" : "Não") .
                        "</span>";
                })
                ->addColumn("qtd_vouchers", function ($item) {
                    return str_pad($item->qtd_vouchers, 2, "0", STR_PAD_LEFT);
                })
                ->addColumn("tshirts", function ($item) {
                    return $item->tshirts;
                })
                ->rawColumns(["actions", "is_subscribe", "qtd_vouchers"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Retrieves the audit history of a store.
     *
     * @param int $storeId The ID of the store for which the audit history is to be retrieved.
     * @return \Illuminate\Http\JsonResponse Returns the store's audit history as a JSON response.
     */
    public function getStoreHistory($storeId)
    {
        try {
            Utils::maxOptimizations();
            $storeAudit = $this->crudLogAuditService->getAll(
                ["table_name" => "stores", "table_item_id" => $storeId],
                ["created_at" => EnumOrderBy::DESC]
            );

            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.stores._includes.tbody_audit_modal", compact("storeAudit"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                2,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                3,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Displays the maintenance page for creating or editing a store.
     *
     * @param int $id The ID of the store to be edited, or 0 for a new store.
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the maintenance view or an error response in case of failure.
     */
    public function storesMaintenance($id = 0)
    {
        try {
            $pageTitle = ViewsModules::PANEL_STORES;

            $parameters = $this->crudParameterService->findById(1);
            $store = $this->rulesMaintenanceService->getRegisterMaintenance($this->crudStoreService, $id);

            $states = $this->crudStateService->getAll([], ["initials" => EnumOrderBy::ASC]);
            $cities = null;
            if ($store != null) {
                $cities = $this->crudCitieService->getAll(["states_id" => $store->cities->states_id]);
            }

            $subTitle = "Novo Cadastro";
            if ($store != null) {
                $subTitle = $store->name;
            }

            $brands = $this->crudBrandService->getAll([], ["id" => EnumOrderBy::ASC]);

            return view(
                "panel.pages.stores.stores_maintenance",
                compact("pageTitle", "subTitle", "parameters", "store", "states", "cities", "brands")
            );
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Saves a new store an database.
     *
     * @param SaveStoresRequest $request The request containing the store data.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function storesStore(SaveStoresRequest $request)
    {
        try {
            $this->crudStoreService->save($request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Updates an existing store's information.
     *
     * @param UpdateStoresRequest $request The request containing the updated store data.
     * @param int $id The ID of the store to be updated.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function storesUpdate(UpdateStoresRequest $request, $id)
    {
        try {
            $this->crudStoreService->update($id, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::SAVE_OR_UPDATE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Deletes a store from the database.
     *
     * @param int $id The ID of the store to be deleted.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function storesDelete($id)
    {
        try {
            $this->crudStoreService->delete($id);
            // $listStores = $this->crudStoreService->getAll([],['name'=> EnumOrderBy::ASC]);

            return response()->json([
                "status" => 1,
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_STORES,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_STORES,
                Actions::DELETE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
