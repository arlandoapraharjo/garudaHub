<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects (with search).
     */
    public function index(Request $request)
    {
        $query = Project::with('client')
            ->withCount('bids')
            ->latest();

        $search = trim((string) $request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(10)->withQueryString();

        return view('projects.index', compact('projects', 'search'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'kategori' => ['required', 'string', 'max:50'],
            'budget_min' => ['required', 'numeric', 'min:0'],
            'budget_max' => ['required', 'numeric', 'gte:budget_min'],
            'batas_lelang' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'judul.required' => 'Judul proyek wajib diisi.',
            'judul.max' => 'Judul maksimal 200 karakter.',
            'deskripsi.required' => 'Deskripsi proyek wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'budget_min.required' => 'Budget minimum wajib diisi.',
            'budget_max.gte' => 'Budget maksimum harus lebih besar atau sama dengan budget minimum.',
            'batas_lelang.after_or_equal' => 'Batas lelang harus tanggal hari ini atau masa depan.',
        ]);

        $project = $request->user()->projects()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyek berhasil dipasang.');
    }

    /**
     * Display the specified project and its bids.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'bids.developer']);

        // Urutkan bids berdasarkan harga terendah
        $bids = $project->bids->sortBy('harga');

        $user = auth()->user();
        $isOwner = $user && $user->id === $project->client_id;
        $userBid = null;

        if ($user && $user->isDeveloper()) {
            $userBid = $project->bids->firstWhere('developer_id', $user->id);
        }

        return view('projects.show', compact('project', 'bids', 'isOwner', 'userBid'));
    }
}
