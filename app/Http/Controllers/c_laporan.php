<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class c_laporan extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->nama_role;

        if ($role === 'admin') {
            $query = Laporan::with(['user.departemen', 'responder']);

            if ($request->status === 'Selesai') {
                $query->where('status', 'Selesai');
            } else {
                $query->whereIn('status', ['Menunggu', 'Dibalas']);
            }

            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                      ->orWhere('isi', 'like', '%' . $request->search . '%')
                      ->orWhereHas('user', function($uq) use ($request) {
                          $uq->where('nama_lengkap', 'like', '%' . $request->search . '%');
                      });
                });
            }

            $laporans = $query->latest()->get();
            $belumDibalasCount = Laporan::whereIn('status', ['Menunggu', 'Dibalas'])->count();
            $selesaiCount = Laporan::where('status', 'Selesai')->count();
            return view('admin.laporan.index', compact('laporans', 'belumDibalasCount', 'selesaiCount'));
        }

        $query = Laporan::with('responder')->where('user_id', $user->id);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('isi', 'like', '%' . $request->search . '%');
            });
        }

        $laporans = $query->latest()->get();

        $view = $role === 'manager' ? 'manager.laporan.index' : 'staff.laporan.index';
        return view($view, compact('laporans'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
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

        $laporan = Laporan::create([
            'user_id' => auth()->id(),
            'judul'   => \Illuminate\Support\Str::limit($request->pertanyaan, 50),
            'isi'     => $request->pertanyaan,
            'status'  => 'Menunggu',
        ]);

        $admins = \App\Models\User::where('nama_role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id'    => $admin->id,
                'title'      => 'Laporan Masuk Baru',
                'message'    => 'Laporan baru "' . $laporan->judul . '" dari ' . auth()->user()->nama_lengkap,
                'type'       => 'laporan_masuk',
                'related_id' => $laporan->id,
            ]);
        }

        try {
            event(new \App\Events\RealtimeLaporanEvent(
                'created',
                'Laporan Masuk Baru',
                'Laporan baru "' . $laporan->judul . '" dari ' . auth()->user()->nama_lengkap,
                null,
                $laporan->id
            ));
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Laporan berhasil dikirim dan sedang menunggu tanggapan admin.');
    }

    public function respond(Request $request, string $id)
    {
        if (auth()->user()->nama_role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $laporan = Laporan::findOrFail($id);

        if ($laporan->tanggapan !== null) {
            return back()->withErrors(['error' => 'Laporan sudah ditanggapi dan tidak dapat diubah.']);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'tanggapan' => 'required|string',
        ], [
            'tanggapan.required' => 'Pesan tanggapan wajib diisi.',
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

        $laporan->update([
            'tanggapan'    => $request->tanggapan,
            'status'       => 'Selesai',
            'responded_by' => auth()->id(),
            'responded_at' => now(),
        ]);

        try {
            event(new \App\Events\RealtimeLaporanEvent(
                'responded',
                'Laporan Dibalas',
                'Laporan Anda "' . $laporan->judul . '" telah ditanggapi oleh Admin.',
                $laporan->user_id,
                $laporan->id
            ));
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Tanggapan berhasil dikirim dan status laporan diperbarui.');
    }

    public function update(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->tanggapan !== null) {
            return back()->withErrors(['error' => 'Pertanyaan sudah dijawab dan tidak dapat diedit.']);
        }

        $user = auth()->user();
        if ($laporan->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
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

        $laporan->update([
            'judul' => \Illuminate\Support\Str::limit($request->pertanyaan, 50),
            'isi'   => $request->pertanyaan,
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function show(string $id)
    {
        $laporan = Laporan::with(['user.departemen', 'responder'])->findOrFail($id);
        $user = auth()->user();

        if ($user->nama_role !== 'admin' && $laporan->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $role = $user->nama_role;
        $view = match ($role) {
            'admin' => 'admin.laporan.show',
            'manager' => 'manager.laporan.show',
            'staff' => 'staff.laporan.show',
        };

        return view($view, compact('laporan'));
    }

    public function destroy(string $id)
    {
        $laporan = Laporan::findOrFail($id);
        $user = auth()->user();

        // Only the owner (manager/staff) can delete their own report
        if ($laporan->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $judul = $laporan->judul;
        $laporan->delete();

        try {
            event(new \App\Events\RealtimeLaporanEvent(
                'deleted',
                'Laporan Dihapus',
                'Laporan "' . $judul . '" telah dihapus oleh ' . $user->nama_lengkap . '.',
                $user->id,
                $id
            ));
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
