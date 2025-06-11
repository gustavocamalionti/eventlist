<?php

namespace App\Libs\Enums;

class EnumPriorityEventStripe
{
    const PAYMENT_FAILED = 10;
    const ASYNC_PAYMENT_FAILED = 9;
    const CHARGE_REFUNDED = 8;
    const PAYMENT_SUCCEEDED = 7;
    const ASYNC_PAYMENT_SUCCEEDED = 6;
    const CHECKOUT_COMPLETED = 5;

    public static function getPriorityForEvent($event)
    {
        return match ($event) {
            "payment_intent_payment_failed" => self::PAYMENT_FAILED,
            "checkout.session.async_payment_failed" => self::ASYNC_PAYMENT_FAILED,
            "charge.refunded" => self::CHARGE_REFUNDED,
            "payment_intent.succeeded" => self::PAYMENT_SUCCEEDED,
            "checkout.session.async_payment_succeeded" => self::ASYNC_PAYMENT_SUCCEEDED,
            "checkout.session.completed" => self::CHECKOUT_COMPLETED,
            default => 0, // prioridade mínima para desconhecidos
            null => 0, // prioridade mínima para desconhecidos
        };
    }
}
