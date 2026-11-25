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
                Rating: <b class="text-yellow">★ {{ number_format($project->averageRating(), 1) }}</b> ({{ $project->ratings->count() }} ulasan) &bull;
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
{{-- Ratings & Reviews Panel --}}
<div class="panel">
    <div class="flex-between mb-3">
        <div>
            <h2 class="m-0">Rating & Ulasan Proyek</h2>
            <div class="muted text-sm">
                Rata-rata: <b class="text-md text-yellow">★ {{ number_format($project->averageRating(), 1) }} / 5.0</b> ({{ $project->ratings->count() }} ulasan)
            </div>
        </div>
    </div>

    {{-- Rating Submission Form for Authenticated Users --}}
    @auth
        <div class="mb-4 p-3 border rounded bg-subtle">
            <h3 class="text-base mb-2">{{ $userRating ? 'Perbarui Rating & Ulasan Anda' : 'Beri Rating untuk Proyek Ini' }}</h3>
            <form method="POST" action="{{ route('ratings.store', $project) }}">
                @csrf
                <div class="row mb-2">
                    <div class="w-240">
                        <label for="skor">Skor Bintang (1 - 5)</label>
                        <select id="skor" name="skor" required>
                            <option value="">Pilih Skor...</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ (old('skor', $userRating?->skor) == $i) ? 'selected' : '' }}>
                                    {{ str_repeat('★', $i) }} ({{ $i }} Bintang)
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <label for="ulasan">Ulasan / Masukan (Opsional)</label>
                <textarea id="ulasan" name="ulasan" rows="2" placeholder="Bagikan tanggapan Anda tentang proyek ini...">{{ old('ulasan', $userRating?->ulasan) }}</textarea>
                <div class="mt-2 flex-end">
                    <button type="submit" class="btn-primary sm">{{ $userRating ? 'Simpan Perubahan' : 'Kirim Rating' }}</button>
                </div>
            </form>
        </div>
    @else
        <p class="muted text-sm mb-3">
            <a href="{{ route('login') }}" class="semibold">Masuk</a> untuk memberikan rating dan ulasan pada proyek ini.
        </p>
    @endauth

    {{-- List of Reviews --}}
    @if($project->ratings->isEmpty())
        <p class="muted text-center p-3">Belum ada ulasan untuk proyek ini.</p>
    @else
        <div class="flex-col gap-12">
            @foreach($project->ratings as $rating)
                <div class="p-3 border-bottom">
                    <div class="flex-between">
                        <div>
                            <b>{{ $rating->user->name }}</b>
                            <span class="muted text-xs ml-1">({{ ucfirst($rating->user->role) }})</span>
                        </div>
                        <div class="text-mono bold text-yellow">
                            {{ str_repeat('★', $rating->skor) }}{{ str_repeat('☆', 5 - $rating->skor) }}
                            <span class="muted text-xs">({{ $rating->skor }}/5)</span>
                        </div>
                    </div>
                    @if($rating->ulasan)
                        <div class="text-sm mt-1 pre-line text-secondary">{{ $rating->ulasan }}</div>
                    @endif
                    <div class="muted text-xs mt-1">{{ $rating->created_at->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Catatan Pengawasan Proyek --}}
<div class="panel">
    <h3>Pusat Bantuan & Pengawasan Proyek</h3>
    <p class="muted">Jika ada kendala pembayaran atau lelang, hubungi administrator kampus.</p>
</div>
@endsection
