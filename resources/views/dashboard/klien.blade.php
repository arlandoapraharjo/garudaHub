@extends('layouts.app')

@section('title', 'Dasbor Klien')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
    <div>
        <h1 style="margin: 0;">Proyek Saya</h1>
        <p class="muted" style="margin-top: 2px;">Daftar proyek lelang yang Anda publikasikan beserta tawaran masuk.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('projects.create') }}">+ Pasang Proyek Baru</a>
</div>

@if($projects->isEmpty())
    <div class="panel" style="text-align: center; padding: 40px 16px;">
        <p class="muted" style="margin-bottom: 8px;">Anda belum memasang proyek apapun.</p>
        <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">Pasang Proyek Pertama Anda</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Alokasi Budget</th>
                    <th style="text-align: right;">Batas Lelang</th>
                    <th style="text-align: right;">Penawaran Masuk</th>
                    <th style="text-align: right;">Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>
                            <b><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a></b>
                        </td>
                        <td class="muted">{{ $p->kategori }}</td>
                        <td class="text-mono" style="text-align: right; font-size: 12px; white-space: nowrap;">
                            Rp {{ number_format($p->budget_min, 0, ',', '.') }} – {{ number_format($p->budget_max, 0, ',', '.') }}
                        </td>
                        <td class="text-mono muted" style="text-align: right; font-size: 12px; white-space: nowrap;">
                            {{ $p->batas_lelang->format('d M Y') }}
                        </td>
                        <td style="text-align: right; font-size: 12px; white-space: nowrap;">
                            <b>{{ $p->bids_count }}</b> pelamar
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <span class="badge {{ $p->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a class="btn btn-sm btn-primary" href="{{ route('projects.show', $p) }}" style="padding: 4px 10px;">
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
