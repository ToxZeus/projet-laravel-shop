<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('info', 'Accès refusé.');
        }

        return view('categories.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('info', 'Accès refusé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create(['name' => $validated['name']]);

        return redirect('/dashboard')->with('status', 'Catégorie créée.');
    }
}
