<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    // Toutes les méthodes sont protégées par le middleware 'admin' sur les routes.

    public function index()
    {
        $categories = Category::withCount('articles')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('status', 'Catégorie créée.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::findOrFail($validated['id']);
        $category->update(['name' => $validated['name']]);

        return redirect()->route('categories.index')->with('status', 'Catégorie mise à jour.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Catégorie supprimée.');
    }
}
