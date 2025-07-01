<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\Buy;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Models\Voucher;
use App\Libs\ViewsModules;
use Illuminate\Bus\Queueable;
use App\Libs\Enums\EnumStatus;
use Jetimob\Asaas\Facades\Asaas;
use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\DB;
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBuysJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    public $backoff = [300, 600];
    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            $buys = Buy::where("status", EnumStatusBuies::INTERNAL_PAYMENT_WAITING)
                ->whereNotNull("gateway_payment_id")
                ->get();

            if (count($buys) > 0) {
                foreach ($buys as $buy) {
                    $payment = Asaas::charging()->find($buy->gateway_payment_id);
                    $data = ["payment" => $payment];

                    $status = $payment->getStatus()->value;
                    $mapStatusToInternalInfo = EnumStatusBuies::mapStatusToInternalInfo($status);

                    $shouldTreatWebhook = $mapStatusToInternalInfo != null ? EnumStatus::ACTIVE : EnumStatus::INACTIVE;

                    if ($shouldTreatWebhook) {
                        // Atualizar informações da compra
                        $buy->status = $mapStatusToInternalInfo->buyStatus;
                        $buy->payment_details = $data;

                        // Depois tratar melhor os casos de REFUNDED
                        if ($status != "REFUNDED") {
                            $buy->receipt_url = $payment->getTransactionReceiptUrl();
                        }
                        $buy->save();

                        // Atualizar informações do ingresso
                        $vouchers = Voucher::where("buys_id", $buy->id)->get();

                        foreach ($vouchers as $voucher) {
                            $voucher->active = $mapStatusToInternalInfo->voucherStatus;
                            $voucher->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::info($e);
            Errors::GetMessageError(
                EnumErrorsType::SQL,
                ViewsModules::CRON_PAYMENT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        } catch (Exception $e) {
            Log::info($e);
            DB::rollBack();
            Errors::GetMessageError(
                EnumErrorsType::GENERIC,
                ViewsModules::CRON_PAYMENT,
                Actions::GET_INFO,
                $e,
                $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
