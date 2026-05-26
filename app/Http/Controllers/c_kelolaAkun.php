<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;

class c_kelolaAkun extends Controller
{
    public function index()
    {
        $users = User::with('departemen')->where('nama_role', '!=', 'admin')->get();
        return view('admin.kelola-akun.index', compact('users'));
    }

    public function create()
    {
        $departemens = Departemen::all();
        return view('admin.kelola-akun.create', compact('departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_telp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'status_pegawai' => 'required|string',
            'nama_role' => 'required|in:manager,staff',
            'departemen_id' => 'required|exists:departemens,id',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => $request->password,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status_pegawai' => $request->status_pegawai,
            'nama_role' => $request->nama_role,
            'departemen_id' => $request->departemen_id,
            'deskripsi_user' => $request->deskripsi_user,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('kelola-akun.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $user = User::with('departemen')->findOrFail($id);
        return view('admin.kelola-akun.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $departemens = Departemen::all();
        return view('admin.kelola-akun.edit', compact('user', 'departemens'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'no_telp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'status_pegawai' => 'required|string',
            'nama_role' => 'required|in:manager,staff',
            'departemen_id' => 'required|exists:departemens,id',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('kelola-akun.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('kelola-akun.index')->with('success', 'User berhasil dihapus.');
    }
}
