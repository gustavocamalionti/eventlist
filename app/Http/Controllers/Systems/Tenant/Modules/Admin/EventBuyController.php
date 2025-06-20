<?php

namespace App\Http\Controllers\Panel;

#region Import Libraries
use App\Libs\Utils;
use App\Models\Buy;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Models\Citie;
use App\Models\State;
use App\Models\Store;
use App\Libs\ViewsModules;
use App\Models\FormContent;
use Illuminate\Http\Request;
#endregion

#region Import Requests
#endregion

#region Import Services
use App\Libs\Enums\EnumForms;
use App\Libs\Enums\EnumOrderBy;
use Yajra\DataTables\DataTables;
use App\Libs\Enums\EnumErrorsType;
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Services\Crud\CrudBuyService;
use App\Services\Crud\CrudLinkService;
#endregion

#region Import Models
use App\Services\Crud\CrudBrandService;
use App\Services\Crud\CrudCitieService;
use App\Services\Crud\CrudStateService;
use App\Services\Crud\CrudStoreService;
use Illuminate\Database\QueryException;
use App\Services\Crud\CrudVoucherService;
use App\Services\Crud\CrudWebhookService;
use App\Services\Crud\CrudLogAuditService;
use App\Services\Crud\CrudLogEmailService;
use App\Services\Crud\CrudParameterService;
use App\Services\Crud\CrudFormSubjectService;
use App\Services\Panel\Rules\RulesFilesService;
use App\Http\Controllers\Controller as Controller;
use App\Services\Panel\Rules\RulesMaintenanceService;

#endregion

#region Import Jobs
#endregion

/**
 * Controller responsible for managing the contact form content in the administration panel.
 */
class EventBuyController extends Controller
{
    #region variables
    protected $crudParameterService;
    protected $crudBuyService;
    protected $crudVoucherService;
    protected $crudBrandService;
    protected $crudStoreService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $crudLogAuditService;
    protected $crudLogEmailService;
    protected $crudWebhookService;

    protected $rulesArchivedService;
    protected $rulesFilesService;
    protected $rulesMaintenanceService;
    #endregion

    #region _construct
    /**
     * Class constructor, initializes necessary services and sets up permission middlewares.
     *
     * @param CrudBuyService $crudBuyService Service for handling buys.
     * @param CrudVoucherService $crudVoucherService Service for handling vouchers.
     * @param CrudParameterService $crudParameterService Service for handling parameters.
     * @param CrudBrandService $crudStoreService Service for handling brands.
     * @param CrudStoreService $crudStoreService Service for handling stores.
     * @param CrudStateService $crudStateService Service for handling states.
     * @param CrudCitieService $crudCitieService Service for handling cities.
     * @param CrudLogAuditService $crudLogAuditService Service for handling audit logs.
     * @param CrudLogEmailService $CrudLogEmailService Service for handling logs emails.
     * @param CrudWebhookService $CrudWebhookService Service for handling webhooks.
     * @param RulesFilesService $rulesFilesService Service for file validation rules.
     * @param RulesMaintenanceService $rulesMaintenanceService Service for maintenance rules.
     * @return void
     */
    public function __construct(
        CrudBuyService $crudBuyService,
        CrudVoucherService $crudVoucherService,
        CrudParameterService $crudParameterService,
        CrudBrandService $crudBrandService,
        CrudStoreService $crudStoreService,
        CrudStateService $crudStateService,
        CrudCitieService $crudCitieService,
        CrudLogAuditService $crudLogAuditService,
        CrudLogEmailService $crudLogEmailService,
        CrudWebhookService $crudWebhookService,
        RulesFilesService $rulesFilesService,
        RulesMaintenanceService $rulesMaintenanceService
    ) {
        $this->crudBuyService = $crudBuyService;
        $this->crudVoucherService = $crudVoucherService;
        $this->crudParameterService = $crudParameterService;
        $this->crudBrandService = $crudBrandService;
        $this->crudStoreService = $crudStoreService;
        $this->crudStateService = $crudStateService;
        $this->crudCitieService = $crudCitieService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudLogEmailService = $crudLogEmailService;
        $this->crudWebhookService = $crudWebhookService;
        $this->rulesFilesService = $rulesFilesService;
        $this->rulesMaintenanceService = $rulesMaintenanceService;

        $this->middleware("can:read_event_buys")->only(["eventBuysList", "eventBuysFilters"]);
    }
    #endregion

