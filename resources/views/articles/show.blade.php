<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400">
            <x-icon name="arrow-left" class="w-4 h-4" /> Retour au catalogue
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('partials._flash')

            <div class="card overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="bg-gray-100 dark:bg-gray-700 flex items-center justify-center min-h-[280px]">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <x-icon name="box" class="w-16 h-16 text-gray-300 dark:text-gray-500" />
                        @endif
                    </div>

                    <div class="p-6 sm:p-8 flex flex-col">
                        <span class="badge-gray w-fit mb-3">{{ $article->category->name }}</span>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $article->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $article->description }}</p>

                        <div class="flex items-center gap-3 mb-6">
                            <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $article->price_formatted }}</span>
                            @if($article->quantity === 0)
                                <span class="badge-red">Rupture de stock</span>
                            @else
                                <span class="badge-green">{{ $article->quantity }} en stock</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @include('cart._add_form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
