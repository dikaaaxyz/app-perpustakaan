@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
    <h1>Daftar Peminjaman</h1>

    <p><a href="{{ route('loans.create') }}" class="btn">+ Tambah Peminjaman</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Anggota</th>
                <th>Petugas</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
                <tr>
                    <td>{{ $loan['id'] }}</td>
                    <td>{{ $loan['member']['nama'] }}</td>
                    <td>{{ $loan['user']['name'] }}</td>
                    <td>
                        @if($loan->status === 'dikembalikan')
                            <span class="badge badge-success">Dikembalikan</span>
                        @elseif($loan->status === 'dipinjam')
                            <span class="badge badge-warning">Dipinjam</span>
                        @elseif($loan->status === 'terlambat')
                            <span class="badge badge-danger">Terlambat</span>
                        @else
                            <span class="badge">{{ ucfirst($loan->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $loan['tanggal_pinjam'] }}</td>
                    <td>{{ $loan['tanggal_kembali'] }}</td>
                    <td>{{ ucfirst($loan['status']) }}</td>
                    <td>
                        <a href="{{ route('loans.show', $loan->id) }}">Detail</a>

                        @if($loan->status === 'dipinjam')
                            <form action="{{ route('loans.kembalikan', $loan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('Yakin ingin mengembalikan buku ini?')">Kembalikan</button>
                            </form>
                        @endif|
                        <a href="{{ route('loans.edit', $loan['id']) }}">Edit</a>
                        |
                        <form class="inline" action="{{ route('loans.destroy', $loan['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $loans->links() }}
@endsection