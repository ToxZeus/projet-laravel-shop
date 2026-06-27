<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private function adminOnly()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('info', 'Accès refusé.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $categories = Category::withCount('articles')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        return view('categories.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create(['name' => $validated['name']]);

        return redirect()->route('categories.index')->with('status', 'Catégorie créée.');
    }

    public function edit($id)
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request)
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id'   => 'required|integer|exists:categories,id_category',
        ]);

        $category = Category::findOrFail($validated['id']);
        $category->update(['name' => $validated['name']]);

        return redirect()->route('categories.index')->with('status', 'Catégorie mise à jour.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Catégorie supprimée.');
    }
}
