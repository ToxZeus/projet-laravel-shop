<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="users" class="w-5 h-5 text-indigo-500" /> Gestion des utilisateurs
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            <div class="card overflow-hidden">
                <table class="table-modern w-full">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Commandes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="font-medium text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 font-semibold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="field-select !py-1.5 text-sm">
                                        <option value="user" @selected($user->role === 'user')>Utilisateur</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-primary btn-sm">
                                        Sauvegarder
                                    </button>
                                </form>
                            </td>
                            <td><span class="badge-gray">{{ $user->orders_count }}</span></td>
                            <td>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                      onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn hover:!text-red-600">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                                @else
                                    <span class="badge-indigo">Vous</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
