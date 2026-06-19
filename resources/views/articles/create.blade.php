@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Créer un produit</h1>

    @if($errors->any())
        <div class="mb-4 text-red-700">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/articles/create" method="post">
        @csrf
        <div class="mb-2">
            <label class="block">Titre :</label>
            <input type="text" name="title" class="border rounded px-2 py-1 text-gray-900 w-full" value="{{ old('title') }}" />
        </div>

        <div class="mb-2">
            <label class="block">Description :</label>
            <textarea name="description" class="border rounded px-2 py-1 text-gray-900 w-full">{{ old('description') }}</textarea>
        </div>

        <div class="mb-2">
            <label class="block">Image (URL) :</label>
            <input type="text" name="image" class="border rounded px-2 py-1 w-full text-gray-900" value="{{ old('image') }}" placeholder="https://..." />
        </div>

        <div class="mb-2">
            <label class="block">Catégorie :</label>
            <select name="category_id" class="border rounded px-2 py-1 text-gray-900 w-full">
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id_category }}" {{ old('category_id') == $category->id_category ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @if($categories->isEmpty())
                <p class="text-sm text-yellow-600">Aucune catégorie disponible — créez-en d'abord.</p>
            @endif
        </div>

        <div class="mb-2">
            <label class="block">Quantité :</label>
            <input type="number" name="quantity" class="border rounded px-2 py-1 text-gray-900" value="{{ old('quantity', 0) }}" />
        </div>

        <div class="mb-2">
            <label class="block">Prix :</label>
            <input type="number" name="price" step="0.01" class="border rounded px-2 py-1 text-gray-900" value="{{ old('price', 0) }}" />
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Créer</button>
        </div>
    </form>
</div>
@endsection
