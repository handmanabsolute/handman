<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\CatatanJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class c_kelolaJadwal extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();

        $departemenId = auth()->user()->departemen_id;
        $userRole = auth()->user()->nama_role;

        if ($userRole === 'staff') {
            $userId = auth()->id();
            
            $myGrupIds = \App\Models\GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->pluck('id');

            $tasks = Tugas::where('departemen_id', $departemenId)
                ->whereBetween('deadline_tugas', [$startOfMonth . ' 00:00:00', $endOfMonth . ' 23:59:59'])
                ->where(function ($query) use ($userId, $myGrupIds) {
                    $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                        $q->where('user_id', $userId)
                          ->orWhereIn('grup_kerja_id', $myGrupIds);
                    })
                    ->orWhereDoesntHave('detailTugas');
                })
                ->get();
        } else {
            
            $tasks = Tugas::where('departemen_id', $departemenId)
                ->whereBetween('deadline_tugas', [$startOfMonth . ' 00:00:00', $endOfMonth . ' 23:59:59'])
                ->get();
        }

        
        $notes = CatatanJadwal::where('user_id', auth()->id())
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->with('tugas')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks,
                'notes' => $notes,
            ]);
        }

        if ($userRole === 'staff') {
            return view('staff.jadwal.index', compact('tasks', 'notes', 'month', 'year'));
        }

        return view('manager.jadwal.index', compact('tasks', 'notes', 'month', 'year'));
    }

    public function storeNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
            'tugas_id' => 'nullable|exists:tugas,id',
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
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $note = CatatanJadwal::create([
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'tugas_id' => $request->tugas_id,
            'user_id' => auth()->id(),
        ]);

        
        if ($note->tugas_id) {
            $note->load('tugas');
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan.',
            'note' => $note
        ], 200);
    }

    public function updateNote(Request $request, string $id)
    {
        $note = CatatanJadwal::where('user_id', auth()->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'catatan' => 'required|string',
            'tugas_id' => 'nullable|exists:tugas,id',
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
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $note->update([
            'catatan' => $request->catatan,
            'tugas_id' => $request->tugas_id,
        ]);

        if ($note->tugas_id) {
            $note->load('tugas');
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui.',
            'note' => $note
        ], 200);
    }

    public function destroyNote(string $id)
    {
        $note = CatatanJadwal::where('user_id', auth()->id())->findOrFail($id);
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus.'
        ], 200);
    }
}
