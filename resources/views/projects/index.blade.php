@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div class="card">
    <h1>Daftar Proyek Lelang</h1>
    <form class="search" method="GET" action="{{ route('home') }}">
        <input type="text" name="q" placeholder="Cari judul atau kategori proyek..." value="{{ $search }}" maxlength="100">
        <button type="submit">Cari</button>
    </form>
    @if($search !== '')
        <p class="muted" style="margin-top: 10px;">
            Hasil pencarian untuk: <b>{{ $search }}</b> ({{ $projects->total() }} proyek ditemukan)
            <a href="{{ route('home') }}" style="margin-left: 8px;">[Reset]</a>
        </p>
    @endif
</div>

@if($projects->isEmpty())
    <div class="card">
        <p class="muted">Belum ada proyek yang cocok.</p>
    </div>
@else
    @foreach($projects as $project)
        <div class="card">
            <h2>
                <a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a>
                <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                    {{ ucfirst($project->status) }}
                </span>
            </h2>
            <p class="muted">
                Kategori: <b>{{ $project->kategori }}</b> |
                Klien: <b>{{ $project->client->name }}</b> |
                Budget: <b>Rp {{ number_format($project->budget_min, 0, ',', '.') }} - Rp {{ number_format($project->budget_max, 0, ',', '.') }}</b> |
                Batas Lelang: <b>{{ $project->batas_lelang->format('d M Y') }}</b> |
                <b>{{ $project->bids_count }}</b> penawaran
            </p>
            <p>{{ Str::limit($project->deskripsi, 160) }}</p>
        </div>
    @endforeach

    <div class="pagination-nav">
        {{ $projects->links() }}
    </div>
@endif
@endsection
