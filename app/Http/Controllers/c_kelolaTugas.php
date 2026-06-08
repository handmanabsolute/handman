<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class c_kelolaTugas extends Controller
{
    public function index()
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->latest()->get();

        if (auth()->user()->nama_role === 'staff') {
            return view('staff.tugas.index', compact('tugas'));
        }

        return view('manager.tugas.index', compact('tugas'));
    }

    public function create()
    {
        return view('manager.tugas.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_tugas' => $request->filled(['tanggal_tugas_date', 'tanggal_tugas_time'])
                ? $request->tanggal_tugas_date . ' ' . $request->tanggal_tugas_time
                : null,
            'deadline_tugas' => $request->filled(['deadline_tugas_date', 'deadline_tugas_time'])
                ? $request->deadline_tugas_date . ' ' . $request->deadline_tugas_time
                : null,
        ]);

        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'kategoritugas' => 'required|string|max:50',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ]);

        $dataTugas = $request->only([
            'nama_tugas', 'deskripsi', 'tanggal_tugas', 'deadline_tugas', 'prioritas', 'kategoritugas'
        ]);

        $dataTugas['departemen_id'] = auth()->user()->departemen_id;
        $dataTugas['status_tugas'] = 'Belum Dikerjakan';

        $tugas = Tugas::create($dataTugas);

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        if ($hasGambar || $hasDokumen || $request->filled('link_tugas')) {
            $tugas->lampirans()->create([
                'gambar_file' => $hasGambar ? $request->file('gambar_file')->store('tugas/gambar', 'public') : null,
                'nama_file' => $hasDokumen ? $request->file('nama_file')->store('tugas/dokumen', 'public') : null,
                'link_tugas' => $request->link_tugas
            ]);
        }

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->with('lampirans')->findOrFail($id);

        if (auth()->user()->nama_role === 'staff') {
            return view('staff.tugas.show', compact('tugas'));
        }

        return view('manager.tugas.show', compact('tugas'));
    }

    public function edit(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);
        return view('manager.tugas.edit', compact('tugas'));
    }

    public function update(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'status_tugas' => 'required|string|max:50',
            'kategoritugas' => 'required|string|max:50',
            'catatan_revisi' => 'nullable|string',
        ]);

        $tugas->update($request->all());

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function reviewTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'action' => 'required|in:setujui,revisi',
            'catatan_revisi' => 'required_if:action,revisi|nullable|string',
        ]);

        if ($request->action === 'setujui') {
            $tugas->update([
                'status_tugas' => 'Selesai',
                'catatan_revisi' => null
            ]);
            return redirect()->back()->with('success', 'Tugas berhasil disetujui.');
        }

        if ($request->action === 'revisi') {
            $tugas->update([
                'status_tugas' => 'Revisi',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            return redirect()->back()->with('success', 'Tugas dikembalikan untuk revisi.');
        }
    }

    public function destroy(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->with('lampirans')->findOrFail($id);

        foreach ($tugas->lampirans as $lampiran) {
            if ($lampiran->gambar_file) {
                Storage::disk('public')->delete($lampiran->gambar_file);
            }
            if ($lampiran->nama_file) {
                Storage::disk('public')->delete($lampiran->nama_file);
            }
        }

        $tugas->delete();

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function submitTugasForm(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);
        return view('staff.tugas.submit', compact('tugas'));
    }

    public function submitTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->validate([
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ]);

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        if ($hasGambar || $hasDokumen || $request->filled('link_tugas')) {
            $tugas->lampirans()->create([
                'gambar_file' => $hasGambar ? $request->file('gambar_file')->store('pengumpulan/gambar', 'public') : null,
                'nama_file' => $hasDokumen ? $request->file('nama_file')->store('pengumpulan/dokumen', 'public') : null,
                'link_tugas' => $request->link_tugas
            ]);
        }

        $tugas->update([
            'status_tugas' => 'Menunggu Persetujuan'
        ]);

        return redirect()->route('staff.tugas.index')->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
