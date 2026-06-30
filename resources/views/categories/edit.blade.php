<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="pencil" class="w-5 h-5 text-indigo-500" /> Modifier la catégorie
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            <div class="card card-body">
                <form action="{{ route('categories.update') }}" method="post" class="space-y-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $category->id_category }}" />
                    <div>
                        <x-input-label value="Nom" />
                        <x-text-input type="text" name="name" class="w-full" value="{{ old('name', $category->name) }}" required />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <x-primary-button>
                            <x-icon name="check-circle" class="w-4 h-4" /> Enregistrer
                        </x-primary-button>
                        <a href="{{ route('categories.index') }}" class="btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
