@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi profil dan unggah foto profil baru Anda.</p>
        </div>
        <div>
            <a href="{{ route('profil.show') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition-colors gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        
        <div class="h-2 bg-[#3B28CC]"></div>
        
        <form id="form-profil" action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" data-redirect="{{ route('profil.show') }}" novalidate>
            @csrf
            @method('PUT')

            
            <input type="file" id="foto_profil_input" name="foto_profil" accept="image/*" onchange="previewImage(event)" class="hidden">

            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                
                <div class="relative group shrink-0">
                    @if($user->foto_profil)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->foto_profil) }}" alt="Avatar" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-md border-4 border-indigo-50 transition-opacity group-hover:opacity-95">
                    @else
                        <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_lengkap ?? $user->email) }}&background=3B28CC&color=fff&size=128" alt="Avatar" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-md border-4 border-indigo-50 transition-opacity group-hover:opacity-95">
                    @endif
                    
                    <label for="foto_profil_input" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <span class="text-white text-xs font-semibold flex flex-col items-center gap-1">
                            <i class="fa-solid fa-camera text-base"></i>
                            Ubah Foto
                        </span>
                    </label>
                </div>

                
                <div class="flex-1 text-center md:text-left space-y-2">
                    <h3 class="text-lg font-bold text-gray-900">Foto Profil</h3>
                    <p class="text-xs text-gray-405 max-w-md">Pilih file foto berformat JPG, JPEG, PNG, atau WEBP dengan ukuran maksimum 2MB.</p>
                    <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-foto_profil"></p>
                </div>
            </div>

            
            <div class="border-t border-gray-100"></div>

            
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-[#3B28CC]"></i>
                        Informasi Akun
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @if($user->nama_role === 'admin')
                        
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                            <div class="relative w-full">
                                <input type="password" id="password" name="password" class="w-full px-4 py-2.5 mt-1.5 pr-11 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                                <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-password"></p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative w-full">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 mt-1.5 pr-11 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                                <button type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-password_confirmation"></p>
                        </div>
                    @else
                        
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <input type="text" value="{{ $user->nama_lengkap }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <input type="text" value="{{ ucfirst($user->nama_role) }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-500">Departemen</label>
                            <input type="text" value="{{ $user->departemen->nama_departemen ?? '-' }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-500">Status Pegawai</label>
                            <input type="text" value="{{ ucfirst($user->status_pegawai) }}" disabled class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-xl outline-none cursor-not-allowed">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-700">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                            <p class="text-xs text-red-600 error-msg hidden" id="error-no_telp"></p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-jenis_kelamin"></p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                            <p class="text-xs text-red-600 error-msg hidden" id="error-tanggal_lahir"></p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" rows="3" required class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-alamat"></p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Deskripsi Diri</label>
                            <textarea name="deskripsi_user" rows="3" class="w-full px-4 py-2.5 mt-1.5 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">{{ old('deskripsi_user', $user->deskripsi_user) }}</textarea>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-deskripsi_user"></p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Password Baru <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                            <div class="relative w-full">
                                <input type="password" id="password" name="password" class="w-full px-4 py-2.5 mt-1.5 pr-11 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                                <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-password"></p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative w-full">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 mt-1.5 pr-11 text-sm text-gray-800 bg-white border border-gray-200 rounded-xl focus:border-[#3B28CC] focus:ring-1 focus:ring-[#3B28CC] outline-none transition-all">
                                <button type="button" onclick="const p = document.getElementById('password_confirmation'); p.type = p.type === 'password' ? 'text' : 'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash');" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p class="text-xs text-red-600 error-msg hidden" id="error-password_confirmation"></p>
                        </div>
                    @endif
                </div>
            </div>

            
            <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                <button type="button" onclick="openModal('confirm-profil')" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-[#3B28CC] hover:bg-opacity-90 rounded-xl shadow-sm transition-colors cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<x-confirm-modal id="confirm-profil" title="Konfirmasi Perbarui Profil" message="Apakah Anda yakin ingin menyimpan perubahan data profil Anda?" action="executeGlobalAjaxSubmit('form-profil', 'confirm-profil')" method="POST" type="primary" />

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initRealTimeValidation('form-profil');
    });
</script>
@endsection
