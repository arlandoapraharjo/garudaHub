@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div style="margin-bottom: 12px;">
    <a href="{{ route('home') }}" class="muted" style="font-size: 13px;">&larr; Kembali ke daftar proyek</a>
</div>

{{-- Project Details Panel --}}
<div class="panel">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 12px;">
        <div>
            <h1 style="margin: 0 0 6px 0; font-size: 18px;">
                {{ $project->judul }}
                <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}" style="vertical-align: middle; margin-left: 6px;">
                    {{ ucfirst($project->status) }}
                </span>
            </h1>
            <div class="muted" style="font-size: 12px;">
                Kategori: <b>{{ $project->kategori }}</b> &bull;
                Klien: <b>{{ $project->client->name }}</b> &bull;
                Batas Lelang: <span class="text-mono">{{ $project->batas_lelang->format('d M Y') }}</span>
            </div>
        </div>

        <div style="text-align: right;">
            <div class="muted" style="font-size: 11px; text-transform: uppercase;">Alokasi Budget</div>
            <div class="text-mono" style="font-size: 14px; font-weight: 700;">
                Rp {{ number_format($project->budget_min, 0, ',', '.') }} – {{ number_format($project->budget_max, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div style="padding-top: 12px; border-top: 1px solid var(--border); font-size: 13px; white-space: pre-line; line-height: 1.6;">
        {{ $project->deskripsi }}
    </div>
</div>

{{-- Bids Table Panel --}}
<div class="panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h2>Daftar Penawaran ({{ $bids->count() }})</h2>
        <span class="muted" style="font-size: 12px;">Diurutkan dari harga terendah</span>
    </div>

    @if($bids->isEmpty())
        <p class="muted" style="padding: 16px 0;">Belum ada penawaran yang diajukan untuk proyek ini.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Developer</th>
                        <th style="text-align: right;">Harga</th>
                        <th style="text-align: right;">Durasi</th>
                        <th>Proposal</th>
                        <th style="text-align: right;">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bids as $bid)
                        <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                            <td style="white-space: nowrap;">
                                <b>{{ $bid->developer->name }}</b>
                                @if($bid->is_winner)
                                    <div><span class="badge win" style="font-size: 10px;">Pemenang</span></div>
                                @endif
                            </td>
                            <td class="text-mono" style="text-align: right; white-space: nowrap; font-weight: 600;">
                                Rp {{ number_format($bid->harga, 0, ',', '.') }}
                            </td>
                            <td class="text-mono muted" style="text-align: right; white-space: nowrap;">
                                {{ $bid->durasi }} hari
                            </td>
                            <td style="font-size: 12px; max-width: 340px; white-space: pre-line;">
                                {{ $bid->proposal }}
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if($isOwner && $project->status === 'terbuka')
                                    <form method="POST" action="{{ route('bids.pick', [$project, $bid]) }}" onsubmit="return confirm('Pilih {{ $bid->developer->name }} sebagai pemenang? Proyek akan ditutup.');">
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
                <div class="alert ok" style="margin-bottom: 0;">
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

                    <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary">Kirim Penawaran</button>
                    </div>
                </form>
            @endif
        </div>
    @endif
@else
    @if($project->isOpen())
        <div class="panel" style="text-align: center; padding: 16px;">
            <p class="muted">
                Untuk mengajukan penawaran: <a href="{{ route('login') }}" style="font-weight: 600;">Masuk</a> atau <a href="{{ route('register') }}" style="font-weight: 600;">Daftar</a> sebagai Developer.
            </p>
        </div>
    @endif
@endauth
@endsection
