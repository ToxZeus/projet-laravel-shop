<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <x-icon name="tag" class="w-5 h-5 text-indigo-500" /> Catégories
            </h2>
            <a href="{{ route('categories.create') }}" class="btn-primary btn-sm">
                <x-icon name="plus" class="w-4 h-4" /> Nouvelle catégorie
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            @if($categories->isEmpty())
                <div class="card card-body text-center py-16">
                    <x-icon name="tag" class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" />
                    <p class="mt-3 text-gray-500">Aucune catégorie.</p>
                </div>
            @else
                <div class="card overflow-hidden">
                    <table class="table-modern w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Nombre d'articles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                <td><span class="badge-gray">{{ $category->articles_count }}</span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('categories.edit', $category->id_category) }}" class="icon-btn">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </a>
                                        <a href="{{ route('categories.destroy', $category->id_category) }}"
                                           onclick="return confirm('Supprimer cette catégorie ?')"
                                           class="icon-btn hover:!text-red-600">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
