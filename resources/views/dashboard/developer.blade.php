@extends('layouts.app')

@section('title', 'Dasbor Developer')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h1>Penawaran Saya</h1>
        <a class="btn sec" href="{{ route('home') }}">Jelajahi Proyek Baru</a>
    </div>

    @if($bids->isEmpty())
        <p class="muted">Anda belum mengajukan penawaran untuk proyek manapun. <a href="{{ route('home') }}">Cari proyek lelang</a>.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Proyek</th>
                    <th>Harga Penawaran</th>
                    <th>Durasi</th>
                    <th>Status Proyek</th>
                    <th>Hasil Penawaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $b)
                    <tr class="{{ $b->is_winner ? 'winner' : '' }}">
                        <td>
                            <b><a href="{{ route('projects.show', $b->project) }}">{{ $b->project->judul }}</a></b>
                            <div class="muted">Klien: {{ $b->project->client->name }} | {{ $b->project->kategori }}</div>
                        </td>
                        <td><b>Rp {{ number_format($b->harga, 0, ',', '.') }}</b></td>
                        <td>{{ $b->durasi }} hari</td>
                        <td>
                            <span class="badge {{ $b->project->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($b->project->status) }}
                            </span>
                        </td>
                        <td>
                            @if($b->is_winner)
                                <span class="badge win">Menang</span>
                            @elseif($b->project->status === 'terbuka')
                                <span class="muted">Menunggu evaluasi</span>
                            @else
                                <span class="badge closed">Tidak terpilih</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
