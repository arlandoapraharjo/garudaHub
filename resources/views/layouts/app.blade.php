<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garuda-Hub') — Garuda-Hub</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<header>
    <div class="header-inner">
        <div class="flex items-center gap-20">
            <a class="brand" href="{{ route('home') }}">Garuda-Hub</a>
            <nav class="nav-items">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Proyek</a>
                @auth
                    @if(auth()->user()->isKlien())
                        <a class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}" href="{{ route('projects.create') }}">+ Pasang Proyek</a>
                    @endif
                    @if(!auth()->user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dasbor</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                @endauth
            </nav>
        </div>

        <div class="user-section">
            @auth
                <span class="user-label">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form class="inline" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-btn" type="submit">Keluar</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                <a class="btn btn-sm btn-primary" href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </div>
</header>

<main>
    @if(session('success'))
        <div class="alert ok">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert err">{{ session('error') }}</div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert err">
            <strong>Terdapat kesalahan:</strong>
            <ul class="errors-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    Garuda-Hub — Platform lelang proyek IT mahasiswa.
</footer>
</body>
</html>
