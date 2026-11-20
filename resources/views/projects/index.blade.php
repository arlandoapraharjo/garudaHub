@extends('layouts.app')

@section('title', 'Daftar Proyek')

@section('content')
<div class="flex-between wrap mb-4 gap-12">
    <div>
        <h1 class="m-0">Daftar Proyek Lelang</h1>
        <p class="muted mt-1">Temukan proyek terbuka dari klien dan ajukan penawaran terbaik Anda.</p>
    </div>

    <form method="GET" action="{{ route('home') }}" class="flex gap-6">
        <input type="text" name="q" placeholder="Cari judul atau kategori..." class="search-input" maxlength="100">
        <button type="submit" class="btn-primary">Cari Proyek</button>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sec">Reset</a>
        @endif
    </form>
</div>

@if($projects->isEmpty())
    <div class="panel empty-state">
        <p class="muted mb-2">Tidak ada proyek yang sesuai dengan pencarian Anda.</p>
        @if($search !== '')
            <a href="{{ route('home') }}" class="btn btn-sm btn-sec">Tampilkan Semua Proyek</a>
        @endif
    </div>
@else
    <div class="flex-col gap-12">
        @foreach($projects as $project)
            <div class="panel m-0 p-5">
                {{-- Header Baris Kartu --}}
                <div class="flex-between items-center gap-12 wrap mb-2">
                    <div class="flex items-center gap-8 wrap">
                        <span class="badge">{{ $project->kategori }}</span>
                        <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                        <span class="muted text-sm">Dipasang oleh <b>{{ $project->client->name }}</b></span>
                    </div>
                    <div class="muted text-sm">
                        Batas Lelang: <b class="text-mono">{{ $project->batas_lelang->format('d M Y') }}</b>
                    </div>
                </div>

                {{-- Judul Proyek --}}
                <h2 class="m-0 mb-1 text-lg">
                    <a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a>
                </h2>

                {{-- Deskripsi Cuplikan --}}
                <p class="muted text-base mb-3 line-clamp-2">
                    {{ $project->deskripsi }}
                </p>

                {{-- Footer Kartu: Budget & Tombol Aksi Nyata --}}
                <div class="flex-between items-center pt-3 border-top wrap gap-12">
                    <div>
                        <div class="muted text-xs">ALOKASI BUDGET:</div>
                        <div class="text-mono text-md bold text-green">
                            Rp {{ number_format($project->budget_min, 0, ',', '.') }} – {{ number_format($project->budget_max, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-14">
                        <span class="muted text-sm"><b>{{ $project->bids_count }}</b> penawaran masuk</span>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary action-btn-primary">
                            Buka Lelang
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@endif
@endsection
