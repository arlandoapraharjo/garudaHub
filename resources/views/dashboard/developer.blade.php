@extends('layouts.app')

@section('title', 'Dasbor Developer')

@section('content')
<div class="flex-between wrap mb-4 gap-12">
    <div>
        <h1 class="m-0">Penawaran Saya</h1>
        <p class="muted mt-1">Riwayat penawaran yang telah Anda ajukan pada proyek lelang.</p>
    </div>
    <a class="btn btn-sec" href="{{ route('home') }}">Jelajahi Proyek Baru</a>
</div>

@if($bids->isEmpty())
    <div class="panel empty-state">
        <p class="muted mb-2">Anda belum mengajukan penawaran pada proyek manapun.</p>
        <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Cari & Tawar Proyek Sekarang</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Proyek Yang Ditawar</th>
                    <th>Klien</th>
                    <th class="text-right">Penawaran Anda</th>
                    <th class="text-right">Durasi Pengerjaan</th>
                    <th class="text-right">Status Lelang</th>
                    <th class="text-right">Hasil Evaluasi</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $b)
                    <tr class="{{ $b->is_winner ? 'winner' : '' }}">
                        <td>
                            <b><a href="{{ route('projects.show', $b->project) }}">{{ $b->project->judul }}</a></b>
                            <div class="muted text-xs">Kategori: {{ $b->project->kategori }}</div>
                        </td>
                        <td class="muted">{{ $b->project->client->name }}</td>
                        <td class="text-mono text-right text-sm nowrap semibold">
                            Rp {{ number_format($b->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-mono muted text-right text-sm nowrap">
                            {{ $b->durasi }} hari
                        </td>
                        <td class="text-right nowrap">
                            <span class="badge {{ $b->project->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($b->project->status) }}
                            </span>
                        </td>
                        <td class="text-right nowrap">
                            @if($b->is_winner)
                                <span class="badge win">★ Menang</span>
                            @elseif($b->project->status === 'terbuka')
                                <span class="muted">Menunggu</span>
                            @else
                                <span class="badge closed">Tidak Terpilih</span>
                            @endif
                        </td>
                        <td class="text-right nowrap">
                            <a class="btn btn-sm btn-primary action-btn-sm" href="{{ route('projects.show', $b->project) }}">
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
