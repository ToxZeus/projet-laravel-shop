<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="plus" class="w-5 h-5 text-indigo-500" /> Créer un produit
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('partials._flash')

            <div class="card card-body">
                <form action="{{ route('articles.post') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label value="Titre *" />
                        <x-text-input type="text" name="title" class="w-full" value="{{ old('title') }}" required />
                    </div>

                    <div>
                        <x-input-label value="Description" />
                        <textarea name="description" rows="4" class="field-input">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <x-input-label value="Image (JPG, PNG, WEBP — max 2 Mo)" />
                        <input type="file" name="image" accept="image/*" class="field-input" />
                    </div>

                    <div>
                        <x-input-label value="Catégorie *" />
                        <select name="category_id" class="field-select">
                            <option value="">-- Choisir une catégorie --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id_category }}"
                                    {{ old('category_id') == $category->id_category ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($categories->isEmpty())
                            <p class="text-sm text-amber-600 mt-1">Aucune catégorie disponible — créez-en une d'abord.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Prix (€) *" />
                            <x-text-input type="number" name="price" step="0.01" min="0" class="w-full" value="{{ old('price', 0) }}" required />
                        </div>
                        <div>
                            <x-input-label value="Quantité *" />
                            <x-text-input type="number" name="quantity" min="0" class="w-full" value="{{ old('quantity', 0) }}" required />
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-primary-button>
                            <x-icon name="plus" class="w-4 h-4" /> Créer l'article
                        </x-primary-button>
                        <a href="{{ route('dashboard') }}" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
