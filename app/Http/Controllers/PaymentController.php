<?php

namespace App\Http\Controllers;

use App\Models\AdvertisingCampaign;
use App\Models\ListingPromotion;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Services\Payments\PaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class PaymentController extends Controller
{
    /**
     * Start the checkout process dynamically
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'provider' => 'required|in:paystack,flutterwave'
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaction is no longer pending.');
        }

        try {
            $paymentService = PaymentFactory::make($request->provider);
            
            $transaction->update([
                'payment_provider' => $request->provider,
                'reference' => 'OMNG-' . time() . '-' . Str::random(8)
            ]);

            $callbackUrl = route('payment.callback', ['provider' => $request->provider]);

            // Initialize through chosen provider
            $redirectUrl = $paymentService->initializePayment($transaction, auth()->user()->email, $callbackUrl);

            return redirect($redirectUrl);

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle provider redirects / callbacks
     */
    public function callback(Request $request, $provider)
    {
        try {
            // Providers pass reference differently (Paystack uses trxref/reference, FLW uses tx_ref)
            $reference = $request->query('reference') ?? $request->query('tx_ref') ?? $request->query('trxref');

            if (!$reference) {
                return redirect()->route('home')->with('error', 'Invalid payment reference.');
            }

            $transaction = Transaction::where('reference', $reference)->first();

            if (!$transaction) {
                return redirect()->route('home')->with('error', 'Transaction not found.');
            }

            if ($transaction->status === 'successful') {
                return redirect()->route('home')->with('success', 'Payment was already processed successfully.');
            }

            $paymentService = PaymentFactory::make($provider);
            $verification = $paymentService->verifyPayment($reference);

            if ($verification['success'] && $verification['amount'] >= $transaction->amount) {
                
                DB::transaction(function () use ($transaction, $verification) {
                    $transaction->update([
                        'status' => 'successful',
                        'paid_at' => now(),
                        'currency' => $verification['currency'] ?? $transaction->currency,
                    ]);

                    self::activateService($transaction);
                });

                return redirect()->route('seller.dashboard')->with('success', 'Payment successful! Service activated.');
            } else {
                $transaction->update(['status' => 'failed']);
                return redirect()->route('seller.dashboard')->with('error', 'Payment verification failed: ' . $verification['message']);
            }

        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'An error occurred processing the payment callback.');
        }
    }

    /**
     * Internally resolves the paid service and executes activation.
     */
    private static function activateService(Transaction $transaction)
    {
        if ($transaction->payment_type === 'listing_promotion') {
            $promo = ListingPromotion::where('transaction_id', $transaction->id)->first();
            if ($promo) {
                $package = \App\Models\MonetizationPackage::find($promo->package_id);
                $duration = $package ? $package->duration_days : 7;
                $promo->update(['status' => 'active', 'starts_at' => now(), 'expires_at' => now()->addDays($duration)]);
                
                $ad = \App\Models\Ad::find($promo->ad_id);
                if ($ad) {
                    if ($promo->promotion_type === 'top_ad') {
                        $ad->update(['is_top_ad' => true]);
                    } elseif ($promo->promotion_type === 'featured') {
                        $ad->update(['is_featured' => true]);
                    } elseif ($promo->promotion_type === 'boost') {
                        $ad->update(['last_boosted_at' => now()]);
                    }
                }
            }
        } elseif ($transaction->payment_type === 'advertising_campaign') {
            $campaign = AdvertisingCampaign::where('id', $transaction->product_purchased)->first();
            if ($campaign) {
                $campaign->update(['status' => 'active', 'starts_at' => now()]);
            }
        } elseif ($transaction->payment_type === 'seller_package') {
            $package = UserPackage::where('transaction_id', $transaction->id)->first();
            if ($package) {
                $package->update(['status' => 'active', 'starts_at' => now()]);
            }
        }
    }
}
