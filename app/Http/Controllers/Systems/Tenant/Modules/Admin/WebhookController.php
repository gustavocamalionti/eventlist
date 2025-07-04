<?php

namespace App\Http\Controllers\Systems\Tenant\Modules\Admin;

use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumOrderBy;
use App\Libs\Enums\EnumErrorsType;

use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Jobs\Webhooks\HandleGenericJob;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Common\Controller;
use App\Services\Systems\Tenant\Crud\CrudParameterService;

/**
 * Controller responsible for managing the store page of the site.
 */
class WebhookController extends Controller
{
    protected $crudParameterService;

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

    /**
     * Display the stores page with necessary parameters and states.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $body = $request->all();

            $event = $body["event"] ?? null;

            if (!$event) {
                Log::warning("Evento ausente no payload recebido");
                return response()->json(["message" => "Evento ausente"], 400);
            }

            $payment = $body["payment"] ?? [];
            $mapEventToWebhookInfo = EnumStatusBuies::mapEventToWebhookInfo($event);
            $shouldTreatWebhook = $mapEventToWebhookInfo != null ? EnumStatus::ACTIVE : EnumStatus::INACTIVE;

            if ($shouldTreatWebhook) {
                $jobClass = $mapEventToWebhookInfo->jobClass;
                dispatch(new $jobClass($body));
            } else {
                dispatch(new HandleGenericJob($body));
            }

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
    }
}
