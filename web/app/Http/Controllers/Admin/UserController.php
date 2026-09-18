<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('events')->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function block(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'No podés bloquear tu propia cuenta.');

        $user->update(['status' => 'blocked']);

        return back()->with('status', 'Usuario bloqueado.');
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update(['status' => 'active']);

        return back()->with('status', 'Usuario desbloqueado.');
    }

    public function makeAdmin(User $user): RedirectResponse
    {
        $user->update(['role' => 'admin']);

        return back()->with('status', 'Usuario promovido a administrador.');
    }

    public function revokeAdmin(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'No podés quitarte tu propio rol de administrador.');

        $user->update(['role' => 'user']);

        return back()->with('status', 'Permisos de administrador removidos.');
    }
}
