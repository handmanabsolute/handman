<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use App\Mail\RandomPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class c_kelolaAkun extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('departemen')->where('nama_role', '!=', 'admin')->get();

        if ($request->ajax()) {
            return view('admin.kelola-akun.index', compact('users'));
        }

        return view('admin.kelola-akun.index', compact('users'));
    }

    public function create()
    {
        $departemens = Departemen::all();
        return view('admin.kelola-akun.create', compact('departemens'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => 'required|numeric|digits_between:10,15|unique:users',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'status_pegawai' => 'required|string',
            'nama_role' => 'required|in:manager,staff',
            'deskripsi_user' => 'nullable|string',
            'departemen_id' => 'required|exists:departemens,id',
            'alamat' => 'required|string',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh mengandung huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'email.unique' => 'Alamat email sudah terdaftar di dalam sistem.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 15 digit.',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar di dalam sistem.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nama_role.required' => 'Peran pengguna wajib dipilih.',
            'status_pegawai.required' => 'Status pegawai wajib dipilih.',
            'departemen_id.required' => 'Departemen wajib dipilih.',
            'alamat.required' => 'Alamat wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
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

        $isActive = ($request->status_pegawai === 'Skorsing') ? 0 : 1;
        $randomPassword = Str::random(12);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($randomPassword),
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status_pegawai' => $request->status_pegawai,
            'is_active' => $isActive,
            'nama_role' => $request->nama_role,
            'departemen_id' => $request->departemen_id,
            'deskripsi_user' => $request->deskripsi_user,
            'alamat' => $request->alamat,
        ]);

        Mail::to($user->email)->send(new RandomPassword($user, $randomPassword));

        session()->flash('success', 'User berhasil ditambahkan.');

        return response()->json([
            'redirect' => route('kelola-akun.index')
        ], 200);
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

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => [ 'nullable', 'string', Password::min(8)->letters()->mixedCase()->symbols() ],
            'no_telp' => 'required|numeric|digits_between:10,15|unique:users,no_telp,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'status_pegawai' => 'required|string',
            'nama_role' => 'required|in:manager,staff',
            'deskripsi_user' => 'nullable|string',
            'departemen_id' => 'required|exists:departemens,id',
            'alamat' => 'required|string',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh mengandung huruf dan spasi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.string' => 'Alamat email harus berupa teks.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar di dalam sistem.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.mixed' => 'Password harus mengandung kombinasi huruf besar dan huruf kecil.',
            'password.symbols' => 'Password harus mengandung simbol unik seperti !, @, #, dsb.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 15 digit.',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar di dalam sistem.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nama_role.required' => 'Peran pengguna wajib dipilih.',
            'status_pegawai.required' => 'Status pegawai wajib dipilih.',
            'departemen_id.required' => 'Departemen wajib dipilih.',
            'alamat.required' => 'Alamat wajib diisi.'
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

        $data = $request->except('password');

        $data['is_active'] = ($request->status_pegawai === 'Skorsing') ? 0 : 1;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        session()->flash('success', 'User berhasil diperbarui.');

        return response()->json([
            'redirect' => route('kelola-akun.index')
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ], 200);
        }

        return redirect()->route('kelola-akun.index')->with('success', 'User berhasil dihapus.');
    }
}
