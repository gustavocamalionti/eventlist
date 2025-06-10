<?php

namespace App\Jobs\Webhooks;

use Exception;
use App\Libs\Errors;
use App\Libs\Actions;
use App\Libs\ViewsModules;
use App\Libs\Enums\EnumErrorsType;
use Illuminate\Support\Facades\Log;
use App\Jobs\Webhooks\BaseWebhookJob;
use Illuminate\Database\QueryException;
use App\Jobs\Email\Payment\NewConfirmedJob;
use App\Jobs\Email\Payment\SuccessVouchersJob;

/**
 * @property \App\Services\Crud\CrudBuyService $crudBuyService
 * @property \App\Services\Crud\CrudVoucherService $crudVoucherService
 * @property mixed $payload
 * @property mixed $stripeObject
 * @property int|null $buysId
 * @property string|null $eventName
 * @property string|null $eventStatus
 */
class HandleSucceededJob extends BaseWebhookJob
{
    public function sendMail()
    {
        // if ($this->buy->methodPayment->name == "PIX") {
        //     $vouchers = $this->crudVoucherService->getAll(["buys_id" => $this->buysId], ["name" => "ASC"]);

        //     SuccessVouchersJob::dispatch(
        //         [$this->buy->customers->email],
        //         [
        //             "customers_id" => $this->buy->customers_id,
        //             "buys_id" => $this->buy->id,
        //             "email" => $this->buy->customers->email,
        //             "buy" => $this->buy,
        //             "vouchers" => $vouchers,
        //         ]
        //     );

        //     $mailToAdress = explode(";", env("MAIL_TO_ADDRESS"));

        //     NewConfirmedJob::dispatch($mailToAdress, [
        //         "customers_id" => $this->buy->customers_id,
        //         "buys_id" => $this->buy->id,
        //         "email" => env("MAIL_TO_ADDRESS"),
        //         "buy" => $this->buy,
        //         "vouchers" => $vouchers,
        //     ]);
        // }
    }

    public function extractInfoJson()
    {
        $data = [
            "payment" => $this->object->payment,
        ];

        $this->buy->payment_details = $data;
        $this->buy->receipt_url = $this->object->payment["transactionReceiptUrl"];
        $this->buy = $this->buy->save();
    }
}
