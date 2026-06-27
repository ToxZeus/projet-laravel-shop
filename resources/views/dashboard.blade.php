<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            -
            @if(auth()->user()->role == 'admin')
                        <a href="/articles/create">Créer un article</a>
                        -
                        <a href="{{ route('categories.index') }}">Gérer les catégories</a>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials._flash')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Articles :</h1>
                    <br>
                    <br>
                    <ul>
                        @foreach ($articles as $article)
                            @if($article->image)
                                <img src="{{ $article->image }}" alt="{{ $article->title }}" class="inline-block h-12 w-12 object-cover rounded mr-2 align-middle">
                            @endif
                            {{ $article->title }}
                            <br>
                            <a href=" {{ route('articles.show', $article->id_article) }}">Voir</a>
                            @if(auth()->user()->role == 'admin')
                                -
                                <a href=" {{ route('articles.edit', $article->id_article) }}">Modifier</a>
                                -
                                <a href=" {{ route('articles.delete', $article->id_article) }}">Supprimer</a>
                            @endif
                            <br><br>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
