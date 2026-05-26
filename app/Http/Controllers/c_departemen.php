<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class c_departemen extends Controller
{
    public function create()
    {
        return view('admin.departemen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_departemen' => 'required|string|max:255|unique:departemens,nama_departemen',
            'deskripsi' => 'nullable|string',
        ]);

        Departemen::create([
            'nama_departemen' => $request->nama_departemen,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kelola-akun.create')->with('success', 'Departemen baru berhasil ditambahkan.');
    }
}
