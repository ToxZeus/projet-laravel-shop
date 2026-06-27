@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Modifier la catégorie</h1>

    @if($errors->any())
        <div class="mb-4 text-red-700">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.update') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $category->id_category }}" />
        <div class="mb-2">
            <label class="block">Nom :</label>
            <input type="text" name="name" class="border rounded px-2 py-1 text-gray-900 w-full"
                   value="{{ old('name', $category->name) }}" />
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Enregistrer</button>
            <a href="{{ route('categories.index') }}" class="bg-gray-400 text-white px-3 py-1 rounded">Annuler</a>
        </div>
    </form>
</div>
@endsection
