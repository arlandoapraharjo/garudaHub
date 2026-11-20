@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="mb-3">
    <a href="{{ route('home') }}" class="muted text-base">&larr; Kembali ke daftar proyek</a>
</div>

{{-- Project Details Panel --}}
<div class="panel">
    <div class="flex-between flex-start gap-16 wrap mb-3">
        <div>
            <h1 class="m-0 mb-1 text-xl">
                {{ $project->judul }}
                <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }} ml-2">
                    {{ ucfirst($project->status) }}
                </span>
            </h1>
            <div class="muted text-sm">
                Kategori: <b>{{ $project->kategori }}</b> &bull;
                Klien: <b>{{ $project->client->name }}</b> &bull;
                Batas Lelang: <span class="text-mono">{{ $project->batas_lelang->format('d M Y') }}</span>
            </div>
        </div>

        <div class="text-right">
            <div class="muted text-xs">ALOKASI BUDGET</div>
            <div class="text-mono text-md bold text-green">
                Rp {{ number_format($project->budget_min, 0, ',', '.') }} – {{ number_format($project->budget_max, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="pt-3 border-top text-base pre-line">
        {{ $project->deskripsi }}
    </div>
</div>

{{-- Bids Table Panel --}}
<div class="panel">
    <div class="flex-between mb-2">
        <h2 class="m-0">Daftar Penawaran ({{ $bids->count() }})</h2>
        <span class="muted text-sm">Diurutkan dari harga terendah</span>
    </div>

    @if($bids->isEmpty())
        <p class="muted p-4 text-center">Belum ada penawaran yang diajukan untuk proyek ini.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Developer</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Durasi</th>
                        <th>Proposal</th>
                        <th class="text-right">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bids as $bid)
                        <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                            <td class="nowrap">
                                <b>{{ $bid->developer->name }}</b>
                                @if($bid->is_winner)
                                    <div><span class="badge win text-xs">Pemenang</span></div>
                                @endif
                            </td>
                            <td class="text-mono text-right nowrap semibold">
                                Rp {{ number_format($bid->harga, 0, ',', '.') }}
                            </td>
                            <td class="text-mono muted text-right nowrap">
                                {{ $bid->durasi }} hari
                            </td>
                            <td class="text-sm pre-line">
                                {{ $bid->proposal }}
                            </td>
                            <td class="text-right nowrap">
                                @if($isOwner && $project->status === 'terbuka')
                                    <form method="POST" action="{{ route('bids.pick', [$project, $bid]) }}">
                                        @csrf
                                        <button type="submit" class="sm btn-primary">Pilih Pemenang</button>
                                    </form>
                                @elseif($bid->is_winner)
                                    <span class="badge win">Terpilih</span>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Bid Form for Developer --}}
@auth
    @if(auth()->user()->isDeveloper())
        <div class="panel">
            <h2>Ajukan Penawaran</h2>
            @if(!$project->isOpen())
                <p class="muted">Lelang proyek sudah ditutup; penawaran baru tidak dapat diajukan.</p>
            @elseif($userBid)
                <div class="alert ok m-0">
                    Anda sudah mengajukan penawaran: <span class="text-mono">Rp {{ number_format($userBid->harga, 0, ',', '.') }}</span> ({{ $userBid->durasi }} hari).
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
                            <label for="durasi">Durasi (hari)</label>
                            <input type="number" id="durasi" name="durasi" min="1" value="{{ old('durasi') }}" required placeholder="Contoh: 14">
                        </div>
                    </div>

                    <label for="proposal">Proposal Penawaran</label>
                    <textarea id="proposal" name="proposal" required placeholder="Jelaskan rencana pengerjaan, kesiapan stack, dan komitmen waktu Anda...">{{ old('proposal') }}</textarea>

                    <div class="mt-3 flex-end">
                        <button type="submit" class="btn-primary">Kirim Penawaran</button>
                    </div>
                </form>
            @endif
        </div>
    @endif
@else
    @if($project->isOpen())
        <div class="panel text-center p-4">
            <p class="muted">
                Untuk mengajukan penawaran: <a href="{{ route('login') }}" class="semibold">Masuk</a> atau <a href="{{ route('register') }}" class="semibold">Daftar</a> sebagai Developer.
            </p>
        </div>
    @endif
@endauth
@endsection
