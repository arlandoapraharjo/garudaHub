@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="panel" style="max-width: 400px; margin: 40px auto; padding: 24px;">
    <h1 style="margin-bottom: 4px;">Daftar Akun</h1>
    <p class="muted" style="margin-bottom: 16px;">Buat akun baru untuk mulai lelang.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus maxlength="100">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="100">

        <label for="password">Password (minimal 6 karakter)</label>
        <input type="password" id="password" name="password" required minlength="6" maxlength="72" autocomplete="new-password">

        <label for="role">Daftar Sebagai</label>
        <select id="role" name="role" required>
            <option value="klien" {{ old('role') === 'klien' ? 'selected' : '' }}>Klien</option>
            <option value="developer" {{ old('role') === 'developer' ? 'selected' : '' }}>Developer</option>
        </select>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 14px;">Daftar</button>
    </form>

    <div style="margin-top: 14px; text-align: center; font-size: 13px;">
        <span class="muted">Sudah punya akun?</span> <a href="{{ route('login') }}">Masuk</a>
    </div>
</div>
@endsection
