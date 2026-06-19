@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Créer une catégorie</h1>

    @if($errors->any())
        <div class="mb-4 text-red-700">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="post">
        @csrf
        <div class="mb-2">
            <label class="block">Nom :</label>
            <input type="text" name="name" class="border rounded px-2 py-1 text-gray-900 w-full" value="{{ old('name') }}" />
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Créer</button>
        </div>
    </form>
</div>
@endsection
