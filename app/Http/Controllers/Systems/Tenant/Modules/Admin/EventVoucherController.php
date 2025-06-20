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
use App\Models\Voucher;
use App\Libs\ViewsModules;
use App\Models\FormContent;
#endregion

#region Import Requests
#endregion

#region Import Services
use Illuminate\Http\Request;
use App\Libs\Enums\EnumForms;
use App\Libs\Enums\EnumOrderBy;
use Yajra\DataTables\DataTables;
use App\Libs\Enums\EnumErrorsType;
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Services\Crud\CrudBuyService;
#endregion

#region Import Models
use App\Services\Crud\CrudLinkService;
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
class EventVoucherController extends Controller
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

        $this->middleware("can:read_event_vouchers")->only(["eventVouchersList", "eventVouchersFilters"]);
    }
    #endregion

    /**
     * Lists all available contact form contents.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the view with the list of contacts or a JSON response in case of an error.
     */
    public function eventVouchersList()
    {
        #region content
        try {
            $pageTitle = ViewsModules::EVENT_VOUCHER;
            $parameters = $this->crudParameterService->findById(1);
            $brands = $this->crudBrandService->getAll();
            return view("panel.pages.event.vouchers_list", compact("pageTitle", "parameters", "brands"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::EVENT_VOUCHER,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::EVENT_VOUCHER,
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
    public function eventVouchersFilters(Request $request)
    {
        #region content
        // dd("oi");
        try {
            /**
             * OS campos "add column" não será afetado pela caixa de pesquisa,
             * portanto precisamos considerar esse caso tratando individualmente
             */
            $search = $request->input("search.value");
            $query = Voucher::query();

            if ($request->selFilterStatus != "") {
                $query->where("active", $request->selFilterStatus);
            }

            $query = $this->crudVoucherService->filterByDate($request, $query);

            if ($request->selFilterBrands != "") {
                $query = $query->whereHas("stores", function ($q) use ($request) {
                    $q->where("brands_id", $request->selFilterBrands);
                });
            }

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "brands_id",
                1 => "stores_id",
                2 => "states",
                3 => "name",
                4 => "email",
                5 => "positions_id",
                6 => "tshirt",
                7 => "value",
                8 => "active",
                9 => "updated_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");
            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("updated_at", "DESC");
            }

            return DataTables::of($query)
                ->addColumn("customer_email", function ($item) {
                    return optional($item->buys->customers)->email;
                })
                ->addColumn("stores_id", function ($item) {
                    return $item->stores->name;
                })
                ->addColumn("states", function ($item) {
                    return $item->stores->cities->states->initials;
                })
                ->addColumn("positions_id", function ($item) {
                    return $item->positions->name;
                })
                ->addColumn("brands_id", function ($item) {
                    return $item->stores->brands->title;
                })
                ->addColumn("value", function ($item) {
                    return number_format($item->value / 100, 2, ",", ".");
                })
                ->addColumn("active", function ($item) {
                    $isActive = $item->active == \App\Libs\Enums\EnumStatus::ACTIVE;

                    return '<span class="' .
                        ($isActive ? "text-success" : "text-danger") .
                        '">
        <i class="fas ' .
                        ($isActive ? "fa-check" : "fa-times") .
                        '"></i> ' .
                        ($isActive ? "Ativo" : "Inativo") .
                        "</span>";
                })

                ->addColumn("updated_at", function ($item) {
                    return date("d/m/Y H:i:s", strtotime($item->updated_at));
                })

                ->rawColumns(["customer_email", "active"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::EVENT_VOUCHER,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::EVENT_VOUCHER,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
