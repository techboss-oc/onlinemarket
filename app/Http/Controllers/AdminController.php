<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Location;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_ads'       => Ad::count(),
            'active_ads'      => Ad::where('status', 'active')->count(),
            'pending_ads'     => Ad::where('status', 'pending')->count(),
            'total_sellers'   => User::where('role', 'seller')->count(),
            'total_buyers'    => User::where('role', 'buyer')->count(),
        ];
        $recentUsers = User::latest()->take(5)->get();
        $recentAds   = Ad::with(['user', 'category'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAds'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|in:buyer,seller,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return back()->with('success', 'User role updated.');
    }

    public function toggleVerify(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_verified' => !$user->is_verified]);
        return back()->with('success', 'Verification status toggled.');
    }

    public function deleteUser(int $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }

    public function ads()
    {
        $ads = Ad::with(['user', 'category', 'location', 'primaryImage'])->latest()->paginate(20);
        return view('admin.ads', compact('ads'));
    }

    public function updateAdStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:pending,active,rejected,expired,sold']);
        Ad::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Ad status updated.');
    }

    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function locations()
    {
        $locations = Location::all();
        return view('admin.locations', compact('locations'));
    }

    public function promotions()
    {
        $featuredAds = Ad::with(['user', 'category', 'primaryImage'])
            ->where('is_featured', true)->latest()->paginate(20);
        return view('admin.promotions', compact('featuredAds'));
    }

    public function transactions()
    {
        $transactions = Transaction::latest('created_at')->paginate(20);
        $totalRevenue = Transaction::where('status', 'successful')->sum('amount');
        return view('admin.transactions', compact('transactions', 'totalRevenue'));
    }
}
