@extends('layouts.app')

@section('title', 'Pasang Proyek')

@section('content')
<div class="card">
    <h1>Pasang Proyek Baru</h1>
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <label for="judul">Judul Proyek</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required maxlength="200" placeholder="Contoh: Pembuatan Website Toko Online">

        <label for="deskripsi">Deskripsi Lengkap</label>
        <textarea id="deskripsi" name="deskripsi" required placeholder="Jelaskan kebutuhan, fitur yang diinginkan, dan teknologi...">{{ old('deskripsi') }}</textarea>

        <label for="kategori">Kategori</label>
        <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" required maxlength="50" placeholder="Web, Mobile, Desain, Data, AI...">

        <div class="row">
            <div>
                <label for="budget_min">Budget Minimum (Rp)</label>
                <input type="number" id="budget_min" name="budget_min" min="0" value="{{ old('budget_min') }}" required placeholder="Contoh: 3000000">
            </div>
            <div>
                <label for="budget_max">Budget Maksimum (Rp)</label>
                <input type="number" id="budget_max" name="budget_max" min="0" value="{{ old('budget_max') }}" required placeholder="Contoh: 6000000">
            </div>
            <div>
                <label for="batas_lelang">Batas Lelang</label>
                <input type="date" id="batas_lelang" name="batas_lelang" min="{{ date('Y-m-d') }}" value="{{ old('batas_lelang') }}" required>
            </div>
        </div>

        <p style="margin-top: 20px;">
            <button type="submit">Pasang Proyek</button>
            <a href="{{ route('dashboard') }}" class="btn sec" style="margin-left: 8px;">Batal</a>
        </p>
    </form>
</div>
@endsection
