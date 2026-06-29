@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
	<h1 class="text-2xl font-bold mb-2">{{ $article->title }}</h1>
	@if($article->image)
		<img src="{{ $article->image }}" alt="{{ $article->title }}" class="mb-3 rounded w-48 object-cover">
	@endif
	<p class="mb-2">{{ $article->description }}</p>
	<p class="mb-1">Catégorie : {{ $article->category->name }}</p>
	<p class="mb-1">En stock : {{ $article->quantity }}</p>
	<p class="mb-2">Prix : {{ number_format($article->price, 2, ',', ' ') }} €</p>

	@include('cart._add_form')

	<div class="mt-4">
		<a href="/dashboard" class="text-blue-600">Retour</a>
	</div>
</div>
@endsection
