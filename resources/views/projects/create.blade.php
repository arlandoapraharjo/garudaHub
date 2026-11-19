@extends('layouts.app')

@section('title', 'Pasang Proyek')

@section('content')
<div style="margin-bottom: 12px;">
    <a href="{{ route('dashboard') }}" class="muted" style="font-size: 13px;">&larr; Kembali ke dasbor</a>
</div>

<div class="panel" style="max-width: 640px; margin: auto;">
    <h1>Pasang Proyek Baru</h1>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <label for="judul">Judul Proyek</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required maxlength="200" placeholder="Misal: Website Toko Batik Online">

        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required>
            <option value="">Pilih kategori...</option>
            @foreach(['Web', 'Mobile', 'Desain', 'Data', 'AI'] as $cat)
                <option value="{{ $cat }}" {{ old('kategori') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <label for="deskripsi">Deskripsi Kebutuhan</label>
        <textarea id="deskripsi" name="deskripsi" required placeholder="Jelaskan kebutuhan sistem, fitur utama, dan kriteria pengerjaan...">{{ old('deskripsi') }}</textarea>

        <div class="row">
            <div>
                <label for="budget_min">Budget Minimum (Rp)</label>
                <input type="number" id="budget_min" name="budget_min" min="0" value="{{ old('budget_min') }}" required placeholder="3000000">
            </div>
            <div>
                <label for="budget_max">Budget Maksimum (Rp)</label>
                <input type="number" id="budget_max" name="budget_max" min="0" value="{{ old('budget_max') }}" required placeholder="6000000">
            </div>
            <div>
                <label for="batas_lelang">Batas Waktu Lelang</label>
                <input type="date" id="batas_lelang" name="batas_lelang" min="{{ date('Y-m-d') }}" value="{{ old('batas_lelang') }}" required>
            </div>
        </div>

        <div style="margin-top: 16px; display: flex; justify-content: flex-end; gap: 8px;">
            <a href="{{ route('dashboard') }}" class="btn btn-sec">Batal</a>
            <button type="submit" class="btn-primary">Pasang Proyek</button>
        </div>
    </form>
</div>
@endsection
