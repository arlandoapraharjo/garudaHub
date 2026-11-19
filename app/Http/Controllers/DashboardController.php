<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard based on role.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isKlien()) {
            $projects = $user->projects()
                ->withCount('bids')
                ->latest()
                ->get();

            return view('dashboard.klien', compact('projects'));
        }

        // Developer dashboard
        $bids = $user->bids()
            ->with('project')
            ->latest()
            ->get();

        return view('dashboard.developer', compact('bids'));
    }
}
