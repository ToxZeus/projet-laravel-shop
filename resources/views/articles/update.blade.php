<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="pencil" class="w-5 h-5 text-indigo-500" /> Modifier : {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('partials._flash')

            <div class="card card-body">
                <form action="{{ route('articles.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $article->id_article }}" />

                    <div>
                        <x-input-label value="Titre *" />
                        <x-text-input type="text" name="title" class="w-full" value="{{ old('title', $article->title) }}" required />
                    </div>

                    <div>
                        <x-input-label value="Description" />
                        <textarea name="description" rows="4" class="field-input">{{ old('description', $article->description) }}</textarea>
                    </div>

                    <div>
                        <x-input-label value="Image (laisser vide pour conserver l'actuelle)" />
                        @if($article->image_url)
                            <div class="mb-2 flex items-center gap-2">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}"
                                     class="h-16 w-16 object-cover rounded-lg ring-1 ring-gray-200 dark:ring-gray-700">
                                <p class="text-xs text-gray-500">Image actuelle</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="field-input" />
                    </div>

                    <div>
                        <x-input-label value="Catégorie *" />
                        <select name="category_id" class="field-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id_category }}"
                                    {{ old('category_id', $article->category_id) == $category->id_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Prix (€) *" />
                            <x-text-input type="number" name="price" step="0.01" min="0" class="w-full" value="{{ old('price', $article->price) }}" required />
                        </div>
                        <div>
                            <x-input-label value="Quantité *" />
                            <x-text-input type="number" name="quantity" min="0" class="w-full" value="{{ old('quantity', $article->quantity) }}" required />
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-primary-button>
                            <x-icon name="check-circle" class="w-4 h-4" /> Enregistrer
                        </x-primary-button>
                        <a href="{{ route('dashboard') }}" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
