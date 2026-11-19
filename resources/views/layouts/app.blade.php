<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garuda-Hub') - Garuda-Hub</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; background: #f4f6f8; color: #1f2933; line-height: 1.5; }
        a { color: #0b5cad; text-decoration: none; }
        a:hover { text-decoration: underline; }
        header { background: #7a1020; color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.15); }
        header .in { max-width: 1000px; margin: auto; padding: 12px 16px; display: flex; flex-wrap: wrap; align-items: center; gap: 14px; }
        header a { color: #fff; }
        header .brand { font-weight: 700; font-size: 1.3rem; margin-right: auto; }
        main { max-width: 1000px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 18px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size: 1.5rem; margin: 0 0 14px; }
        h2 { font-size: 1.2rem; margin: 0 0 12px; }
        label { display: block; margin: 12px 0 4px; font-weight: 600; font-size: .95rem; }
        input, select, textarea { width: 100%; padding: 9px 12px; border: 1px solid #c5ccd3; border-radius: 6px; font: inherit; }
        input:focus, select:focus, textarea:focus { border-color: #7a1020; outline: none; box-shadow: 0 0 0 3px rgba(122,16,32,.15); }
        textarea { min-height: 110px; }
        .btn, button { display: inline-block; background: #7a1020; color: #fff; border: 0; border-radius: 6px; padding: 8px 16px; font: inherit; cursor: pointer; text-align: center; }
        .btn.sec, button.sec { background: #52606d; }
        .btn.danger, button.danger { background: #b42318; }
        .btn.sm, button.sm { padding: 4px 10px; font-size: .85rem; }
        .btn:hover, button:hover { opacity: .92; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid #e4e7eb; vertical-align: middle; }
        th { background: #f0f2f5; font-weight: 600; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: .8rem; font-weight: 600; color: #fff; }
        .badge.open { background: #1a7f37; }
        .badge.closed { background: #6b7280; }
        .badge.win { background: #b7791f; }
        .flash { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: .95rem; }
        .flash.ok, .flash.success { background: #d9f2e0; color: #0f5132; border: 1px solid #badbcc; }
        .flash.err, .flash.danger, .flash.error { background: #fde2e0; color: #842029; border: 1px solid #f5c2c7; }
        .muted { color: #6b7280; font-size: .9rem; }
        .row { display: flex; gap: 12px; flex-wrap: wrap; }
        .row > * { flex: 1; min-width: 140px; }
        form.inline { display: inline-block; margin: 0; }
        form.inline select { width: auto; padding: 4px 8px; display: inline-block; }
        .winner { background: #fff8e1; }
        .search { display: flex; gap: 8px; }
        .search input { flex: 1; }
        .search button { flex: none; }
        footer { text-align: center; color: #6b7280; font-size: .85rem; margin: 40px 0 20px; }
        .errors-list { margin: 0; padding-left: 20px; }
        .pagination-nav { margin-top: 16px; }
    </style>
</head>
<body>
<header>
    <div class="in">
        <a class="brand" href="{{ route('home') }}">Garuda-Hub</a>
        <a href="{{ route('home') }}">Proyek</a>
        @auth
            @if(auth()->user()->isKlien())
                <a href="{{ route('projects.create') }}">Pasang Proyek</a>
            @endif
            @if(!auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}">Dasbor</a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
            <span style="opacity: .9;">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
            <form class="inline" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sm sec" type="submit">Keluar</button>
            </form>
        @else
            <a href="{{ route('login') }}">Masuk</a>
            <a href="{{ route('register') }}">Daftar</a>
        @endauth
    </div>
</header>

<main>
    @if(session('success'))
        <div class="flash ok">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="flash err">{{ session('error') }}</div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="flash err">
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
    Garuda-Hub - startup lelang proyek IT mahasiswa (Laravel Refactored MVC Edition).
</footer>
</body>
</html>
