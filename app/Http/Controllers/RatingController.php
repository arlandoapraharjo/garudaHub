<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Store or update a rating for the project.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'skor' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['nullable', 'string', 'max:1000'],
        ], [
            'skor.required' => 'Pilih skor rating antara 1 sampai 5 bintang.',
            'skor.min' => 'Skor rating minimal 1 bintang.',
            'skor.max' => 'Skor rating maksimal 5 bintang.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        $project->ratings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'skor' => (int) $validated['skor'],
                'ulasan' => $validated['ulasan'] ?? null,
            ]
        );

        return redirect()->route('projects.show', $project)
            ->with('success', 'Rating dan ulasan Anda berhasil disimpan.');
    }
}
