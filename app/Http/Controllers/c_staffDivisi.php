<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class c_staffDivisi extends Controller
{
    


    public function index(Request $request)
    {
        $departemenId = auth()->user()->departemen_id;

        $query = User::with('departemen')
            ->where('departemen_id', $departemenId)
            ->where('nama_role', 'staff');

        
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        
        if ($request->filled('status')) {
            $query->where('status_pegawai', $request->status);
        }

        $staffs = $query->orderBy('nama_lengkap')->get();

        $totalStaff  = $staffs->count();
        $staffAktif  = $staffs->where('is_active', 1)->count();
        $staffNonAktif = $staffs->where('is_active', 0)->count();

        
        $grups = \App\Models\GrupKerja::with(['anggota', 'creator'])
            ->where('departemen_id', $departemenId)
            ->latest()
            ->get();

        return view('manager.staff-divisi.index', compact(
            'staffs',
            'totalStaff',
            'staffAktif',
            'staffNonAktif',
            'grups'
        ));
    }

    


    public function show(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        
        $staff = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->findOrFail($id);

        $myGrups = $staff->grupKerjas;
        $myGrupIds = $myGrups->pluck('id');

        
        $grups = \App\Models\GrupKerja::where('departemen_id', $departemenId)
            ->whereNotIn('id', $myGrupIds)
            ->orderBy('nama_grup')
            ->get();

        
        $userId = $staff->id;
        $tugas = \App\Models\Tugas::where('departemen_id', $departemenId)
            ->where(function ($query) use ($userId, $myGrupIds) {
                $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                    $q->where('user_id', $userId)
                      ->orWhereIn('grup_kerja_id', $myGrupIds);
                })
                ->orWhereDoesntHave('detailTugas');
            })
            ->latest()
            ->get();

        return view('manager.staff-divisi.show', compact('staff', 'grups', 'myGrups', 'tugas'));
    }

    


    public function joinGroup(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $staff = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'grup_kerja_id' => 'required|exists:grup_kerjas,id',
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

        $grup = \App\Models\GrupKerja::where('departemen_id', $departemenId)->findOrFail($request->grup_kerja_id);

        
        if (!$grup->anggota()->where('users.id', $staff->id)->exists()) {
            $grup->anggota()->attach($staff->id);
        }

        return redirect()->back()->with('success', 'Staff berhasil dimasukkan ke dalam grup kerja.');
    }

    


    public function leaveGroup(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $staff = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'grup_kerja_id' => 'required|exists:grup_kerjas,id',
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

        $grup = \App\Models\GrupKerja::where('departemen_id', $departemenId)->findOrFail($request->grup_kerja_id);

        
        $grup->anggota()->detach($staff->id);

        return redirect()->back()->with('success', 'Staff berhasil dikeluarkan dari grup kerja.');
    }
}