    /**
     * Lists all available contact form contents.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of contacts or a JSON response in case of an error.
     */
    public function eventBuysList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::EVENT_BUY;
            $parameters = $this->crudParameterService->findById(1);
            $brands = $this->crudBrandService->getAll();
            return view("panel.pages.event.buys_list", compact("pageTitle", "parameters", "brands"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::EVENT_BUY,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::EVENT_BUY,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Filters contact form contents based on request parameters.
     *
     * @param Request $request Object containing request parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with filtered data.
     */
    public function eventBuysFilters(Request $request)
    {
        #region content
        try {
            /**
             * OS campos "add column" não será afetado pela caixa de pesquisa,
             * portanto precisamos considerar esse caso tratando individualmente
             */
            $search = $request->input("search.value");
            $query = Buy::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->orWhereHas("customers", function ($q) use ($search) {
                        $q->where("name", "LIKE", "%{$search}%")
                            ->orWhere("email", "LIKE", "%{$search}%")
                            ->orWhere("phone_cell", "LIKE", "%{$search}%");
                    });

                    $q->orWhereHas("stores", function ($q) use ($search) {
                        $q->where("name", "LIKE", "%{$search}%");
                    });

                    $q->orWhere("value", "LIKE", "%{$search}%");
                    $q->orWhere("net_value", "LIKE", "%{$search}%");

                    // Filtro especial para nomes de status
                    $statusMatches = EnumStatusBuies::searchCodesByPartialName($search);
                    if (!empty($statusMatches)) {
                        $q->orWhereIn("status", $statusMatches);
                    }

                    $formattedSearch = str_replace("/", "-", $search); // trata formatos tipo 21/05/2025
                    $q->orWhereRaw("DATE_FORMAT(updated_at, '%d/%m/%Y') LIKE ?", ["%{$search}%"]);
                });
            }

            if ($request->selFilterStatus != "") {
                $query->where("status", $request->selFilterStatus);
            }

            if ($request->selFilterBrands != "") {
                $query = $query->whereHas("stores", function ($q) use ($request) {
                    $q->where("brands_id", $request->selFilterBrands);
                });
            }

            $query = $this->crudBuyService->filterByDate($request, $query);

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "actions",
                1 => "brands_id",
                2 => "stores_id",
                3 => "states",
                4 => "name",
                5 => "email",
                6 => "phone_cell",
                7 => "method_payments_id",
                8 => "qtd_vouchers",
                9 => "value",
                10 => "net_value",
                11 => "status",
                12 => "updated_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("updated_at", "DESC");
            }

            return DataTables::of($query)
                ->addColumn("name", function ($item) {
                    return optional($item->customers)->name;
                })

                ->addColumn("email", function ($item) {
                    return optional($item->customers)->email;
                })

                ->addColumn("phone_cell", function ($item) {
                    return Utils::MaskPhone(optional($item->customers)->phone_cell);
                })

                ->addColumn("brands_id", function ($item) {
                    return $item->stores->brands->title;
                })
                ->addColumn("stores_id", function ($item) {
                    return $item->stores->name;
                })
                ->addColumn("states", function ($item) {
                    return $item->stores->cities->states->initials;
                })
                ->addColumn("method_payments_id", function ($item) {
                    return optional($item->methodPayment)->name;
                })
                ->addColumn("qtd_vouchers", function ($item) {
                    $qtdVoucher = $item->qtd_vouchers;
                    if ($qtdVoucher == null) {
                        $qtdVoucher = $this->crudVoucherService->getAll(["buys_id" => $item->id])->count();
                    }
                    return str_pad($qtdVoucher, 2, "0", STR_PAD_LEFT);
                })
                ->addColumn("value", function ($item) {
                    return number_format($item->value, 2, ",", ".");
                })
                ->addColumn("net_value", function ($item) {
                    return number_format($item->net_value, 2, ",", ".");
                })
                ->addColumn("status", function ($item) {
                    $status = EnumStatusBuies::mapStatusToInternalInfo($item->status);
                    return '<span class="badge text-center ' .
                        $status->badge .
                        ' fw-semibold py-1 px-3" style=";width: 140px; min-width: 140px; white-space: nowrap;">
                <i class="' .
                        optional($status)->icon .
                        ' me-1" style="font-size: 1em; vertical-align: center;"></i> ' .
                        optional($status)->name .
                        "</span>";
                })

                ->addColumn("updated_at", function ($item) {
                    return date("d/m/Y H:i:s", strtotime($item->updated_at));
                })
                ->addColumn("actions", function ($item) {
                    return view("panel.pages.event._includes.event_buys_actions_grid", compact("item"))->render();
                })
                ->rawColumns(["name", "email", "phone_cell", "status", "actions"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::EVENT_BUY,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::EVENT_BUY,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }

    /**
     * Retrieves the audit history of form configurations.
     *
     * @param int $formConfigsId ID of the form configuration to retrieve history for.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with audit history data.
     */
    public function getBuysHistory($buysId)
    {
        try {
            Utils::maxOptimizations();
            $vouchers = $this->crudVoucherService->getAll(["buys_id" => $buysId], ["created_at" => EnumOrderBy::DESC]);
            $webhooks = $this->crudWebhookService->getAll(["buys_id" => $buysId], ["created_at" => EnumOrderBy::DESC]);
            $emails = $this->crudLogEmailService->getAll(["buys_id" => $buysId], ["created_at" => EnumOrderBy::DESC]);
            // dd($vouchers, $buysId);
            return response()->json([
                "status" => 1,
                "gridEventVouchers" => view(
                    "panel.pages.event._includes.event_vouchers_tbody_modal",
                    compact("vouchers")
                )->render(),
                "gridEventEmails" => view(
                    "panel.pages.event._includes.event_emails_tbody_modal",
                    compact("emails")
                )->render(),
                "gridEventWebhooks" => view(
                    "panel.pages.event._includes.event_webhooks_tbody_modal",
                    compact("webhooks")
                )->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::FORM_CONFIG,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
