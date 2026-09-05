<?php

namespace App\Services\Payments;

use App\Models\PaymentSetting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Exception;

class PaystackPaymentService implements PaymentServiceInterface
{
    protected $secretKey;
    protected $publicKey;
    protected $isActive;
    protected $baseUrl = 'https://api.paystack.co';

    public function configure(): void
    {
        $setting = PaymentSetting::where('provider_name', 'paystack')->first();
        if ($setting) {
            $this->secretKey = $setting->secret_key;
            $this->publicKey = $setting->public_key;
            $this->isActive = $setting->is_active;
        }
    }

    public function initializePayment(Transaction $transaction, string $email, string $callbackUrl): string
    {
        if (!$this->isActive || !$this->secretKey) {
            throw new Exception("Paystack is not configured correctly or is disabled.");
        }

        $amountInKobo = (int) ($transaction->amount * 100);

        $response = Http::withToken($this->secretKey)->post($this->baseUrl . '/transaction/initialize', [
            'email' => $email,
            'amount' => $amountInKobo,
            'reference' => $transaction->reference,
            'callback_url' => $callbackUrl,
            'metadata' => [
                'transaction_id' => $transaction->id,
                'custom_fields' => [
                    [
                        'display_name' => "Payment For",
                        'variable_name' => "payment_for",
                        'value' => $transaction->payment_type
                    ]
                ]
            ]
        ]);

        if ($response->successful() && $response->json('status')) {
            return $response->json('data.authorization_url');
        }

        throw new Exception("Paystack initialization failed: " . $response->json('message', 'Unknown error'));
    }

    public function verifyPayment(string $reference): array
    {
        if (!$this->isActive || !$this->secretKey) {
            return ['success' => false, 'message' => 'Paystack not configured'];
        }

        $response = Http::withToken($this->secretKey)->get($this->baseUrl . '/transaction/verify/' . rawurlencode($reference));

        if ($response->successful() && $response->json('status')) {
            $data = $response->json('data');

            if ($data['status'] === 'success') {
                return [
                    'success' => true,
                    'amount'  => $data['amount'] / 100, // Convert Kobo back to standard
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
