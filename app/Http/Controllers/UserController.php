<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Toutes les méthodes sont protégées par le middleware 'admin' sur les routes.

    public function index()
    {
        $users = User::withCount('orders')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(int $id)
    {
        request()->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('info', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user->role = request()->input('role');
        $user->save();

        return back()->with('status', 'Rôle mis à jour.');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('info', 'Vous ne pouvez pas supprimer votre propre compte ici.');
        }

        $user->delete();

        return back()->with('status', 'Utilisateur supprimé.');
    }
}
