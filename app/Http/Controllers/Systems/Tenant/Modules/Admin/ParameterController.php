<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Database\QueryException;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumErrorsType;

use App\Http\Requests\Panel\ParametersRequest;

use App\Services\Crud\CrudParameterService;

/**
 * Controller responsible for handling the parameters page in the admin panel.
 */
class ParameterController extends Controller
{
    protected $crudParameterService;
    protected $linkService;

    /**
     * Class constructor, initializes necessary services.
     *
     * @param CrudParameterService $crudParameterService Service for handling parameter-related actions.
     * @return void
     */
    public function __construct(CrudParameterService $crudParameterService)
    {
        $this->crudParameterService = $crudParameterService;
    }

    /**
     * Displays the parameters page with the current configuration.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse Returns the parameters page view with relevant data or an error response in case of failure.
     */
    public function parametersList()
    {
        try {
            $pageTitle = ViewsModules::PANEL_PARAMETERS;
            $parameters = $this->crudParameterService->findById(1);
            return view("panel.pages.parameters.parameters", compact("pageTitle", "parameters"));
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_PARAMETERS,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_PARAMETERS,
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
    public function parametersUpdate(ParametersRequest $request)
    {
        try {
            $this->crudParameterService->update(1, $request->all());
            return response()->json(["status" => 1]);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::PANEL_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::PANEL_PARAMETERS,
                Actions::SAVE,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
