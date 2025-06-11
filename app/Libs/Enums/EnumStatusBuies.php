<?php

namespace App\Libs\Enums;

use App\Libs\Enums\EnumStatus;
use App\Jobs\Webhooks\HandleFailedJob;
use App\Jobs\Webhooks\HandleExpiredJob;
use App\Jobs\Webhooks\HandleRefundedJob;
use App\Jobs\Webhooks\HandleConfirmedJob;
use App\Jobs\Webhooks\HandleSucceededJob;

// Faixas	Propósitos
// -----------------------------------------------------
// 0–99	    Estados internos (não externos)
// 100–199	Eventos relacionados a pagamento
// 200–299	Eventos relacionados a reembolsos
// 300–399	Eventos relacionados a disputas (se houver)
// 400–499	Erros/falhas gerais ou sistêmicas
class EnumStatusBuies
{
    // Estados Internos
    const INTERNAL_PAYMENT_CREATED = 0;
    const INTERNAL_PAYMENT_WAITING = 1;

    // Webhook - Pagamentos
    const WEBHOOK_PAYMENT_SUCCEEDED = 100;
    const WEBHOOK_PAYMENT_PROCESSING = 101;
    const WEBHOOK_PAYMENT_FAILED = 102;
    const WEBHOOK_PAYMENT_EXPIRED = 103;

    // Webhook - Reembolsos
    const WEBHOOK_REFUND_SUCCEEDED_TOTAL = 200;

    public static function mapStatusToInternalInfo($status)
    {
        return match ($status) {
            // "PENDING" => (object) [
            //     "buyStatus" => self::INTERNAL_PAYMENT_WAITING,
            //     "voucherStatus" => EnumStatus::INACTIVE,
            // ],
            "RECEIVED" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_SUCCEEDED,
                "voucherStatus" => EnumStatus::ACTIVE,
            ],
            "CONFIRMED" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_PROCESSING,
                "voucherStatus" => EnumStatus::ACTIVE,
            ],
            "OVERDUE" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_EXPIRED,
                "voucherStatus" => EnumStatus::INACTIVE,
            ],
            "REFUNDED" => (object) [
                "buyStatus" => self::WEBHOOK_REFUND_SUCCEEDED_TOTAL,
                "voucherStatus" => EnumStatus::INACTIVE,
            ],

            self::INTERNAL_PAYMENT_CREATED => (object) [
                "name" => "Criado",
                "badge" => "text-primary",
                "icon" => "fas fa-plus",
            ],

            self::INTERNAL_PAYMENT_WAITING => (object) [
                "name" => "Aguardando",
                "badge" => "text-secondary",
                "icon" => "fas fa-clock", // relógio clean
            ],

            self::WEBHOOK_PAYMENT_SUCCEEDED => (object) [
                "name" => "Recebido",
                "badge" => "text-success",
                "icon" => "fas fa-check", // check simples, linear
            ],

            self::WEBHOOK_PAYMENT_PROCESSING => (object) [
                "name" => "Confirmado",
                "badge" => "text-warning",
                "icon" => "fas fa-sync-alt", // atualização/processo
            ],

            self::WEBHOOK_PAYMENT_FAILED => (object) [
                "name" => "Falha",
                "badge" => "badge text-danger",
                "icon" => "fas fa-times", // exclamação sem triângulo
            ],

            self::WEBHOOK_PAYMENT_EXPIRED => (object) [
                "name" => "Expirado",
                "badge" => "badge text-danger",
                "icon" => "fas fa-hourglass-end", // ampulheta simples
            ],
            default => null,
            null => null,
        };
    }
    public static function mapEventToWebhookInfo($event)
    {
        return match ($event) {
            "PAYMENT_RECEIVED" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_SUCCEEDED,
                "voucherStatus" => EnumStatus::ACTIVE,
                "jobClass" => HandleSucceededJob::class,
            ],
            "PAYMENT_CONFIRMED" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_PROCESSING,
                "voucherStatus" => EnumStatus::ACTIVE,
                "jobClass" => HandleConfirmedJob::class,
            ],
            "PAYMENT_CREDIT_CARD_CAPTURE_REFUSED" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_FAILED,
                "voucherStatus" => EnumStatus::INACTIVE,
                "jobClass" => HandleFailedJob::class,
            ],
            "PAYMENT_OVERDUE" => (object) [
                "buyStatus" => self::WEBHOOK_PAYMENT_EXPIRED,
                "voucherStatus" => EnumStatus::INACTIVE,
                "jobClass" => HandleExpiredJob::class,
            ],

            "PAYMENT_REFUNDED" => (object) [
                "buyStatus" => self::WEBHOOK_REFUND_SUCCEEDED_TOTAL,
                "voucherStatus" => EnumStatus::INACTIVE,
                "jobClass" => HandleRefundedJob::class,
            ],
            default => null,
            null => null,
        };
    }

    public static function searchCodesByPartialName(string $searchTerm): array
    {
        $matchingCodes = [];

        // Lista de todos os status definidos
        $statusCodes = [
            self::INTERNAL_PAYMENT_CREATED,
            self::INTERNAL_PAYMENT_WAITING,
            self::WEBHOOK_PAYMENT_SUCCEEDED,
            self::WEBHOOK_PAYMENT_PROCESSING,
            self::WEBHOOK_PAYMENT_FAILED,
            self::WEBHOOK_PAYMENT_EXPIRED,
            self::WEBHOOK_REFUND_SUCCEEDED_TOTAL,
        ];

        foreach ($statusCodes as $code) {
            $info = self::mapStatusToInternalInfo($code);

            if ($info && isset($info->name)) {
                if (stripos($info->name, $searchTerm) !== false) {
                    $matchingCodes[] = $code;
                }
            }
        }

        return $matchingCodes;
    }
}
