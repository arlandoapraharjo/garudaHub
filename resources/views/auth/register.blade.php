@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="card" style="max-width: 480px; margin: 30px auto;">
    <h1>Daftar Akun</h1>
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
            <option value="klien" {{ old('role') === 'klien' ? 'selected' : '' }}>Klien (Pemasang Proyek)</option>
            <option value="developer" {{ old('role') === 'developer' ? 'selected' : '' }}>Developer (Penawar Proyek)</option>
        </select>

        <p style="margin-top: 20px; display: flex; align-items: center; gap: 14px;">
            <button type="submit">Daftar Sekarang</button>
            <a href="{{ route('login') }}">Sudah punya akun? Masuk</a>
        </p>
    </form>
</div>
@endsection
