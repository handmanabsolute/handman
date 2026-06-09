<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class c_departemen extends Controller
{
    public function create()
    {
        return view('admin.departemen.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_departemen' => 'required|string|max:255|unique:departemens,nama_departemen',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_departemen.required' => 'Nama departemen wajib diisi.',
            'nama_departemen.unique' => 'Nama departemen sudah ada di dalam sistem.',
        ]);

        if ($request->hasHeader('X-Validate-Only')) {
            if ($validator->fails()) {
                return response()->json([
                    'valid' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            return response()->json(['valid' => true], 200);
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Departemen::create([
            'nama_departemen' => $request->nama_departemen,
            'deskripsi_departemen' => $request->deskripsi,
        ]);

        session()->flash('success', 'Departemen baru berhasil ditambahkan.');

        return response()->json([
            'redirect' => route('kelola-akun.create')
        ], 200);
    }

    public function edit(string $id)
    {
        $departemen = Departemen::findOrFail($id);
        return view('admin.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, string $id)
    {
        $departemen = Departemen::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_departemen' => 'required|string|max:255|unique:departemens,nama_departemen,' . $id,
            'deskripsi' => 'nullable|string',
        ], [
            'nama_departemen.required' => 'Nama departemen wajib diisi.',
            'nama_departemen.unique' => 'Nama departemen sudah ada di dalam sistem.',
        ]);

        if ($request->hasHeader('X-Validate-Only')) {
            if ($validator->fails()) {
                return response()->json([
                    'valid' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            return response()->json(['valid' => true], 200);
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $departemen->update([
            'nama_departemen' => $request->nama_departemen,
            'deskripsi_departemen' => $request->deskripsi,
        ]);

        session()->flash('success', 'Departemen berhasil diperbarui.');

        return response()->json([
            'redirect' => route('kelola-akun.create')
        ], 200);
    }

    public function destroy(string $id)
    {
        $departemen = Departemen::findOrFail($id);
        $departemen->delete();

        return redirect()->back()->with('success', 'Departemen berhasil dihapus.');
    }
}
