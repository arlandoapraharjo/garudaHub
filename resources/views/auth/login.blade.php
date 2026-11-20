@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="panel max-w-auth p-6">
    <h1 class="mb-1">Masuk</h1>
    <p class="muted mb-4">Masuk ke akun Garuda-Hub Anda.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus maxlength="100">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <div class="my-auto mt-2 mb-2 text-base">
            <label class="flex items-center gap-6 normal cursor-pointer m-0">
                <input type="checkbox" name="remember" class="w-auto"> Ingat saya
            </label>
        </div>

        <button type="submit" class="btn-primary w-full mt-2">Masuk</button>
    </form>

    <div class="mt-3 text-center text-base">
        <span class="muted">Belum punya akun?</span> <a href="{{ route('register') }}">Daftar</a>
    </div>

    <div class="mt-5 pt-3 border-top text-xs muted">
        <div><strong>Akun Demo:</strong></div>
        <div>Admin: <span class="text-mono">admin@example.com</span> (admin123)</div>
        <div>Klien: <span class="text-mono">budi@example.com</span> (klien123)</div>
        <div>Dev: <span class="text-mono">dewi@example.com</span> (dev123)</div>
    </div>
</div>
@endsection
