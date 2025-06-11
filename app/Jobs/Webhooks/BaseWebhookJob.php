<?php

namespace App\Jobs\Webhooks;

use Exception;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Libs\Enums\EnumStatus;
use App\Libs\Enums\EnumErrorsType;
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use App\Services\Crud\CrudBuyService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
// use App\Services\Crud\CrudVoucherService;
use App\Services\Crud\CrudWebhookService;
use App\Libs\Enums\EnumPriorityEventStripe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;

abstract class BaseWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $backoff = [300, 600];

    public $crudBuyService;
    // public $crudVoucherService;
    public $crudWebhookService;

    public $data;
    public $object;
    public $buysId;
    public $buy;
    public $appEnv;
    public $gatewayPaymentId;
    public $gatewayCustomersId;
    public $webhookId;
    public $mapEventToWebhookInfo;
    public $eventName;
    public $shouldTreatWebhook;

    public function __construct($data)
    {
        $this->crudBuyService = app(CrudBuyService::class);
        // $this->crudVoucherService = app(CrudVoucherService::class);
        $this->crudWebhookService = app(CrudWebhookService::class);

        $this->data = $data;
        $this->object = $this->resolveObject();
        $this->appEnv = $this->resolveAppEnv();
        $this->buysId = $this->resolveBuysId();
        $this->buy = $this->resolveBuy();
        $this->gatewayPaymentId = $this->resolveGatewayPaymentId();
        $this->gatewayCustomersId = $this->resolveGatewayCustomersId();
        $this->eventName = $this->resolveEventName();
        $this->mapEventToWebhookInfo = $this->resolvemapEventToWebhookInfo();
        $this->shouldTreatWebhook = $this->resolveshouldTreatWebhook();
    }

    abstract public function sendMail();
    abstract public function extractInfoJson();

    /**
     * Método de tratamento do evento
     */
    public function handle()
    {
        try {
            $this->crudBuyService->beginTransaction();
            $this->saveWebhook();

            // Validar se existe buys para ser processado, se há permissão para isso e se estamos no ambiente correto.
            if ($this->shouldTreatWebhook && $this->buysId != null && $this->appEnv == env("APP_ENV")) {
                // $this->processWebhook();
                // $this->sendMail();
                // $this->extractInfoJson();
            }
            $this->crudBuyService->commitTransaction();
            return response()->json([], 200);
        } catch (QueryException $e) {
            Log::info($e);
            $this->crudBuyService->rollBackTransaction();
            Log::info($e);
            return Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::WEBHOOK,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (Exception $e) {
            Log::info($e);
            $this->crudBuyService->rollBackTransaction();
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

    /**
     * Valida se devemos atualizar o status da compra ou não,
     * considerando o nível de prioridade dos eventos
     */
    // protected function shouldUpdateStatus()
    // {
    //     // Buscar a compra
    //     $buy = $this->crudBuyService->findById($this->buysId);

    //     // Valida os Eventos
    //     $currentEvent = $buy->current_event_stripe;
    //     $newEvent = $this->eventName;
    //     $newPriority = EnumPriorityEventStripe::getPriorityForEvent($newEvent);
    //     $currentPriority = EnumPriorityEventStripe::getPriorityForEvent($currentEvent);
    //     return $newPriority >= $currentPriority;
    // }

    /**
     * Salva o webhook no banco de dados
     */
    protected function saveWebhook()
    {
        $webhook = $this->crudWebhookService->save([
            "buys_id" =>
                $this->appEnv != null && $this->buysId != null && $this->appEnv == env("APP_ENV")
                    ? $this->buysId
                    : null,
            "events_id" => $this->object->id,
            "event_type" => $this->eventName,
            "payload" => $this->data,
            "should_treat" => $this->shouldTreatWebhook,
        ]);
        $this->webhookId = $webhook->id;
    }

    /**
     * Processa o webhook
     */
    protected function processWebhook()
    {
        // $vouchers = $this->crudVoucherService->getAll(["buys_id" => $this->buysId]);
        $this->crudBuyService->update($this->buysId, [
            "status" => $this->mapEventToWebhookInfo->buyStatus,
        ]);

        // foreach ($vouchers as $voucher) {
        //     $voucher->active = $this->mapEventToWebhookInfo->voucherStatus;
        //     $voucher->save();
        // }

        $this->crudWebhookService->update($this->webhookId, [
            "status" => EnumStatus::ACTIVE,
        ]);
    }

    /**
     * Transformar o webhookCall em objeto
     */
    private function resolveObject()
    {
        return (object) $this->data;
    }

    /**
     * Encontra o id da venda vinculada ao evento no nosso banco de dados
     */
    private function resolveBuysId()
    {
        $buysId = null;
        if (property_exists($this->object, "payment") && isset($this->object->payment["externalReference"])) {
            $reference = json_decode($this->object->payment["externalReference"]);
            $buysId = $reference->buys_id ?? null;
        }

        return $buysId;
    }
    /**
     * Encontra a venda vinculada ao evento no nosso banco de dados
     */
    private function resolveBuy()
    {
        $buy = null;
        if ($this->buysId != null && $this->appEnv == env("APP_ENV")) {
            $buy = $this->crudBuyService->findById($this->buysId);
        }

        return $buy;
    }

    /**
     * Encontra o AppEnv de referencias (metadados) e valida se estamos no ambiente correto. (production, development ou local)
     */
    private function resolveAppEnv()
    {
        $appEnv = null;
        if (property_exists($this->object, "payment") && isset($this->object->payment["externalReference"])) {
            $reference = json_decode($this->object->payment["externalReference"]);
            $appEnv = $reference->app_env ?? null;
        }

        return $appEnv;
    }

    /**
     * Encontra a venda vinculada ao evento no gateway de pagamento
     */
    private function resolveGatewayPaymentId()
    {
        $gatewayPaymentId = null;
        if (property_exists($this->object, "payment") && isset($this->object->payment["id"])) {
            $gatewayPaymentId = $this->object->payment["id"];
        }

        return $gatewayPaymentId;
    }

    /**
     * Encontra o id do cliente criado no gateway
     */
    private function resolveGatewayCustomersId()
    {
        $gatewayCustomersId = null;
        if (property_exists($this->object, "payment") && isset($this->object->payment["customer"])) {
            $gatewayCustomersId = $this->object->payment["transactionReceiptUrl"] ?? null;
        }
        return $gatewayCustomersId;
    }

    /**
     * Encontra o nome do evento
     */
    private function resolveEventName()
    {
        return $this->object->event;
    }
    /**
     * Trás todas as informações internas referente o webhook
     */
    private function resolvemapEventToWebhookInfo()
    {
        return EnumStatusBuies::mapEventToWebhookInfo($this->eventName);
    }

    /**
     * Verifica se deveriamos tratar o webhook.
     */
    private function resolveshouldTreatWebhook()
    {
        $shouldTreatWebhook = $this->mapEventToWebhookInfo != null ? EnumStatus::ACTIVE : EnumStatus::INACTIVE;
        return $shouldTreatWebhook;
    }
}
