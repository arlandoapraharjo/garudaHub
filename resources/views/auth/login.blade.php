@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="card" style="max-width: 460px; margin: 30px auto;">
    <h1>Masuk</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus maxlength="100">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <div style="margin: 12px 0;">
            <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer;">
                <input type="checkbox" name="remember" style="width: auto;"> Ingat saya
            </label>
        </div>

        <p style="margin-top: 18px; display: flex; align-items: center; gap: 14px;">
            <button type="submit">Masuk</button>
            <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
        </p>
    </form>
</div>
@endsection
