<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $categoryId = $request->integer('category') ?: null;

        $articles = Article::search($search)
            ->byCategory($categoryId)
            ->with('category')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('dashboard', compact('articles', 'categories', 'search', 'categoryId'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('articles.create', compact('categories'));
    }

    public function post(StoreArticleRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        } else {
            unset($data['image']);
        }

        Article::create($data);

        return redirect()->route('dashboard')->with('status', 'Article créé.');
    }

    public function edit(int $id)
    {
        $article    = Article::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('articles.update', compact('article', 'categories'));
    }

    public function update(UpdateArticleRequest $request)
    {
        $data    = $request->validated();
        $article = Article::findOrFail($data['id']);

        if ($request->hasFile('image')) {
            // Supprime l'ancienne image si stockée localement
            if ($article->image && !str_starts_with($article->image, 'http')) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        } else {
            unset($data['image']);
        }

        unset($data['id']);
        $article->update($data);

        return redirect()->route('dashboard')->with('status', 'Article mis à jour.');
    }

    public function show(int $id)
    {
        $article = Article::with('category')->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function delete(int $id)
    {
        $article = Article::findOrFail($id);

        // Supprime l'image locale si elle existe
        if ($article->image && !str_starts_with($article->image, 'http')) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete(); // soft delete

        return redirect()->route('dashboard')->with('status', 'Article supprimé.');
    }
}
