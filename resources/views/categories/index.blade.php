@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Catégories</h1>
        <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">+ Nouvelle catégorie</a>
    </div>

    @if(session('status'))
        <div class="mb-4 text-green-700">{{ session('status') }}</div>
    @endif

    @if($categories->isEmpty())
        <p>Aucune catégorie.</p>
    @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2">Nom</th>
                    <th class="py-2">Nombre d'articles</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b">
                    <td class="py-2">{{ $category->name }}</td>
                    <td class="py-2">{{ $category->articles_count }}</td>
                    <td class="py-2">
                        <a href="{{ route('categories.edit', $category->id_category) }}" class="text-blue-600 mr-3">Modifier</a>
                        <a href="{{ route('categories.destroy', $category->id_category) }}"
                           onclick="return confirm('Supprimer cette catégorie ?')"
                           class="text-red-600">Supprimer</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
