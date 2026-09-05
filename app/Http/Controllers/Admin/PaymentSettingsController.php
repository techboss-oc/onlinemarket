<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        $paystack = PaymentSetting::firstOrCreate(['provider_name' => 'paystack'], ['currency' => 'NGN']);
        $flutterwave = PaymentSetting::firstOrCreate(['provider_name' => 'flutterwave'], ['currency' => 'NGN']);
        
        return view('admin.settings.payments', compact('paystack', 'flutterwave'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:paystack,flutterwave',
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
        ]);

        $setting = PaymentSetting::where('provider_name', $request->provider)->firstOrFail();
        
        $data = $request->only(['public_key', 'is_active', 'is_test_mode']);
        
        // Only update secret key if a new one is provided maskingly
        if ($request->filled('secret_key')) {
            $data['secret_key'] = $request->secret_key;
        }

        $setting->update($data);

        return back()->with('success', ucfirst($request->provider) . ' settings updated successfully!');
    }
}
