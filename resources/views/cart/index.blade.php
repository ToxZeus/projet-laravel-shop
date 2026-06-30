<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="cart" class="w-5 h-5 text-indigo-500" /> Mon panier
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            @if($items->isEmpty())
                <div class="card card-body text-center py-16">
                    <x-icon name="cart" class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" />
                    <p class="mt-3 text-gray-500">Votre panier est vide.</p>
                    <a href="{{ route('dashboard') }}" class="btn-primary mt-4 inline-flex">
                        <x-icon name="box" class="w-4 h-4" /> Voir le catalogue
                    </a>
                </div>
            @else
                <div class="card overflow-hidden">
                    <table class="table-modern w-full">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td class="font-medium text-gray-900 dark:text-gray-100">{{ $item->article->title }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update', $item->id_cart) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                               max="{{ $item->article->quantity }}"
                                               class="field-input w-16 !py-1 text-center">
                                        <button type="submit" class="icon-btn">
                                            <x-icon name="check-circle" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </td>
                                <td>{{ number_format($item->article->price, 2, ',', ' ') }} €</td>
                                <td class="font-semibold">{{ number_format($item->article->price * $item->quantity, 2, ',', ' ') }} €</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove', $item->id_cart) }}">
                                        @csrf
                                        <button type="submit" class="icon-btn hover:!text-red-600">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card card-body flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total à payer</p>
                        <p class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($items->sum(fn($item) => $item->article->price * $item->quantity), 2, ',', ' ') }} €
                        </p>
                    </div>
                    <form method="POST" action="{{ route('payment.checkout') }}">
                        @csrf
                        <button type="submit" class="btn-success">
                            <x-icon name="check-circle" class="w-4 h-4" /> Payer avec Stripe
                        </button>
                    </form>
                </div>
            @endif

            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400">
                <x-icon name="arrow-left" class="w-4 h-4" /> Retour aux articles
            </a>
        </div>
    </div>
</x-app-layout>
