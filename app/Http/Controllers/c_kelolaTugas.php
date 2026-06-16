<?php

namespace App\Http\Controllers;

use App\Models\GrupKerja;
use App\Models\Notification;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class c_kelolaTugas extends Controller
{
    public function index(Request $request)
    {
        $departemenId = auth()->user()->departemen_id;

        if (auth()->user()->nama_role === 'staff') {
            $userId = auth()->id();

            $myGrupIds = GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->pluck('id');

            $query = Tugas::where('departemen_id', $departemenId)
                ->where(function ($query) use ($userId, $myGrupIds) {
                    $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                        $q->where('user_id', $userId)
                            ->orWhereIn('grup_kerja_id', $myGrupIds);
                    })
                        ->orWhereDoesntHave('detailTugas');
                });

            if ($request->filled('status')) {
                $query->where('status_tugas', $request->status);
            }

            if ($request->filled('prioritas')) {
                $query->where('prioritas', $request->prioritas);
            }

            if ($request->filled('kategori')) {
                $query->where('kategoritugas', $request->kategori);
            }

            $tugas = $query->orderBy('tanggal_tugas', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('staff.tugas.index', compact('tugas'));
        }

        $query = Tugas::where('departemen_id', $departemenId);

        if ($request->filled('status')) {
            $query->where('status_tugas', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategoritugas', $request->kategori);
        }

        $tugas = $query->orderBy('tanggal_tugas', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manager.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $departemenId = auth()->user()->departemen_id;

        $staffs = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        $grups = GrupKerja::where('departemen_id', $departemenId)
            ->orderBy('nama_grup')
            ->get();

        return view('manager.tugas.create', compact('staffs', 'grups'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_tugas' => $request->filled(['tanggal_tugas_date', 'tanggal_tugas_time'])
                ? $request->tanggal_tugas_date.' '.$request->tanggal_tugas_time
                : null,
            'deadline_tugas' => $request->filled(['deadline_tugas_date', 'deadline_tugas_time'])
                ? $request->deadline_tugas_date.' '.$request->deadline_tugas_time
                : null,
        ]);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'kategoritugas' => 'required|string|max:50',
            'user_id' => 'required_if:kategoritugas,Individu|nullable|exists:users,id',
            'grup_kerja_id' => 'required_if:kategoritugas,Kelompok|nullable|exists:grup_kerjas,id',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ], [
            'nama_tugas.required' => 'Nama tugas wajib diisi.',
            'nama_tugas.max' => 'Nama tugas maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi tugas wajib diisi.',
            'tanggal_tugas.required' => 'Tanggal tugas wajib diisi.',
            'tanggal_tugas.date' => 'Format tanggal tugas tidak valid.',
            'deadline_tugas.required' => 'Deadline tugas wajib diisi.',
            'deadline_tugas.date' => 'Format deadline tugas tidak valid.',
            'deadline_tugas.after_or_equal' => 'Deadline tugas harus setelah atau sama dengan tanggal tugas.',
            'prioritas.required' => 'Prioritas tugas wajib dipilih.',
            'prioritas.max' => 'Prioritas tugas maksimal 200 karakter.',
            'kategoritugas.required' => 'Kategori tugas wajib dipilih.',
            'kategoritugas.max' => 'Kategori tugas maksimal 50 karakter.',
            'user_id.required_if' => 'Staff penanggung jawab wajib dipilih jika kategori tugas adalah Individu.',
            'grup_kerja_id.required_if' => 'Grup kerja penanggung jawab wajib dipilih jika kategori tugas adalah Kelompok.',
            'gambar_file.image' => 'File harus berupa gambar.',
            'gambar_file.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar_file.max' => 'Ukuran gambar maksimal 10MB.',
            'nama_file.file' => 'File harus berupa dokumen.',
            'nama_file.mimes' => 'Format dokumen harus pdf, doc, docx, xls, xlsx, ppt, pptx, atau txt.',
            'nama_file.max' => 'Ukuran dokumen maksimal 20MB.',
            'link_tugas.url' => 'Format link tugas tidak valid.',
        ]);

        if ($request->hasHeader('X-Validate-Only')) {
            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('tanggal_tugas')) {
                    $errors->add('tanggal_tugas_date', $errors->first('tanggal_tugas'));
                    $errors->add('tanggal_tugas_time', $errors->first('tanggal_tugas'));
                }
                if ($errors->has('deadline_tugas')) {
                    $errors->add('deadline_tugas_date', $errors->first('deadline_tugas'));
                    $errors->add('deadline_tugas_time', $errors->first('deadline_tugas'));
                }
                return response()->json([
                    'valid' => false,
                    'errors' => $errors
                ], 200);
            }
            return response()->json(['valid' => true], 200);
        }

        $validator->validate();

        $dataTugas = $request->only([
            'nama_tugas', 'deskripsi', 'tanggal_tugas', 'deadline_tugas', 'prioritas', 'kategoritugas',
        ]);

        $dataTugas['departemen_id'] = auth()->user()->departemen_id;
        $dataTugas['status_tugas'] = 'Belum Dikerjakan';

        $tugas = Tugas::create($dataTugas);

        $tugas->detailTugas()->create([
            'user_id' => $request->kategoritugas === 'Individu' ? $request->user_id : null,
            'grup_kerja_id' => $request->kategoritugas === 'Kelompok' ? $request->grup_kerja_id : null,
        ]);

        if ($tugas->kategoritugas === 'Individu' && $request->user_id) {
            \App\Models\Notification::create([
                'user_id' => $request->user_id,
                'title' => 'Tugas Baru Masuk',
                'message' => 'Anda mendapatkan tugas baru: "' . $tugas->nama_tugas . '".',
                'type' => 'tugas_baru',
                'related_id' => $tugas->id,
            ]);
        } elseif ($tugas->kategoritugas === 'Kelompok' && $request->grup_kerja_id) {
            $anggotaIds = \DB::table('detail_grups')
                ->where('grup_kerja_id', $request->grup_kerja_id)
                ->pluck('user_id');
            foreach ($anggotaIds as $uid) {
                \App\Models\Notification::create([
                    'user_id' => $uid,
                    'title' => 'Tugas Kelompok Baru',
                    'message' => 'Grup Anda mendapatkan tugas kelompok baru: "' . $tugas->nama_tugas . '".',
                    'type' => 'tugas_baru',
                    'related_id' => $tugas->id,
                ]);
            }
        }

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        if ($hasGambar || $hasDokumen || $request->filled('link_tugas')) {
            $tugas->lampirans()->create([
                'gambar_file' => $hasGambar ? $request->file('gambar_file')->store('tugas/gambar', 'public') : null,
                'nama_file' => $hasDokumen ? $request->file('nama_file')->store('tugas/dokumen', 'public') : null,
                'link_tugas' => $request->link_tugas,
            ]);
        }

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'created',
                'Tugas Baru Masuk',
                'Tugas baru "' . $tugas->nama_tugas . '" telah diterbitkan oleh Manager.',
                null,
                $tugas->id
            ));
        }
        catch (\Throwable $e) {

        }

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)
            ->with(['lampirans', 'detailTugas.user', 'detailTugas.grupKerja'])
            ->findOrFail($id);

        if (auth()->user()->nama_role === 'staff') {
            return view('staff.tugas.show', compact('tugas'));
        }

        return view('manager.tugas.show', compact('tugas'));
    }

    public function edit(string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->with('detailTugas')->findOrFail($id);

        $staffs = User::where('departemen_id', $departemenId)
            ->where('nama_role', 'staff')
            ->where('is_active', 1)
            ->orderBy('nama_lengkap')
            ->get();

        $grups = GrupKerja::where('departemen_id', $departemenId)
            ->orderBy('nama_grup')
            ->get();

        return view('manager.tugas.edit', compact('tugas', 'staffs', 'grups'));
    }

    public function update(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $request->merge([
            'tanggal_tugas' => $request->filled(['tanggal_tugas_date', 'tanggal_tugas_time'])
                ? $request->tanggal_tugas_date.' '.$request->tanggal_tugas_time
                : null,
            'deadline_tugas' => $request->filled(['deadline_tugas_date', 'deadline_tugas_time'])
                ? $request->deadline_tugas_date.' '.$request->deadline_tugas_time
                : null,
        ]);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline_tugas' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|string|max:200',
            'status_tugas' => 'required|string|max:50',
            'kategoritugas' => 'required|string|max:50',
            'user_id' => 'required_if:kategoritugas,Individu|nullable|exists:users,id',
            'grup_kerja_id' => 'required_if:kategoritugas,Kelompok|nullable|exists:grup_kerjas,id',
            'catatan_revisi' => 'nullable|string',
        ], [
            'nama_tugas.required' => 'Nama tugas wajib diisi.',
            'nama_tugas.max' => 'Nama tugas maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi tugas wajib diisi.',
            'tanggal_tugas.required' => 'Tanggal tugas wajib diisi.',
            'tanggal_tugas.date' => 'Format tanggal tugas tidak valid.',
            'deadline_tugas.required' => 'Deadline tugas wajib diisi.',
            'deadline_tugas.date' => 'Format deadline tugas tidak valid.',
            'deadline_tugas.after_or_equal' => 'Deadline tugas harus setelah atau sama dengan tanggal tugas.',
            'prioritas.required' => 'Prioritas tugas wajib dipilih.',
            'prioritas.max' => 'Prioritas tugas maksimal 200 karakter.',
            'status_tugas.required' => 'Status tugas wajib dipilih.',
            'status_tugas.max' => 'Status tugas maksimal 50 karakter.',
            'kategoritugas.required' => 'Kategori tugas wajib dipilih.',
            'kategoritugas.max' => 'Kategori tugas maksimal 50 karakter.',
            'user_id.required_if' => 'Staff penanggung jawab wajib dipilih jika kategori tugas adalah Individu.',
            'grup_kerja_id.required_if' => 'Grup kerja penanggung jawab wajib dipilih jika kategori tugas adalah Kelompok.',
            'catatan_revisi.string' => 'Catatan revisi harus berupa teks.',
        ]);

        if ($request->hasHeader('X-Validate-Only')) {
            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('tanggal_tugas')) {
                    $errors->add('tanggal_tugas_date', $errors->first('tanggal_tugas'));
                    $errors->add('tanggal_tugas_time', $errors->first('tanggal_tugas'));
                }
                if ($errors->has('deadline_tugas')) {
                    $errors->add('deadline_tugas_date', $errors->first('deadline_tugas'));
                    $errors->add('deadline_tugas_time', $errors->first('deadline_tugas'));
                }
                return response()->json([
                    'valid' => false,
                    'errors' => $errors
                ], 200);
            }
            return response()->json(['valid' => true], 200);
        }

        $validator->validate();

        $tugas->update($request->only([
            'nama_tugas', 'deskripsi', 'tanggal_tugas', 'deadline_tugas', 'prioritas', 'status_tugas', 'kategoritugas', 'catatan_revisi',
        ]));

        $tugas->detailTugas()->updateOrCreate(
            ['tugas_id' => $tugas->id],
            [
                'user_id' => $request->kategoritugas === 'Individu' ? $request->user_id : null,
                'grup_kerja_id' => $request->kategoritugas === 'Kelompok' ? $request->grup_kerja_id : null,
            ]
        );

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'updated',
                'Tugas Diperbarui',
                'Tugas "' . $tugas->nama_tugas . '" telah diperbarui oleh Manager.',
                null,
                $tugas->id
            ));
        } catch (\Throwable $e) {
        }

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function reviewTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'action' => 'required|in:setujui,revisi',
            'catatan_revisi' => 'required_if:action,revisi|nullable|string',
        ], [
            'action.required' => 'Aksi wajib dipilih.',
            'action.in' => 'Aksi yang dipilih tidak valid.',
            'catatan_revisi.required_if' => 'Catatan revisi wajib diisi jika tugas dikembalikan untuk revisi.',
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

        if ($request->action === 'setujui') {
            $tugas->update([
                'status_tugas' => 'Selesai',
                'catatan_revisi' => null,
            ]);

            if ($tugas->kategoritugas === 'Individu' && $tugas->detailTugas && $tugas->detailTugas->user_id) {
                Notification::create([
                    'user_id' => $tugas->detailTugas->user_id,
                    'title' => 'Tugas Selesai',
                    'message' => 'Tugas "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                    'type' => 'tugas_baru',
                    'related_id' => $tugas->id,
                ]);
            } elseif ($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grup_kerja_id) {
                $anggotaIds = \DB::table('detail_grups')
                    ->where('grup_kerja_id', $tugas->detailTugas->grup_kerja_id)
                    ->pluck('user_id');
                foreach ($anggotaIds as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title' => 'Tugas Kelompok Selesai',
                        'message' => 'Tugas kelompok "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                        'type' => 'tugas_baru',
                        'related_id' => $tugas->id,
                    ]);
                }
            }

            try {
                event(new \App\Events\RealtimeTugasEvent(
                    $tugas->departemen_id,
                    'reviewed',
                    'Tugas Selesai',
                    'Tugas "' . $tugas->nama_tugas . '" telah disetujui oleh Manager.',
                    null,
                    $tugas->id
                ));
            } catch (\Throwable $e) {
            }

            return redirect()->back()->with('success', 'Tugas berhasil disetujui.');
        }

        if ($request->action === 'revisi') {
            $tugas->update([
                'status_tugas' => 'Revisi',
                'catatan_revisi' => $request->catatan_revisi,
            ]);

            if ($tugas->kategoritugas === 'Individu' && $tugas->detailTugas && $tugas->detailTugas->user_id) {
                Notification::create([
                    'user_id' => $tugas->detailTugas->user_id,
                    'title' => 'Tugas Direvisi',
                    'message' => 'Tugas "'.$tugas->nama_tugas.'" memerlukan revisi: '.$request->catatan_revisi,
                    'type' => 'revisi_tugas',
                    'related_id' => $tugas->id,
                ]);
            } elseif ($tugas->kategoritugas === 'Kelompok' && $tugas->detailTugas && $tugas->detailTugas->grup_kerja_id) {
                $anggotaIds = \DB::table('detail_grups')
                    ->where('grup_kerja_id', $tugas->detailTugas->grup_kerja_id)
                    ->pluck('user_id');
                foreach ($anggotaIds as $uid) {
                    Notification::create([
                        'user_id' => $uid,
                        'title' => 'Tugas Kelompok Direvisi',
                        'message' => 'Tugas kelompok "'.$tugas->nama_tugas.'" memerlukan revisi: '.$request->catatan_revisi,
                        'type' => 'revisi_tugas',
                        'related_id' => $tugas->id,
                    ]);
                }
            }

            try {
                event(new \App\Events\RealtimeTugasEvent(
                    $tugas->departemen_id,
                    'reviewed',
                    'Tugas Direvisi',
                    'Tugas "' . $tugas->nama_tugas . '" memerlukan revisi.',
                    null,
                    $tugas->id
                ));
            } catch (\Throwable $e) {
            }

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



    public function submitTugas(Request $request, string $id)
    {
        $departemenId = auth()->user()->departemen_id;
        $tugas = Tugas::where('departemen_id', $departemenId)->findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nama_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:20480',
            'link_tugas' => 'nullable|url',
        ], [
            'gambar_file.image' => 'File harus berupa gambar.',
            'gambar_file.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar_file.max' => 'Ukuran gambar maksimal 10MB.',
            'nama_file.file' => 'File harus berupa dokumen.',
            'nama_file.mimes' => 'Format dokumen harus pdf, doc, docx, xls, xlsx, ppt, pptx, atau txt.',
            'nama_file.max' => 'Ukuran dokumen maksimal 20MB.',
            'link_tugas.url' => 'Format link tugas tidak valid.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $hasGambar = $request->hasFile('gambar_file');
            $hasExistingGambar = $request->filled('existing_gambar');
            $hasDokumen = $request->hasFile('nama_file');
            $hasExistingDokumen = $request->filled('existing_dokumen');
            $hasLink = $request->filled('link_tugas');

            if (!$hasGambar && !$hasExistingGambar && !$hasDokumen && !$hasExistingDokumen && !$hasLink) {
                $validator->errors()->add('gambar_file', 'Minimal harus mengunggah satu berkas (gambar/dokumen) atau mengisi tautan link tugas.');
            }
        });

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

        $existingLampiran = $tugas->lampirans()->get()->filter(function($item) use ($tugas) {
            if ($item->gambar_file && str_contains($item->gambar_file, 'pengumpulan/')) {
                return true;
            }
            if ($item->nama_file && str_contains($item->nama_file, 'pengumpulan/')) {
                return true;
            }
            if (!$item->gambar_file && !$item->nama_file && $item->link_tugas) {
                return $item->created_at->diffInSeconds($tugas->created_at) >= 15;
            }
            return false;
        })->first();

        $hasGambar = $request->hasFile('gambar_file');
        $hasDokumen = $request->hasFile('nama_file');

        // Tentukan path gambar
        $gambarPath = null;
        if ($hasGambar) {
            if ($existingLampiran && $existingLampiran->gambar_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLampiran->gambar_file);
            }
            $gambarPath = $request->file('gambar_file')->store('pengumpulan/gambar', 'public');
        } elseif ($request->filled('existing_gambar')) {
            $gambarPath = $existingLampiran ? $existingLampiran->gambar_file : null;
        } else {
            if ($existingLampiran && $existingLampiran->gambar_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLampiran->gambar_file);
            }
        }

        // Tentukan path dokumen
        $dokumenPath = null;
        if ($hasDokumen) {
            if ($existingLampiran && $existingLampiran->nama_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLampiran->nama_file);
            }
            $dokumenPath = $request->file('nama_file')->store('pengumpulan/dokumen', 'public');
        } elseif ($request->filled('existing_dokumen')) {
            $dokumenPath = $existingLampiran ? $existingLampiran->nama_file : null;
        } else {
            if ($existingLampiran && $existingLampiran->nama_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingLampiran->nama_file);
            }
        }

        $linkTugas = $request->link_tugas;

        if ($gambarPath || $dokumenPath || $linkTugas) {
            if ($existingLampiran) {
                $existingLampiran->update([
                    'gambar_file' => $gambarPath,
                    'nama_file' => $dokumenPath,
                    'link_tugas' => $linkTugas,
                ]);
            } else {
                $tugas->lampirans()->create([
                    'gambar_file' => $gambarPath,
                    'nama_file' => $dokumenPath,
                    'link_tugas' => $linkTugas,
                ]);
            }
        } else {
            if ($existingLampiran) {
                $existingLampiran->delete();
            }
        }

        $tugas->update([
            'status_tugas' => 'Menunggu Persetujuan',
        ]);

        $manager = User::where('departemen_id', $tugas->departemen_id)
            ->where('nama_role', 'manager')
            ->first();
        if ($manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Tugas Dikumpulkan',
                'message' => 'Staff '.auth()->user()->nama_lengkap.' telah mengumpulkan tugas "'.$tugas->nama_tugas.'".',
                'type' => 'tugas_dikumpulkan',
                'related_id' => $tugas->id,
            ]);
        }

        try {
            event(new \App\Events\RealtimeTugasEvent(
                $tugas->departemen_id,
                'submitted',
                'Tugas Dikumpulkan',
                'Staff ' . auth()->user()->nama_lengkap . ' telah mengumpulkan tugas "' . $tugas->nama_tugas . '".',
                null,
                $tugas->id
            ));
        } catch (\Throwable $e) {
        }

        return redirect()->route('staff.tugas.show', $tugas->id)->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function exportPdf(Request $request)
    {
        $departemenId = auth()->user()->departemen_id;
        $departemenName = auth()->user()->departemen ? auth()->user()->departemen->nama_departemen : 'Departemen Anda';

        $query = Tugas::where('departemen_id', $departemenId)
            ->with('departemen')
            ->orderBy('tanggal_tugas', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status_tugas', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategoritugas', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_tugas', 'like', '%' . $request->search . '%');
        }

        $tugasList = $query->get();

        $totalTugas      = $tugasList->count();
        $tugasSelesai    = $tugasList->where('status_tugas', 'Selesai')->count();
        $tugasBerjalan   = $tugasList->whereIn('status_tugas', ['Belum Dikerjakan', 'Revisi'])->count();
        $tugasMenunggu   = $tugasList->where('status_tugas', 'Menunggu Persetujuan')->count();

        $currentEfficiency = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

        $cutoffDate = now()->subDays(14);
        $pastTugas = $tugasList->filter(function($t) use ($cutoffDate) {
            return \Carbon\Carbon::parse($t->tanggal_tugas)->lt($cutoffDate);
        });
        $recentTugas = $tugasList->filter(function($t) use ($cutoffDate) {
            return \Carbon\Carbon::parse($t->tanggal_tugas)->gte($cutoffDate);
        });

        $pastTotal = $pastTugas->count();
        $pastSelesai = $pastTugas->where('status_tugas', 'Selesai')->count();
        $pastEfficiency = $pastTotal > 0 ? round(($pastSelesai / $pastTotal) * 100) : null;

        $recentTotal = $recentTugas->count();
        $recentSelesai = $recentTugas->where('status_tugas', 'Selesai')->count();
        $recentEfficiency = $recentTotal > 0 ? round(($recentSelesai / $recentTotal) * 100) : null;

        if ($pastEfficiency !== null && $recentEfficiency !== null) {
            $change = $recentEfficiency - $pastEfficiency;
        } elseif ($recentEfficiency !== null) {
            $change = $recentEfficiency - 75;
        } else {
            $change = 0;
        }

        $filters = [];
        if ($request->filled('status')) {
            $filters[] = 'Status: ' . $request->status;
        }
        if ($request->filled('prioritas')) {
            $filters[] = 'Prioritas: ' . $request->prioritas;
        }
        if ($request->filled('kategori')) {
            $filters[] = 'Kategori: ' . $request->kategori;
        }
        if ($request->filled('search')) {
            $filters[] = 'Pencarian: "' . $request->search . '"';
        }
        $kategoriFilter = count($filters) > 0 ? implode(', ', $filters) : 'Semua';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tugas-pdf', compact(
            'tugasList',
            'departemenName',
            'kategoriFilter',
            'totalTugas',
            'tugasSelesai',
            'tugasBerjalan',
            'tugasMenunggu',
            'currentEfficiency',
            'change'
        ));
        return $pdf->download('Laporan_Kelola_Tugas_' . now()->format('YmdHis') . '.pdf');
    }
}
