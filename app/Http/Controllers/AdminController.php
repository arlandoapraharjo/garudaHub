<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin management panel.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        $projects = Project::with('client')->orderBy('id', 'asc')->get();

        return view('admin.index', compact('users', 'projects'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah peran akun sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:klien,developer,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Peran pengguna '.$user->name.' berhasil diperbarui.');
    }

    /**
     * Delete user (cascaded to projects and bids).
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', 'Pengguna '.$userName.' berhasil dihapus.');
    }

    /**
     * Delete project.
     */
    public function destroyProject(Project $project)
    {
        $judul = $project->judul;
        $project->delete();

        return back()->with('success', 'Proyek "'.$judul.'" berhasil dihapus.');
    }
}
