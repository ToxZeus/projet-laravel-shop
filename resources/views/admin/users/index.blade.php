@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Gestion des utilisateurs</h1>

    @include('partials._flash')

    <table class="w-full border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Nom</th>
                <th class="py-2">Email</th>
                <th class="py-2">Rôle</th>
                <th class="py-2">Commandes</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b">
                <td class="py-2">{{ $user->name }}</td>
                <td class="py-2">{{ $user->email }}</td>
                <td class="py-2">
                    <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="role" class="border rounded px-2 py-1 text-sm bg-white text-gray-900">
                            <option value="user" @selected($user->role === 'user')>Utilisateur</option>
                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                        </select>
                        <button type="submit" class="text-sm bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                            Sauvegarder
                        </button>
                    </form>
                </td>
                <td class="py-2">{{ $user->orders_count }}</td>
                <td class="py-2">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 text-sm">Supprimer</button>
                    </form>
                    @else
                        <span class="text-gray-400 text-sm">Vous</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
