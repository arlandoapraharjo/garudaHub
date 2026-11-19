@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; gap: 12px; flex-wrap: wrap;">
    <div>
        <h1 style="margin: 0;">Daftar Proyek</h1>
        <p class="muted" style="margin-top: 2px;">Lelang proyek terbuka dan penawaran developer.</p>
    </div>

    <form method="GET" action="{{ route('home') }}" style="display: flex; gap: 6px;">
        <input type="text" name="q" placeholder="Cari judul atau kategori..." value="{{ $search }}" style="width: 220px;" maxlength="100">
        <button type="submit" class="btn-primary">Cari</button>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sec">Reset</a>
        @endif
    </form>
</div>

@if($projects->isEmpty())
    <div class="panel" style="text-align: center; padding: 32px 16px;">
        <p class="muted">Belum ada proyek yang cocok dengan pencarian.</p>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sm btn-sec" style="margin-top: 8px;">Reset Pencarian</a>
        @endif
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek & Kategori</th>
                    <th>Klien</th>
                    <th style="text-align: right;">Rentang Budget</th>
                    <th style="text-align: right;">Batas Lelang</th>
                    <th style="text-align: right;">Penawaran</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>
                            <div>
                                <b><a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a></b>
                            </div>
                            <div class="muted" style="font-size: 12px; margin-top: 1px;">
                                {{ $project->kategori }} &bull; {{ Str::limit($project->deskripsi, 80) }}
                            </div>
                        </td>
                        <td class="muted">{{ $project->client->name }}</td>
                        <td class="text-mono" style="text-align: right; font-size: 12px; white-space: nowrap;">
                            Rp {{ number_format($project->budget_min, 0, ',', '.') }} – {{ number_format($project->budget_max, 0, ',', '.') }}
                        </td>
                        <td class="text-mono muted" style="text-align: right; font-size: 12px; white-space: nowrap;">
                            {{ $project->batas_lelang->format('d M Y') }}
                        </td>
                        <td style="text-align: right; font-size: 12px; white-space: nowrap;">
                            <b>{{ $project->bids_count }}</b> bid
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">
        {{ $projects->links() }}
    </div>
@endif
@endsection
