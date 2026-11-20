@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="panel max-w-auth p-6">
    <h1 class="mb-1">Daftar Akun</h1>
    <p class="muted mb-4">Buat akun baru untuk mulai lelang.</p>

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

        <button type="submit" class="btn-primary w-full mt-4">Daftar</button>
    </form>

    <div class="mt-3 text-center text-base">
        <span class="muted">Sudah punya akun?</span> <a href="{{ route('login') }}">Masuk</a>
    </div>
</div>
@endsection
