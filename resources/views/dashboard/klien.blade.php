@extends('layouts.app')

@section('title', 'Dasbor Klien')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h1>Proyek Saya</h1>
        <a class="btn" href="{{ route('projects.create') }}">+ Pasang Proyek Baru</a>
    </div>

    @if($projects->isEmpty())
        <p class="muted">Anda belum memasang proyek apapun. <a href="{{ route('projects.create') }}">Mulai pasang proyek sekarang</a>.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Judul Proyek</th>
                    <th>Status</th>
                    <th>Batas Lelang</th>
                    <th>Jumlah Penawaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td>
                            <b><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a></b>
                            <div class="muted">{{ $p->kategori }} | Rp {{ number_format($p->budget_min, 0, ',', '.') }} - Rp {{ number_format($p->budget_max, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $p->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>{{ $p->batas_lelang->format('d M Y') }}</td>
                        <td><b>{{ $p->bids_count }}</b> tawaran</td>
                        <td>
                            <a class="btn sm sec" href="{{ route('projects.show', $p) }}">Lihat Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
