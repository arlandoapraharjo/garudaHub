@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="panel" style="max-width: 380px; margin: 40px auto; padding: 24px;">
    <h1 style="margin-bottom: 4px;">Masuk</h1>
    <p class="muted" style="margin-bottom: 16px;">Masuk ke akun Garuda-Hub Anda.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus maxlength="100">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <div style="margin: 10px 0; font-size: 13px;">
            <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: normal; cursor: pointer; margin: 0;">
                <input type="checkbox" name="remember" style="width: auto;"> Ingat saya
            </label>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 8px;">Masuk</button>
    </form>

    <div style="margin-top: 14px; text-align: center; font-size: 13px;">
        <span class="muted">Belum punya akun?</span> <a href="{{ route('register') }}">Daftar</a>
    </div>

    <div style="margin-top: 20px; padding-top: 12px; border-top: 1px solid var(--border); font-size: 11px;" class="muted">
        <div><strong>Akun Demo:</strong></div>
        <div>Admin: <span class="text-mono">admin@example.com</span> (admin123)</div>
        <div>Klien: <span class="text-mono">budi@example.com</span> (klien123)</div>
        <div>Dev: <span class="text-mono">dewi@example.com</span> (dev123)</div>
    </div>
</div>
@endsection
