<?php

namespace App\Http\Controllers;

use App\Models\GrupKerja;
use App\Models\User;
use Illuminate\Http\Request;

class c_grupKerja extends Controller
{
    


    public function index()
    {
        return redirect()->route('staff-divisi.index', ['tab' => 'grup-kerja']);
    }

    


    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_grup'   => 'required|string|max:200',
            'deskripsi'   => 'nullable|string',
            'anggota_ids' => 'nullable|array',
            'anggota_ids.*' => 'exists:users,id',
        ], [
            'nama_grup.required'    => 'Nama grup wajib diisi.',
            'nama_grup.max'         => 'Nama grup maksimal 200 karakter.',
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

        $validator->validate();

        $departemenId = auth()->user()->departemen_id;

        $grup = GrupKerja::create([
            'nama_grup'    => $request->nama_grup,
            'deskripsi'    => $request->deskripsi,
            'departemen_id'=> $departemenId,
            'created_by'   => auth()->id(),
        ]);

        $memberCount = 0;
        if ($request->filled('anggota_ids')) {
            
            $validAnggota = User::where('departemen_id', $departemenId)
                ->where('nama_role', 'staff')
                ->whereIn('id', $request->anggota_ids)
                ->pluck('id');

            if ($validAnggota->isNotEmpty()) {
                $grup->anggota()->sync($validAnggota);
                $memberCount = $validAnggota->count();
            }
        }

        $msg = "Grup kerja \"{$grup->nama_grup}\" berhasil dibuat" . ($memberCount > 0 ? " dengan {$memberCount} anggota." : ".");

        return redirect()->route('staff-divisi.index', ['tab' => 'grup-kerja'])
            ->with('success', $msg);
    }

    


    public function show(string $id)
    {
        return redirect()->route('staff-divisi.index', ['tab' => 'grup-kerja']);
    }

    


    public function destroy(string $id)
    {
        $departemenId = auth()->user()->departemen_id;

        $grup = GrupKerja::where('departemen_id', $departemenId)->findOrFail($id);

        $namaGrup = $grup->nama_grup;
        $grup->anggota()->detach(); 
        $grup->delete();

        return redirect()->route('staff-divisi.index', ['tab' => 'grup-kerja'])
            ->with('success', "Grup kerja \"{$namaGrup}\" berhasil dibubarkan.");
    }
}
