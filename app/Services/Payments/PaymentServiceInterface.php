<?php

namespace App\Services\Payments;

use App\Models\Transaction;

interface PaymentServiceInterface
{
    /**
     * Set up the provider credentials from the database settings.
     */
    public function configure(): void;

    /**
     * Initialize a payment session/checkout URL.
     * 
     * @param Transaction $transaction
     * @param string $email
     * @param string $callbackUrl
     * @return string Redirect URL for checkout
     */
    public function initializePayment(Transaction $transaction, string $email, string $callbackUrl): string;

    /**
     * Verify a transaction callback/webhook.
     * 
     * @param string $reference
     * @return array ['success' => bool, 'amount' => float, 'currency' => string, 'message' => string]
     */
    public function verifyPayment(string $reference): array;
}
