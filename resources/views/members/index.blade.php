@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')

<h1>Daftar Anggota</h1>

<div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('members.create') }}" class="btn">+ Tambah Anggota</a>

    <form action="{{ route('members.index') }}" method="GET" style="display: flex; gap: 8px;">
        <input type="text" name="search" placeholder="Cari nama anggota..." value="{{ request('search') }}" style="padding: 6px 10px; min-width: 200px;">
        <button type="submit" class="btn">Cari</button>
    </form>
</div>

@if(session('success'))
    <div style="color: green; margin-bottom: 12px;">{{ session('success') }}</div>
@endif

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
    <thead>
        <tr style="background: #f3f4f6;">
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr>
                <td>{{ $member->nim }}</td>
                <td>{{ $member->nama }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->nomor_telepon }}</td>
                <td>{{ ucfirst($member->status) }}</td>
                <td>
                    <a href="{{ route('members.show', $member->id) }}">Detail</a> |
                    <a href="{{ route('members.edit', $member->id) }}">Edit</a> |
                    <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Belum ada data anggota.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 16px;">
    {{ $members->appends(request()->query())->links() }}
</div>

@endsection