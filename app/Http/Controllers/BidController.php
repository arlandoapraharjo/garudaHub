<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    /**
     * Store a newly created bid for a project.
     */
    public function store(Request $request, Project $project)
    {
        $user = $request->user();

        // Validasi status proyek
        if (! $project->isOpen()) {
            return back()->with('error', 'Lelang untuk proyek ini sudah ditutup.');
        }

        // Cek penawaran ganda
        $alreadyBid = $project->bids()->where('developer_id', $user->id)->exists();
        if ($alreadyBid) {
            return back()->with('error', 'Anda sudah mengajukan penawaran untuk proyek ini.');
        }

        $validated = $request->validate([
            'harga' => ['required', 'integer', 'min:1'],
            'durasi' => ['required', 'integer', 'min:1'],
            'proposal' => ['required', 'string', 'max:5000'],
        ], [
            'harga.required' => 'Harga penawaran wajib diisi.',
            'harga.min' => 'Harga penawaran minimal Rp 1.',
            'durasi.required' => 'Durasi pengerjaan wajib diisi.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'proposal.required' => 'Proposal penawaran wajib diisi.',
        ]);

        $project->bids()->create([
            'developer_id' => $user->id,
            'harga' => $validated['harga'],
            'durasi' => $validated['durasi'],
            'proposal' => $validated['proposal'],
            'is_winner' => false,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Penawaran Anda berhasil diajukan.');
    }

    /**
     * Pick a winning bid (Client only) using database transaction.
     */
    public function pickWinner(Request $request, Project $project, Bid $bid)
    {
        $user = $request->user();

        // Otorisasi: hanya pemilik proyek
        if ($project->client_id !== $user->id) {
            abort(403, 'Anda bukan pemilik proyek ini.');
        }

        // Pastikan bid milik proyek ini
        if ($bid->project_id !== $project->id) {
            abort(404, 'Penawaran tidak ditemukan pada proyek ini.');
        }

        if ($project->status !== 'terbuka') {
            return back()->with('error', 'Proyek ini sudah ditutup.');
        }

        // Transaksi database untuk menjamin integritas data
        DB::transaction(function () use ($project, $bid) {
            $bid->update(['is_winner' => true]);
            $project->update(['status' => 'ditutup']);
        });

        return redirect()->route('projects.show', $project)
            ->with('success', 'Pemenang lelang berhasil dipilih; status proyek telah ditutup.');
    }
}
