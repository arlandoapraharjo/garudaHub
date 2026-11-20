<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garuda-Hub') — Garuda-Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface-subtle: #f1f5f9;
            --border: #e2e8f0;
            --border-dark: #cbd5e1;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --primary: #0284c7; /* Freelance Blue */
            --primary-hover: #0369a1;
            --black: #0f172a;
            --radius: 8px;
            --font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
            font-size: 14px;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { color: var(--text); text-decoration: none; }
        a:hover { color: var(--primary); text-decoration: underline; }

        /* Ultra-Slim Header */
        header {
            border-bottom: 1px solid var(--border);
            background: #ffffff;
        }
        .header-inner {
            max-width: 960px;
            margin: auto;
            padding: 0 16px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            font-weight: 700;
            font-size: 14px;
            letter-spacing: -0.01em;
            color: var(--black);
        }
        .brand:hover { color: var(--black); text-decoration: none; }

        .nav-items {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .nav-link {
            font-size: 13px;
            color: var(--text-secondary);
        }
        .nav-link:hover {
            color: var(--primary);
            text-decoration: none;
        }
        .nav-link.active {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }
        .user-label {
            color: var(--text-muted);
        }
        .user-label strong {
            color: var(--text);
            font-weight: 600;
        }

        /* Layout Container */
        main {
            max-width: 960px;
            width: 100%;
            margin: 24px auto;
            padding: 0 16px;
            flex: 1;
        }

        /* Pure Utilitarian Box/Panel */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .panel.interactive:hover, .panel:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
        }

        /* Headings */
        h1 { font-size: 18px; font-weight: 700; letter-spacing: -0.025em; margin-bottom: 12px; }
        h2 { font-size: 15px; font-weight: 600; letter-spacing: -0.02em; margin-bottom: 10px; }
        .muted { color: var(--text-muted); font-size: 13px; }
        .text-mono {
            font-family: var(--font);
            font-variant-numeric: tabular-nums;
            font-feature-settings: 'tnum', 'cv02', 'cv03', 'cv04';
        }

        /* Status Badges - Monochrome & Subdued */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: var(--surface-subtle);
        }
        .badge.open {
            border-color: #bae6fd;
            background: #f0f9ff;
            color: #0369a1;
        }
        .badge.closed {
            border-color: var(--border);
            background: var(--surface-subtle);
            color: var(--text-muted);
        }
        .badge.win {
            border-color: #fde68a;
            background: #fffbeb;
            color: #92400e;
        }

        /* Buttons */
        .btn, button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: var(--radius);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            line-height: 1.4;
            transition: background-color 0.12s ease, border-color 0.12s ease;
        }
        .btn-primary, button[type="submit"]:not(.sec):not(.danger):not(.text-btn) {
            background: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover, button[type="submit"]:not(.sec):not(.danger):not(.text-btn):hover {
            background: var(--primary-hover);
            color: #ffffff;
            text-decoration: none;
        }
        .btn-sec, button.sec {
            background: #ffffff;
            color: var(--text);
            border-color: var(--border);
        }
        .btn-sec:hover, button.sec:hover {
            background: var(--surface-subtle);
            border-color: var(--border-dark);
            text-decoration: none;
        }
        .btn-danger, button.danger {
            background: #ffffff;
            color: #b91c1c;
            border-color: #fecaca;
        }
        .btn-danger:hover, button.danger:hover {
            background: #fef2f2;
            text-decoration: none;
        }
        .btn-sm, button.sm {
            padding: 3px 8px;
            font-size: 12px;
        }
        .text-btn {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }
        .text-btn:hover { color: var(--primary); text-decoration: underline; }

        /* Inputs */
        label {
            display: block;
            margin: 10px 0 4px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }
        input, select, textarea {
            width: 100%;
            padding: 6px 10px;
            font-size: 13px;
            font-family: var(--font);
            color: var(--text);
            background: #ffffff;
            border: 1px solid var(--border-dark);
            border-radius: var(--radius);
            transition: border-color 0.12s ease, box-shadow 0.12s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        textarea { min-height: 80px; resize: vertical; }
        .row { display: flex; gap: 10px; flex-wrap: wrap; }
        .row > * { flex: 1; min-width: 120px; }

        /* Tables */
        .table-wrap {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: #ffffff;
        }
        th, td {
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        th {
            background: var(--surface-subtle);
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-subtle); }
        tr.winner td { background: #fffdf5; }

        /* Alerts */
        .alert {
            padding: 8px 12px;
            border-radius: var(--radius);
            margin-bottom: 14px;
            font-size: 13px;
            border: 1px solid var(--border);
        }
        .alert.ok { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert.err { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .errors-list { padding-left: 18px; margin: 4px 0 0; }

        /* Inline Forms */
        form.inline { display: inline-flex; align-items: center; gap: 4px; margin: 0; }
        form.inline select { width: auto; padding: 2px 6px; font-size: 12px; }

        /* Footer */
        footer {
            border-top: 1px solid var(--border);
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: auto;
        }

        @media (max-width: 640px) {
            .header-inner { height: auto; padding: 10px 16px; flex-direction: column; align-items: flex-start; gap: 8px; }
        }
    </style>
</head>
<body>
<header>
    <div class="header-inner">
        <div style="display: flex; align-items: center; gap: 20px;">
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
