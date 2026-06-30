<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <x-icon name="box" class="w-5 h-5 text-indigo-500" />
                Catalogue
            </h2>
            @if(auth()->user()->role === 'admin')
                <div class="flex gap-2">
                    <a href="{{ route('articles.create') }}" class="btn-primary btn-sm">
                        <x-icon name="plus" class="w-4 h-4" /> Nouvel article
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn-secondary btn-sm">
                        <x-icon name="tag" class="w-4 h-4" /> Catégories
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            {{-- Barre de recherche + filtre catégorie --}}
            <form method="GET" action="{{ route('dashboard') }}" class="card card-body flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-48">
                    <label class="field-label">Recherche</label>
                    <div class="relative">
                        <x-icon name="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                               placeholder="Rechercher un article..."
                               class="field-input pl-9" />
                    </div>
                </div>

                <div class="min-w-48">
                    <label class="field-label">Catégorie</label>
                    <select name="category" class="field-select">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_category }}"
                                {{ ($categoryId ?? '') == $cat->id_category ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary">
                    <x-icon name="search" class="w-4 h-4" /> Filtrer
                </button>

                @if($search || $categoryId)
                    <a href="{{ route('dashboard') }}" class="btn-ghost">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <div class="card card-body">
                @if($articles->isEmpty())
                    <div class="text-center py-16">
                        <x-icon name="box" class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" />
                        <p class="mt-3 text-gray-500">Aucun article trouvé.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($articles as $article)
                            <div class="group rounded-xl overflow-hidden flex flex-col ring-1 ring-gray-200 dark:ring-gray-700 hover:shadow-lg hover:-translate-y-0.5 transition">
                                <div class="relative h-40 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if($article->image_url)
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}"
                                             class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400">
                                            <x-icon name="box" class="w-8 h-8" />
                                        </div>
                                    @endif
                                    <span class="absolute top-2 right-2 {{ $article->quantity === 0 ? 'badge-red' : 'badge-green' }}">
                                        {{ $article->quantity === 0 ? 'Rupture' : 'En stock' }}
                                    </span>
                                </div>

                                <div class="p-4 flex flex-col flex-1">
                                    <span class="badge-gray w-fit mb-2">{{ $article->category->name ?? '—' }}</span>
                                    <h3 class="font-semibold text-base mb-1 text-gray-900 dark:text-gray-100">{{ $article->title }}</h3>
                                    <p class="font-bold text-indigo-600 dark:text-indigo-400 mb-3">
                                        {{ $article->price_formatted }}
                                    </p>

                                    <div class="mt-auto flex flex-wrap gap-2 text-sm">
                                        <a href="{{ route('articles.show', $article->id_article) }}" class="btn-secondary btn-sm">
                                            <x-icon name="eye" class="w-3.5 h-3.5" /> Voir
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('articles.edit', $article->id_article) }}" class="icon-btn">
                                                <x-icon name="pencil" class="w-4 h-4" />
                                            </a>
                                            <a href="{{ route('articles.delete', $article->id_article) }}"
                                               class="icon-btn hover:!text-red-600"
                                               onclick="return confirm('Supprimer cet article ?')">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
