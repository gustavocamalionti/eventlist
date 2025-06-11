<?php

namespace App\Http\Controllers\Tenant\Admin;

#region Import Libraries
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumErrorsType;
#endregion

#region Import Requests
#endregion

#region Import Services
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Jobs\Webhooks\HandleGenericJob;
use Illuminate\Database\QueryException;
use App\Services\Crud\CrudParameterService;
use App\Http\Controllers\Controller as Controller;
#endregion

#region Import Models
#endregion

#region Import Jobs
#endregion

/**
 * Controller responsible for managing the store page of the site.
 */
class WebhookController extends Controller
{
    #region variables
    protected $crudParameterService;
    #endregion

    #region _construct
    /**
     * Constructor to initialize services.
     *
     * @param CrudParameterService $crudParameterService
     * @return void
     */
    public function __construct(CrudParameterService $crudParameterService)
    {
        $this->crudParameterService = $crudParameterService;
    }
    #endregion

    /**
     * Display the stores page with necessary parameters and states.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        #region content
        try {
            Log::info("entrei1");
            $body = $request->all();
            Log::info("entrei2");

            $event = $body["event"] ?? null;
            Log::info("entrei3");

            if (!$event) {
                Log::warning("Evento ausente no payload recebido");
                return response()->json(["message" => "Evento ausente"], 400);
            }
            Log::info("entrei4");

            $payment = $body["payment"] ?? [];
            $mapEventToWebhookInfo = EnumStatusBuies::mapEventToWebhookInfo($event);
            $shouldTreatWebhook = $mapEventToWebhookInfo != null ? EnumStatus::ACTIVE : EnumStatus::INACTIVE;

            Log::info("entrei5");
            if ($shouldTreatWebhook) {
                $jobClass = $mapEventToWebhookInfo->jobClass;
                dispatch(new $jobClass($body));
            } else {
                dispatch(new HandleGenericJob($body));
            }
            Log::info("entrei6");

            return response()->json(["message" => "Webhook received successfully"], 200);
        } catch (QueryException $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::WEBHOOK,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (\Exception $e) {
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::WEBHOOK,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
        #endregion
    }
}
