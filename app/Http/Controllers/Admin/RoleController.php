<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.roles.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => 'required|in:user,agent,admin'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.roles.index')
            ->with('success', "Le rôle de {$user->name} a été changé.");
    }
}