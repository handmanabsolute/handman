<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class c_notification extends Controller
{

    public function read(string $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        switch ($notification->type) {
            case 'laporan_masuk':
                return redirect()->route('admin.laporan.index');

            case 'tugas_dikumpulkan':
                return redirect()->route('tugas.show', $notification->related_id);

            case 'revisi_tugas':
                return redirect()->route('staff.tugas.show', $notification->related_id);

            case 'tugas_baru':
            case 'tugas_selesai':
                if (Auth::user()->nama_role === 'manager') {
                    return redirect()->route('tugas.show', $notification->related_id);
                }
                return redirect()->route('staff.tugas.show', $notification->related_id);

            case 'deadline_mendekati':
                if (Auth::user()->nama_role === 'manager') {
                    return redirect()->route('tugas.show', $notification->related_id);
                }
                return redirect()->route('staff.tugas.show', $notification->related_id);

            default:
                return redirect('/');
        }
    }

    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function destroy(Request $request, string $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus.'
            ]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        Notification::where('user_id', Auth::id())->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil dihapus.'
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
