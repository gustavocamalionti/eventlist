<?php

namespace App\Http\Controllers\Common\Admin;

use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;

use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Common\Controller;
use App\Http\Requests\Common\ConfigContentsRequest;
use App\Http\Requests\Common\ConfigParametersRequest;
use App\Services\Common\Crud\CrudCustomizationService;
use App\Services\Systems\Tenant\Crud\CrudParameterService;
use App\Http\Requests\Systems\Master\Modules\admin\ConfigColorsRequest;

/**
 * Controller for managing log-related actions such as displaying logs for emails, audits, and errors.
 * Handles the filtering and viewing of log data with proper access control and error handling.
 */
class ConfigController extends Controller
{
    protected $crudParameterService;
    protected $crudCustomizationService;

    public function __construct(
        CrudParameterService $crudParameterService,
        CrudCustomizationService $crudCustomizationService
    ) {
        $this->crudParameterService = $crudParameterService;
        $this->crudCustomizationService = $crudCustomizationService;

        // $this->middleware("can:read_log_audits")->only(["logAuditsList", "logAuditsFilters"]);
        // $this->middleware("can:read_log_emails")->only(["logEmailsList", "logEmailsFilters"]);
        // $this->middleware("can:read_log_errors")->only(["logErrorsList", "logErrorsFilters"]);
        // $this->middleware("can:read_log_webhooks")->only(["logWebhooksList", "logWebhooksFilters"]);
    }

    /**
     * Show the list of config colors list.
     *
     * @return \Illuminate\View\View The view for the config colors list.
     */
    public function indexConfigColors()
    {
        try {
            $pageTitle = ViewsModules::PANEL_CONFIG_COLORS;
            $subTitle = "Atualizar";
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.configs.colors";

            return view($viewPath, compact("pageTitle", "subTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_COLORS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_COLORS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Updates the parameters with the provided data.
     *
     * @param ParametersRequest $request The request containing the updated parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function updateConfigColors(ConfigColorsRequest $request)
    {
        try {
            $this->crudParameterService->update(1, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Show the list of config colors list.
     *
     * @return \Illuminate\View\View The view for the config colors list.
     */
    public function indexConfigContents()
    {
        try {
            $pageTitle = ViewsModules::PANEL_CONFIG_CONTENTS;
            $subTitle = "Atualizar";
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.configs.contents";

            return view($viewPath, compact("pageTitle", "subTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_CONTENTS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_CONTENTS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Updates the parameters with the provided data.
     *
     * @param ParametersRequest $request The request containing the updated parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function updateConfigContents(ConfigContentsRequest $request)
    {
        try {
            $this->crudParameterService->update(1, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Show the list of config colors list.
     *
     * @return \Illuminate\View\View The view for the config colors list.
     */
    public function indexConfigParameters()
    {
        try {
            $subTitle = "Atualizar";
            $pageTitle = ViewsModules::PANEL_CONFIG_PARAMETERS;
            $parameters = $this->crudParameterService->findById(1);
            $viewBase = $this->isTenant() ? "tenant" : "master";
            $viewPath = "legacy.systems.{$viewBase}.modules.admin.pages.configs.parameters";

            return view($viewPath, compact("pageTitle", "subTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Updates the parameters with the provided data.
     *
     * @param ParametersRequest $request The request containing the updated parameters.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with status information.
     */
    public function updateConfigParameters(ConfigParametersRequest $request)
    {
        try {
            $this->crudParameterService->update(1, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_CONFIG_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
