@extends('layouts.app')

@section('title', 'Sesi Berakhir (419)')

@section('content')
<div class="panel empty-state max-w-auth">
    <h1 class="text-xl bold mb-2">419 — Sesi Kedaluwarsa</h1>
    <p class="muted mb-4">Halaman telah kedaluwarsa karena tidak ada aktivitas. Silakan segarkan dan coba lagi.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Masuk Kembali</a>
</div>
@endsection
