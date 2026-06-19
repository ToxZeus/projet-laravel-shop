<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('dashboard', ['articles' => $articles]);
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')
            ->with('info', 'Vous ne pouvez pas créer de produit.');
        }
        $categories = Category::all();
        return view('articles.create', ['categories' => $categories]);
    }

    public function post(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'category_id' => 'required|integer|exists:categories,id_category',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        Article::create($validated);

        return redirect('/dashboard')->with('status', 'Article créé.');
    }

    public function edit(int $id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')
            ->with('info', 'Vous ne pouvez pas modifier ce produit.');
        }
        return view('articles.update', ['article' => $article, 'categories' => $categories]);
    }

    public function update(Request $request)
    {
        $article = Article::findOrFail($request->id);
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'category_id' => 'required|integer|exists:categories,id_category',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $article->update($validated);

        return redirect('/dashboard')->with('status', 'Article mis à jour.');
    }

    public function show(int $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', ['article' => $article]);
    }

    public function delete(int $id)
    {
        $article = Article::findOrFail($id);
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')
            ->with('info', 'Vous ne pouvez pas supprimer ce produit.');
        }
        $article->delete();

        return redirect('/dashboard');
    }
}
