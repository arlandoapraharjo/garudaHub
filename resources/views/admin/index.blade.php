@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div style="margin-bottom: 16px;">
    <h1 style="margin: 0;">Panel Admin</h1>
    <p class="muted" style="margin-top: 2px;">Kelola akun pengguna dan daftar proyek.</p>
</div>

{{-- Users Table --}}
<div class="panel" style="padding: 16px;">
    <h2>Pengguna ({{ $users->count() }})</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="text-mono muted">#{{ $user->id }}</td>
                        <td><b>{{ $user->name }}</b></td>
                        <td class="text-mono muted" style="font-size: 12px;">{{ $user->email }}</td>
                        <td>
                            <span class="badge">{{ $user->role }}</span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            @if($user->id === auth()->id())
                                <span class="muted" style="font-size: 11px;">(Akun Anda)</span>
                            @else
                                <form class="inline" method="POST" action="{{ route('admin.users.role', $user) }}">
                                    @csrf
                                    <select name="role">
                                        <option value="klien" {{ $user->role === 'klien' ? 'selected' : '' }}>klien</option>
                                        <option value="developer" {{ $user->role === 'developer' ? 'selected' : '' }}>developer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
                                    </select>
                                    <button type="submit" class="sm sec">Ubah</button>
                                </form>

                                <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Proyek dan bid terkait akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="sm danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Projects Table --}}
<div class="panel" style="padding: 16px;">
    <h2>Proyek ({{ $projects->count() }})</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ID</th>
                    <th>Judul Proyek</th>
                    <th>Klien</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Batas Lelang</th>
                    <th style="text-align: right;">Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td class="text-mono muted">#{{ $project->id }}</td>
                        <td>
                            <b><a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a></b>
                        </td>
                        <td class="muted">{{ $project->client->name }}</td>
                        <td class="muted">{{ $project->kategori }}</td>
                        <td class="text-mono muted" style="text-align: right; font-size: 12px;">
                            {{ $project->batas_lelang->format('d M Y') }}
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a class="btn btn-sm btn-sec" href="{{ route('projects.show', $project) }}">Lihat</a>
                            <form class="inline" method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Hapus proyek {{ $project->judul }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sm danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
