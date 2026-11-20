@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan (404)')

@section('content')
<div class="panel empty-state max-w-auth">
    <h1 class="text-xl bold mb-2">404 — Tidak Ditemukan</h1>
    <p class="muted mb-4">Halaman yang Anda tuju tidak ditemukan atau telah dipindahkan.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
