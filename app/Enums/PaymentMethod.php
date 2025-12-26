<?php

namespace App\Enums;

enum PaymentMethod:string
{
    //
    case PAYSTACK = 'paystack';
    case FLUTTERWAVE = 'flutterwave';
    case STRIPE = 'stripe';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case WALLET = 'wallet';

    public function label(): string
    {
        return match($this) {
            PaymentMethod::PAYSTACK => 'Paystack',
            PaymentMethod::FLUTTERWAVE => 'Flutterwave',
            PaymentMethod::STRIPE => 'Stripe',
            PaymentMethod::BANK_TRANSFER => 'Bank Transfer',
            PaymentMethod::CASH => 'Cash',
            PaymentMethod::WALLET => 'Wallet',
        };
    }
}
