@extends('layouts.app')

@section('title', 'Akses Ditolak (403)')

@section('content')
<div class="panel empty-state max-w-auth">
    <h1 class="text-xl bold mb-2">403 — Akses Ditolak</h1>
    <p class="muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
