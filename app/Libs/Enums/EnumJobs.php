<?php

namespace App\Libs\Enums;

use App\Libs\Enums\EnumStatus;
use App\Jobs\Webhooks\HandleFailedJob;
use App\Jobs\Webhooks\HandleExpiredJob;
use App\Jobs\Webhooks\HandleRefundedJob;
use App\Jobs\Webhooks\HandleConfirmedJob;
use App\Jobs\Webhooks\HandleSucceededJob;

class EnumJobs
{
    // Estados Internos
    const NEW_CONFIRMED_JOB = "App\Jobs\Email\Payment\NewConfirmedJob";
    const SUCCESS_VOUCHERS_JOB = "App\Jobs\Email\Payment\SuccessVouchersJob";

    public static function mapToInternalInfo($status)
    {
        return match ($status) {
            self::SUCCESS_VOUCHERS_JOB => "Ingressos Confirmados",
            self::NEW_CONFIRMED_JOB => "Novo Participante",
            default => null,
            null => null,
        };
    }
}
