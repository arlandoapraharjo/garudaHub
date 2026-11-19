@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="card">
    <h1>Admin: Manajemen Pengguna</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Peran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><b>{{ $user->name }}</b></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->isAdmin() ? 'win' : ($user->isKlien() ? 'open' : 'closed') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->id === auth()->id())
                            <span class="muted">(Akun Anda)</span>
                        @else
                            <form class="inline" method="POST" action="{{ route('admin.users.role', $user) }}">
                                @csrf
                                <select name="role">
                                    <option value="klien" {{ $user->role === 'klien' ? 'selected' : '' }}>Klien</option>
                                    <option value="developer" {{ $user->role === 'developer' ? 'selected' : '' }}>Developer</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="sm sec">Ubah</button>
                            </form>

                            <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }} beserta seluruh proyek dan penawarannya?');">
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

<div class="card">
    <h1>Admin: Manajemen Proyek</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Proyek</th>
                <th>Klien</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>
                        <b><a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a></b>
                        <div class="muted">{{ $project->kategori }} | Batas: {{ $project->batas_lelang->format('d M Y') }}</div>
                    </td>
                    <td>{{ $project->client->name }}</td>
                    <td>
                        <span class="badge {{ $project->status === 'terbuka' ? 'open' : 'closed' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </td>
                    <td>
                        <a class="btn sm sec" href="{{ route('projects.show', $project) }}">Lihat</a>
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
@endsection
