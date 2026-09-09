<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private array $statuses = [
        ['id' => 1, 'nama_status' => 'Aktif'],
        ['id' => 2, 'nama_status' => 'Tidak Aktif'],
    ];

    private array $members = [
        ['id' => 1, 'nama' => 'Mas Blor', 'nim/nrp' => '123456789', 'email' => 'john.doe@example.com', 'nomor_telepon' => '081234567890', 'alamat' => 'Jl. Merdeka No. 1', 'status' => 'active'],
        ['id' => 2, 'nama' => 'Joshua', 'nim/nrp' => '987654321', 'email' => 'jane.smith@example.com', 'nomor_telepon' => '081234567891', 'alamat' => 'Jl. Sudirman No. 2', 'status' => 'active'],
        ['id' => 3, 'nama' => 'Mua', 'nim/nrp' => '456789123', 'email' => 'bob.johnson@example.com', 'nomor_telepon' => '081234567892', 'alamat' => 'Jl. Diponegoro No. 3', 'status' => 'inactive'],
    ];

    public function index()
    {
        $members = $this->members;

        return view('members.index', compact('members'));
    }

    public function create()
    {
        $statuses = $this->statuses;

        return view('members.create', compact('statuses'));
    }

    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        return redirect()->route('members.index')
            ->with('success', "Member \"{$validated['nama']}\" berhasil ditambahkan (data dummy, belum tersimpan ke database).");
    }

    public function show(string $id)
    {
        $member = collect($this->members)->firstWhere('id', (int) $id);

        abort_if(! $member, 404);

        return view('members.show', compact('member'));
    }

    public function edit(string $id)
    {
        $member = collect($this->members)->firstWhere('id', (int) $id);

        abort_if(! $member, 404);

        $statuses = $this->statuses;

        return view('members.edit', compact('member', 'statuses'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'nim/nrp' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:200',
            'status_id' => 'required|integer|min:0',
        ]);

        return redirect()->route('members.index')
            ->with('success', "Member \"{$validated['nama']}\" berhasil diperbarui (data dummy, belum tersimpan ke database).");
    }

    public function destroy(string $id)
    {
        return redirect()->route('members.index')
            ->with('success', "Member dengan id {$id} berhasil dihapus (data dummy, belum tersimpan ke database).");
    }
}