@extends('layouts.app')

@section('title', 'Profil Petugas')

@section('content')
<h1>Profil Petugas</h1>

@if (session('success'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="margin-bottom: 20px;">
    <h3>Informasi Akun</h3>
    <p><strong>Nama:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
</div>

<hr>

<h2>Ganti Password</h2>
<form action="{{ route('profile.update-password') }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="current_password">Password Lama</label>
        <input type="password" name="current_password" id="current_password">
        @error('current_password')
            <div class="error" style="color: red;">{{ $message }}</div>
        @enderror
    </div>

    <div style="margin-top: 10px;">
        <label for="password">Password Baru</label>
        <input type="password" name="password" id="password">
        @error('password')
            <div class="error" style="color: red;">{{ $message }}</div>
        @enderror
    </div>

    <div style="margin-top: 10px;">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
    </div>

    <button type="submit" class="btn" style="margin-top: 15px;">Simpan Password Baru</button>
</form>
@endsection