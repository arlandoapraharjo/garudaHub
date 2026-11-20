@extends('layouts.app')

@section('title', 'Dasbor Developer')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
    <div>
        <h1 style="margin: 0;">Penawaran Saya</h1>
        <p class="muted" style="margin-top: 2px;">Riwayat penawaran yang telah Anda ajukan pada proyek lelang.</p>
    </div>
    <a class="btn btn-sec" href="{{ route('home') }}">Jelajahi Proyek Baru</a>
</div>

@if($bids->isEmpty())
    <div class="panel" style="text-align: center; padding: 40px 16px;">
        <p class="muted" style="margin-bottom: 8px;">Anda belum mengajukan penawaran pada proyek manapun.</p>
        <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Cari & Tawar Proyek Sekarang</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Proyek Yang Ditawar</th>
                    <th>Klien</th>
                    <th style="text-align: right;">Penawaran Anda</th>
                    <th style="text-align: right;">Durasi Pengerjaan</th>
                    <th style="text-align: right;">Status Lelang</th>
                    <th style="text-align: right;">Hasil Evaluasi</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $b)
                    <tr class="{{ $b->is_winner ? 'winner' : '' }}">
                        <td>
                            <b><a href="{{ route('projects.show', $b->project) }}">{{ $b->project->judul }}</a></b>
                            <div class="muted" style="font-size: 11px;">Kategori: {{ $b->project->kategori }}</div>
                        </td>
                        <td class="muted">{{ $b->project->client->name }}</td>
                        <td class="text-mono" style="text-align: right; font-size: 12px; white-space: nowrap; font-weight: 600;">
                            Rp {{ number_format($b->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-mono muted" style="text-align: right; font-size: 12px; white-space: nowrap;">
                            {{ $b->durasi }} hari
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <span class="badge {{ $b->project->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($b->project->status) }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if($b->is_winner)
                                <span class="badge win">★ Menang</span>
                            @elseif($b->project->status === 'terbuka')
                                <span class="muted">Menunggu</span>
                            @else
                                <span class="badge closed">Tidak Terpilih</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a class="btn btn-sm btn-primary" href="{{ route('projects.show', $b->project) }}" style="padding: 4px 10px;">
                                Buka Proyek
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
