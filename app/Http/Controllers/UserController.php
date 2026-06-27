<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function adminOnly()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('info', 'Accès refusé.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->adminOnly()) return $redirect;

        $users = User::withCount('orders')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(int $id)
    {
        if ($redirect = $this->adminOnly()) return $redirect;

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
        if ($redirect = $this->adminOnly()) return $redirect;

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('info', 'Vous ne pouvez pas supprimer votre propre compte ici.');
        }

        $user->delete();

        return back()->with('status', 'Utilisateur supprimé.');
    }
}
