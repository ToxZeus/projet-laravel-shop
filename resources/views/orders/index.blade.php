<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="truck" class="w-5 h-5 text-indigo-500" />
            {{ auth()->user()->role === 'admin' ? 'Toutes les commandes' : 'Mes commandes' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            @if($orders->isEmpty())
                <div class="card card-body text-center py-16">
                    <x-icon name="truck" class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" />
                    <p class="mt-3 text-gray-500">Aucune commande pour le moment.</p>
                </div>
            @else
                <div class="card overflow-hidden">
                    <table class="table-modern w-full">
                        <thead>
                            <tr>
                                <th>N°</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Client</th>
                                @endif
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php
                                $statusBadge = match($order->status) {
                                    'en attente' => 'badge-yellow',
                                    'validée' => 'badge-blue',
                                    'expédiée' => 'badge-indigo',
                                    'livrée' => 'badge-green',
                                    'annulée' => 'badge-red',
                                    default => 'badge-gray',
                                };
                            @endphp
                            <tr>
                                <td class="font-medium text-gray-900 dark:text-gray-100">#{{ $order->id_order }}</td>
                                @if(auth()->user()->role === 'admin')
                                    <td>{{ $order->user->name }}</td>
                                @endif
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="font-semibold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                <td><span class="{{ $statusBadge }}">{{ ucfirst($order->status) }}</span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('orders.show', $order->id_order) }}" class="icon-btn">
                                            <x-icon name="eye" class="w-4 h-4" />
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('orders.destroy', $order->id_order) }}"
                                              onsubmit="return confirm('Supprimer la commande #{{ $order->id_order }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn hover:!text-red-600">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $orders->links() }}
                </div>
            @endif

            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400">
                <x-icon name="arrow-left" class="w-4 h-4" /> Retour aux articles
            </a>
        </div>
    </div>
</x-app-layout>
