<?php

namespace App\Jobs\Webhooks;

use Illuminate\Support\Facades\Log;
use App\Jobs\Email\Payment\NewConfirmedJob;
use App\Jobs\Email\Payment\SuccessVouchersJob;
use App\Jobs\Systems\Tenant\General\BaseWebhookJob;

class HandleConfirmedJob extends BaseWebhookJob
{
    public function sendMail()
    {
        // $vouchers = $this->crudVoucherService->getAll(["buys_id" => $this->buysId], ["name" => "ASC"]);

        // SuccessVouchersJob::dispatch(
        //     [$this->buy->customers->email],
        //     [
        //         "customers_id" => $this->buy->customers_id,
        //         "buys_id" => $this->buy->id,
        //         "email" => $this->buy->customers->email,
        //         "buy" => $this->buy,
        //         "vouchers" => $vouchers,
        //     ]
        // );

        // $mailToAdress = explode(";", env("MAIL_TO_ADDRESS"));

        // NewConfirmedJob::dispatch($mailToAdress, [
        //     "customers_id" => $this->buy->customers_id,
        //     "buys_id" => $this->buy->id,
        //     "email" => env("MAIL_TO_ADDRESS"),
        //     "buy" => $this->buy,
        //     "vouchers" => $vouchers,
        // ]);
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
