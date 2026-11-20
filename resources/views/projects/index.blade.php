@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
    <div>
        <h1 style="margin: 0;">Daftar Proyek Lelang</h1>
        <p class="muted" style="margin-top: 2px;">Temukan proyek terbuka dari klien dan ajukan penawaran terbaik Anda.</p>
    </div>

    <form method="GET" action="{{ route('home') }}" style="display: flex; gap: 6px;">
        <input type="text" name="q" placeholder="Cari judul atau kategori..." value="{{ $search }}" style="width: 240px;" maxlength="100">
        <button type="submit" class="btn-primary">Cari Proyek</button>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sec">Reset</a>
        @endif
    </form>
</div>

@if($projects->isEmpty())
    <div class="panel" style="text-align: center; padding: 40px 16px;">
        <p class="muted" style="margin-bottom: 8px;">Tidak ada proyek yang sesuai dengan pencarian Anda.</p>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sm btn-sec">Tampilkan Semua Proyek</a>
        @endif
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($projects as $project)
            <div class="panel" style="margin-bottom: 0; padding: 18px 20px;">
                {{-- Header Baris Kartu --}}
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span class="badge">{{ $project->kategori }}</span>
                        <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                        <span class="muted" style="font-size: 12px;">Dipasang oleh <b>{{ $project->client->name }}</b></span>
                    </div>
                    <div class="muted" style="font-size: 12px;">
                        Batas Lelang: <b class="text-mono" style="color: var(--text);">{{ $project->batas_lelang->format('d M Y') }}</b>
                    </div>
                </div>

                {{-- Judul Proyek --}}
                <h2 style="margin: 0 0 6px 0; font-size: 16px;">
                    <a href="{{ route('projects.show', $project) }}" style="color: var(--text);">{{ $project->judul }}</a>
                </h2>

                {{-- Deskripsi Cuplikan --}}
                <p class="muted" style="font-size: 13px; line-height: 1.5; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $project->deskripsi }}
                </p>

                {{-- Footer Kartu: Budget & Tombol Aksi Nyata --}}
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div class="muted" style="font-size: 11px; text-transform: uppercase;">Alokasi Budget:</div>
                        <div class="text-mono" style="font-size: 14px; font-weight: 700; color: #065f46;">
                            Rp {{ number_format($project->budget_min, 0, ',', '.') }} – {{ number_format($project->budget_max, 0, ',', '.') }}
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 14px;">
                        <span class="muted" style="font-size: 12px;"><b>{{ $project->bids_count }}</b> penawaran masuk</span>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary" style="padding: 7px 16px;">
                            Buka Lelang
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 16px;">
        {{ $projects->links() }}
    </div>
@endif
@endsection
