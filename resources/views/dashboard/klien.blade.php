@extends('layouts.app')

@section('title', 'Dasbor Klien')

@section('content')
<div class="flex-between wrap mb-4 gap-12">
    <div>
        <h1 class="m-0">Proyek Saya</h1>
        <p class="muted mt-1">Daftar proyek lelang yang Anda publikasikan beserta tawaran masuk.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('projects.create') }}">+ Pasang Proyek Baru</a>
</div>

@if($projects->isEmpty())
    <div class="panel empty-state">
        <p class="muted mb-2">Anda belum memasang proyek apapun.</p>
        <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">Pasang Proyek Pertama Anda</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek</th>
                    <th>Kategori</th>
                    <th class="text-right">Alokasi Budget</th>
                    <th class="text-right">Batas Lelang</th>
                    <th class="text-right">Penawaran Masuk</th>
                    <th class="text-right">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>
                            <b><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a></b>
                        </td>
                        <td class="muted">{{ $p->kategori }}</td>
                        <td class="text-mono text-right text-sm nowrap">
                            Rp {{ number_format($p->budget_min, 0, ',', '.') }} – {{ number_format($p->budget_max, 0, ',', '.') }}
                        </td>
                        <td class="text-mono muted text-right text-sm nowrap">
                            {{ $p->batas_lelang->format('d M Y') }}
                        </td>
                        <td class="text-right text-sm nowrap">
                            <b>{{ $p->bids_count }}</b> pelamar
                        </td>
                        <td class="text-right nowrap">
                            <span class="badge {{ $p->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="text-right nowrap">
                            <a class="btn btn-sm btn-primary action-btn-sm" href="{{ route('projects.show', $p) }}">
                                Kelola & Pilih Pemenang
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
