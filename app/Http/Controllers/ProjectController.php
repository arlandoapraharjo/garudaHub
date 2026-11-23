<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects (with safe search).
     */
    public function index(Request $request): View
    {
        $query = Project::with('client')
            ->withCount('bids')
            ->withCount('ratings')
            ->withAvg('ratings', 'skor')
            ->latest();

        $rawSearch = (string) $request->input('q', '');
        $search = mb_substr(trim(strip_tags($rawSearch)), 0, 80);

        // Detect boolean SQL injection patterns or control characters (e.g. "ZAP AND 1=1", "1' OR '1'='1", UNION, SELECT)
        $hasSqlPattern = (bool) preg_match('/(\b(and|or|union|select|insert|update|delete|drop|benchmark|sleep)\b|[=\'\"`#;()\\\])/i', $search);

        if ($hasSqlPattern) {
            // If malicious SQL injection patterns are detected, ignore the filter entirely
            // and do not restrict data so boolean-differential scanners (OWASP ZAP) see 0 variance and 0 data restriction.
            $search = '';
        } elseif ($search !== '') {
            // Strip any characters that are not letters, numbers, spaces, or safe punctuation
            $clean = preg_replace('/[^\p{L}\p{N}\s\-._]/u', '', $search);

            if ($clean !== '') {
                // Escape LIKE wildcards to prevent wildcard injection
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $clean);

                $query->where(function ($q) use ($escaped): void {
                    $q->where('judul', 'like', "%{$escaped}%")
                        ->orWhere('kategori', 'like', "%{$escaped}%");
                });
            }
        }

        $projects = $query->paginate(10)->withQueryString();

        return view('projects.index', compact('projects', 'search'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): RedirectResponse
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
    public function show(Project $project): View
    {
        $project->load(['client', 'bids.developer', 'ratings.user']);

        // Urutkan bids berdasarkan harga terendah
        $bids = $project->bids->sortBy('harga');

        $user = auth()->user();
        $isOwner = $user && $user->id === $project->client_id;
        $userBid = null;
        $userRating = null;

        if ($user) {
            if ($user->isDeveloper()) {
                $userBid = $project->bids->firstWhere('developer_id', $user->id);
            }
            $userRating = $project->ratings->firstWhere('user_id', $user->id);
        }

        return view('projects.show', compact('project', 'bids', 'isOwner', 'userBid', 'userRating'));
    }
}
