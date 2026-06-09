<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class c_profil extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('profil.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $rules = [];
        $messages = [];

        if ($request->hasFile('foto_profil')) {
            $rules['foto_profil'] = 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $messages['foto_profil.image'] = 'File harus berupa gambar.';
            $messages['foto_profil.mimes'] = 'Format gambar harus jpeg, png, jpg, gif, atau webp.';
            $messages['foto_profil.max'] = 'Ukuran gambar maksimal adalah 2MB.';
        }

        if ($user->nama_role === 'admin') {
            $rules['password'] = ['nullable', 'string', Password::min(8)->letters()->mixedCase()->symbols()];
            $messages['password.string'] = 'Password harus berupa teks.';
            $messages['password.min'] = 'Password minimal harus terdiri dari 8 karakter.';
            $messages['password.mixed'] = 'Password harus mengandung kombinasi huruf besar dan huruf kecil.';
            $messages['password.symbols'] = 'Password harus mengandung simbol unik seperti !, @, #, dsb.';
        } else {

            $rules = array_merge($rules, [
                'no_telp' => 'required|numeric|digits_between:10,15|unique:users,no_telp,'.$user->id,
                'jenis_kelamin' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'deskripsi_user' => 'nullable|string',
                'password' => ['nullable', 'string', Password::min(8)->letters()->mixedCase()->symbols()],
            ]);

            $messages = array_merge($messages, [
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
                'no_telp.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 15 digit.',
                'no_telp.unique' => 'Nomor telepon sudah terdaftar di dalam sistem.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'alamat.required' => 'Alamat wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
                'password.string' => 'Password harus berupa teks.',
                'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
                'password.mixed' => 'Password harus mengandung kombinasi huruf besar dan huruf kecil.',
                'password.symbols' => 'Password harus mengandung simbol unik seperti !, @, #, dsb.',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->hasHeader('X-Validate-Only')) {
            if ($validator->fails()) {
                return response()->json([
                    'valid' => false,
                    'errors' => $validator->errors(),
                ], 200);
            }

            return response()->json(['valid' => true], 200);
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('foto_profil')) {

            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('profil', 'public');
            $user->foto_profil = $path;
            $user->save();
        }

        if ($user->nama_role === 'admin') {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        } else {

            $data = $request->only(['no_telp', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'deskripsi_user']);
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);
        }

        session()->flash('success', 'Profil berhasil diperbarui.');

        return response()->json([
            'redirect' => route('profil.show'),
        ], 200);
    }
}
