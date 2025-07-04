<?php

namespace App\Http\Controllers\Common\Admin;

use App\Libs\Utils;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Models\Common\Webhook;
use App\Libs\Enums\EnumOrderBy;
use App\Models\Common\LogEmail;
use App\Models\Common\LogError;

use App\Models\Common\LogAudits;
use Yajra\DataTables\DataTables;
use App\Libs\Enums\EnumErrorsType;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Common\Controller;
use App\Services\Common\Crud\CrudWebhookService;
use App\Services\Common\Crud\CrudLogAuditService;
use App\Services\Common\Crud\CrudLogEmailService;
use App\Services\Common\Crud\CrudLogErrorService;
use App\Services\Systems\Tenant\Crud\CrudParameterService;

/**
 * Controller for managing log-related actions such as displaying logs for emails, audits, and errors.
 * Handles the filtering and viewing of log data with proper access control and error handling.
 */
class LogController extends Controller
{
    protected $crudParameterService;
    protected $crudUserService;
    protected $crudLogAuditService;
    protected $crudLogEmailService;
    protected $crudLogErrorService;
    protected $crudWebhookService;
    protected $crudStateService;
    protected $crudCitieService;
    protected $rulesMaintenanceService;

    public function __construct(
        CrudParameterService $crudParameterService,
        CrudLogAuditService $crudLogAuditService,
        CrudLogEmailService $crudLogEmailService,
        CrudLogErrorService $crudLogErrorService,
        CrudWebhookService $crudWebhookService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudLogAuditService = $crudLogAuditService;
        $this->crudLogEmailService = $crudLogEmailService;
        $this->crudLogErrorService = $crudLogErrorService;
        $this->crudWebhookService = $crudWebhookService;

        $this->middleware("can:read_log_audits")->only(["logAuditsList", "logAuditsFilters"]);
        $this->middleware("can:read_log_emails")->only(["logEmailsList", "logEmailsFilters"]);
        $this->middleware("can:read_log_errors")->only(["logErrorsList", "logErrorsFilters"]);
        $this->middleware("can:read_log_webhooks")->only(["logWebhooksList", "logWebhooksFilters"]);
    }

    /**
     * Show the list of log emails.
     *
     * @return \Illuminate\View\View The view for the log emails list.
     */
    public function logEmailsList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_LOG_EMAILS;
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.logs.log_emails_list";

