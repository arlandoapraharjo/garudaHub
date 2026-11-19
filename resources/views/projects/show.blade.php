@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="card">
    <h1>
        {{ $project->judul }}
        <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
            {{ ucfirst($project->status) }}
        </span>
    </h1>
    <p class="muted">
        Kategori: <b>{{ $project->kategori }}</b> |
        Klien: <b>{{ $project->client->name }}</b> |
        Budget: <b>Rp {{ number_format($project->budget_min, 0, ',', '.') }} - Rp {{ number_format($project->budget_max, 0, ',', '.') }}</b> |
        Batas Lelang: <b>{{ $project->batas_lelang->format('d M Y') }}</b>
    </p>
    <div style="margin-top: 14px; white-space: pre-line;">
        {{ $project->deskripsi }}
    </div>
</div>

<div class="card">
    <h2>Daftar Penawaran ({{ $bids->count() }})</h2>
    @if($bids->isEmpty())
        <p class="muted">Belum ada penawaran untuk proyek ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Developer</th>
                    <th>Harga Penawaran</th>
                    <th>Durasi</th>
                    <th>Proposal</th>
                    <th>Status / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                    <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                        <td>
                            <b>{{ $bid->developer->name }}</b>
                            @if($bid->is_winner)
                                <br><span class="badge win">Pemenang</span>
                            @endif
                        </td>
                        <td><b>Rp {{ number_format($bid->harga, 0, ',', '.') }}</b></td>
                        <td>{{ $bid->durasi }} hari</td>
                        <td style="white-space: pre-line; max-width: 320px;">{{ $bid->proposal }}</td>
                        <td>
                            @if($isOwner && $project->status === 'terbuka')
                                <form method="POST" action="{{ route('bids.pick', [$project, $bid]) }}" onsubmit="return confirm('Pilih penawaran {{ $bid->developer->name }} sebagai pemenang lelang? Proyek akan langsung ditutup.');">
                                    @csrf
                                    <button type="submit" class="sm">Pilih Pemenang</button>
                                </form>
                            @elseif($bid->is_winner)
                                <span class="badge win">Terpilih</span>
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Panel Ajukan Penawaran khusus Developer --}}
@auth
    @if(auth()->user()->isDeveloper())
        <div class="card">
            <h2>Ajukan Penawaran</h2>
            @if(!$project->isOpen())
                <p class="muted">Lelang untuk proyek ini sudah ditutup; penawaran baru tidak dapat diajukan.</p>
            @elseif($userBid)
                <div class="flash ok" style="margin-bottom: 0;">
                    Anda sudah mengajukan penawaran untuk proyek ini seharga <b>Rp {{ number_format($userBid->harga, 0, ',', '.') }}</b> (durasi {{ $userBid->durasi }} hari).
                </div>
            @else
                <form method="POST" action="{{ route('bids.store', $project) }}">
                    @csrf
                    <div class="row">
                        <div>
                            <label for="harga">Harga Penawaran (Rp)</label>
                            <input type="number" id="harga" name="harga" min="1" value="{{ old('harga') }}" required placeholder="Contoh: 5000000">
                        </div>
                        <div>
                            <label for="durasi">Durasi Pengerjaan (hari)</label>
                            <input type="number" id="durasi" name="durasi" min="1" value="{{ old('durasi') }}" required placeholder="Contoh: 30">
                        </div>
                    </div>
                    <label for="proposal">Proposal Penawaran</label>
                    <textarea id="proposal" name="proposal" required placeholder="Jelaskan pendekatan solusi Anda, pengalaman yang relevan, atau teknologi yang akan digunakan...">{{ old('proposal') }}</textarea>

                    <p style="margin-top: 16px;">
                        <button type="submit">Kirim Penawaran</button>
                    </p>
                </form>
            @endif
        </div>
    @endif
@else
    @if($project->isOpen())
        <div class="card">
            <p class="muted">
                Ingin mengajukan penawaran untuk proyek ini? <a href="{{ route('login') }}">Masuk</a> atau <a href="{{ route('register') }}">Daftar</a> sebagai Developer.
            </p>
        </div>
    @endif
@endauth
@endsection
