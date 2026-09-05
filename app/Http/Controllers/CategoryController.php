<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ad;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('ads')->whereNull('parent_id')->get();
        return view('categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $ads = Ad::with(['primaryImage', 'location', 'category'])
            ->active()
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(20);

        return view('categories.show', compact('category', 'ads'));
    }
}
