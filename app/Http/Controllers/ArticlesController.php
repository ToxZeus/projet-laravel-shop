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

    public function post(){
        $article = new Article();
        $article->title = request('title');
        $article->description = request('description');
        $article->category_id = request('category_id');
        $article->price = request('price');
        $article->quantity = request('quantity');
        $article->save();

        return redirect('/dashboard');
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
        $article->title = $request->input('title');
        $article->description = $request->input('description');
        $article->category_id = $request->input('category_id');
        $article->price = $request->input('price');
        $article->quantity = $request->input('quantity');
        $article->save();

        return redirect('/dashboard');
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
