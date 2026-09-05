<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonetizationPackage;
use Illuminate\Http\Request;

class MonetizationAdminController extends Controller
{
    public function packages(Request $request)
    {
        $packages = MonetizationPackage::orderBy('sort_order')->get();
        return view('admin.monetization.packages', compact('packages'));
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'boost_count' => 'nullable|integer',
            'refresh_frequency_hours' => 'nullable|integer',
            'listing_limit' => 'nullable|integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        MonetizationPackage::create($data);

        return back()->with('success', 'Package created successfully!');
    }

    public function updatePackage(Request $request, $id)
    {
        $package = MonetizationPackage::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'boost_count' => 'nullable|integer',
            'refresh_frequency_hours' => 'nullable|integer',
            'listing_limit' => 'nullable|integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $package->update($data);

        return back()->with('success', 'Package updated successfully!');
    }

    public function togglePackage($id)
    {
        $package = MonetizationPackage::findOrFail($id);
        $package->update(['is_active' => !$package->is_active]);

        return back()->with('success', 'Package status toggled!');
    }
}