            return view($viewPath, compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_EMAILS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_EMAILS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filter the log emails based on the provided request parameters.
     *
     * @param Request $request The request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response with the filtered log emails.
     */
    public function logEmailsFilters(Request $request)
    {
        try {
            $query = LogEmail::query();

            $query = $this->crudLogEmailService->filterByDate($request, $query);

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "uuid",
                1 => "job_title",
                2 => "email",
                3 => "users_id",
                4 => "status",
                5 => "details",
                6 => "created_at",
                7 => "updated_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("created_at", "desc");
            }

            return DataTables::of($query)
                ->addColumn("created_at", function ($item) {
                    return $item->created_at != null ? date("d/m/Y H:i:s", strtotime($item->created_at)) : "";
                })
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Show the list of log audits.
     *
     * @return \Illuminate\View\View The view for the log audits list.
     */
    public function logAuditsList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_LOG_AUDIT;
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.logs.log_audits_list";
            return view($viewPath, compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_AUDIT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_AUDIT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filter the log audits based on the provided request parameters.
     *
     * @param Request $request The request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response with the filtered log audits.
     */
    public function logAuditsFilters(Request $request)
    {
        try {
            $query = LogAudits::query();

            $query = $this->crudLogAuditService->filterByDate($request, $query);

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "route",
                3 => "action",
                4 => "description",
                2 => "title",
                5 => "table_name",
                6 => "method",
                7 => "users_name",
                8 => "users_email",
                9 => "ip",
                10 => "created_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("created_at", "desc");
            }

            return DataTables::of($query)
                ->addColumn("created_at", function ($item) {
                    return $item->created_at != null ? date("d/m/Y H:i:s", strtotime($item->created_at)) : "";
                })
                ->addColumn("details", function ($item) {
                    return view(
                        "legacy.systems.tenant.modules.admin.layouts._details_audit",
                        compact("item")
                    )->render();
                })
                ->rawColumns(["details"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_AUDIT,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_AUDIT,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Get the audit history for a specific log entry.
     *
     * @param int $logId The ID of the log entry.
     * @return \Illuminate\Http\JsonResponse JSON response containing the audit history.
     */
    public function getLogAuditHistory($logId)
    {
        try {
            Utils::maxOptimizations();
            $logAudit = $this->crudLogAuditService->getAll(["id" => $logId], ["created_at" => EnumOrderBy::DESC]);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.logs._includes.tbody_audit_modal";
            return response()->json([
                "status" => 1,
                "grid" => view("panel.pages.logs._includes.tbody_audit_modal", compact("logAudit"))->render(),
            ]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_EMAILS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_EMAILS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Show the list of log errors.
     *
     * @return \Illuminate\View\View The view for the log errors list.
     */
    public function logErrorsList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_LOG_ERRORS;
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.logs.log_errors_list";

            return view($viewPath, compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filter the log errors based on the provided request parameters.
     *
     * @param Request $request The request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response with the filtered log errors.
     */
    public function logErrorsFilters(Request $request)
    {
        try {
            $query = LogError::query();

            $query = $this->crudLogErrorService->filterByDate($request, $query);
            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "route",
                2 => "action",
                3 => "route",
                4 => "title",
                5 => "method",
                6 => "users_name",
                7 => "users_email",
                8 => "status",
                9 => "message",
                10 => "ip",
                11 => "created_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("created_at", "desc");
            }

            return DataTables::of($query)
                ->addColumn("status", function ($item) {
                    $value = "Erro Desconhecido";
                    switch ($item->status) {
                        case EnumErrorsType::SQL:
                            $value = "Erro de Banco de Dados";
                            break;

                        case EnumErrorsType::GENERIC:
                            $value = "Erro de Execução";
                            break;

                        default:
                            # code...
                            break;
                    }

                    return $value;
                })
                ->addColumn("created_at", function ($item) {
                    return $item->created_at != null ? date("d/m/Y H:i:s", strtotime($item->created_at)) : "";
                })
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_ERRORS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Show the list of log errors.
     *
     * @return \Illuminate\View\View The view for the log errors list.
     */
    public function logWebhooksList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_LOG_WEBHOOKS;
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.logs.log_webhooks_list";

            return view($viewPath, compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_WEBHOOKS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_WEBHOOKS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Filter the log errors based on the provided request parameters.
     *
     * @param Request $request The request containing filter parameters.
     * @return \Illuminate\Http\JsonResponse JSON response with the filtered log errors.
     */
    public function logWebhooksFilters(Request $request)
    {
        try {
            $query = Webhook::query();

            $query = $this->crudWebhookService->filterByDate($request, $query);

            // Capturar parâmetros de ordenação enviados pelo DataTables
            $columns = [
                0 => "id",
                1 => "buys_id",
                2 => "events_id",
                3 => "event_type",
                4 => "should_treat",
                5 => "status",
                6 => "created_at",
            ];

            $orderColumnIndex = $request->input("order.0.column");
            $orderDirection = $request->input("order.0.dir", "asc");

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            } else {
                $query->orderBy("created_at", "desc");
            }

            return DataTables::of($query)
                ->addColumn("status", function ($item) {
                    $isProcessed = $item->status == \App\Libs\Enums\EnumStatus::ACTIVE;

                    return '<span class="' .
                        ($isProcessed ? "text-success" : "text-danger") .
                        '">
                    <i class="fas ' .
                        ($isProcessed ? "fa-check" : "fa-times") .
                        '"></i> ' .
                        ($isProcessed ? "Processado" : "Não Processado") .
                        "</span>";
                })
                ->addColumn("buys_id", function ($item) {
                    return $item->buys->customers->name;
                })
                ->addColumn("should_treat", function ($item) {
                    $shouldTreat = $item->should_treat == \App\Libs\Enums\EnumStatus::ACTIVE;

                    return '<span class="' .
                        ($shouldTreat ? "text-success" : "text-warning") .
                        '">
                    <i class="fas ' .
                        ($shouldTreat ? "fa-check" : "fa-times") .
                        '"></i> ' .
                        ($shouldTreat ? "Tratar" : "Não Tratar") .
                        "</span>";
                })
                ->addColumn("created_at", function ($item) {
                    return $item->created_at != null ? date("d/m/Y H:i:s", strtotime($item->created_at)) : "";
                })
                ->rawColumns(["status", "should_treat"])
                ->make(true);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_LOG_WEBHOOKS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_LOG_WEBHOOKS,
                Actions::FILTER,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
