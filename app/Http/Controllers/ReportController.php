<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ad_id' => 'nullable|exists:ads,id',
            'reported_user_id' => 'nullable|exists:users,id',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'ad_id' => $request->ad_id,
            'reported_user_id' => $request->reported_user_id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Report submitted successfully. Thank you for keeping our community safe!');
    }
}
