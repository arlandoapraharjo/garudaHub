@extends('layouts.app')

@section('title', 'Terjadi Kesalahan (500)')

@section('content')
<div class="panel empty-state max-w-auth">
    <h1 class="text-xl bold mb-2">500 — Kesalahan Server</h1>
    <p class="muted mb-4">Terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
