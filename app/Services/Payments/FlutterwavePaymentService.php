<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Exception;

class FlutterwavePaymentService implements PaymentServiceInterface
{
    protected $secretKey;
    protected $publicKey;
    protected $isActive;
    protected $baseUrl = 'https://api.flutterwave.com/v3';

    public function configure(): void
    {
        $setting = PaymentSetting::where('provider_name', 'flutterwave')->first();
        if ($setting) {
            $this->secretKey = $setting->secret_key;
            $this->publicKey = $setting->public_key;
            $this->isActive = $setting->is_active;
        }
    }

    public function initializePayment(Transaction $transaction, string $email, string $callbackUrl): string
    {
        if (!$this->isActive || !$this->secretKey) {
            throw new Exception("Flutterwave is not configured correctly or is disabled.");
        }

        $response = Http::withToken($this->secretKey)->post($this->baseUrl . '/payments', [
            'tx_ref' => $transaction->reference,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'redirect_url' => $callbackUrl,
            'customer' => [
                'email' => $email,
            ],
            'meta' => [
                'transaction_id' => $transaction->id,
                'payment_type' => $transaction->payment_type
            ],
            'customizations' => [
                'title' => 'OnlineMarket.ng Payment',
                'description' => 'Payment for ' . $transaction->payment_type
            ]
        ]);

        if ($response->successful() && $response->json('status') === 'success') {
            return $response->json('data.link');
        }

        throw new Exception("Flutterwave initialization failed: " . $response->json('message', 'Unknown error'));
    }

    public function verifyPayment(string $reference): array
    {
        if (!$this->isActive || !$this->secretKey) {
            return ['success' => false, 'message' => 'Flutterwave not configured'];
        }

        // According to current FLW v3 standard, verification should be done via transaction ID obtained from callback,
        // but FLW also provides endpoint to verify by tx_ref
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . '/transactions/verify_by_reference', [
                'tx_ref' => $reference
            ]);

        if ($response->successful() && $response->json('status') === 'success') {
            $data = $response->json('data');

            // Fluterwave strict verification: check if status is successful/completed
            if ($data['status'] === 'successful') {
                return [
                    'success' => true,
                    'amount'  => $data['amount'],
                    'currency'=> $data['currency'],
                    'message' => 'Payment verified successfully.'
                ];
            }
        }

        return [
            'success' => false,
            'amount'  => 0,
            'message' => $response->json('message', 'Verification failed.')
        ];
    }
}
