<div id="sidebar" class="pt-17 -translate-x-full fixed inset-y-0 left-0 z-20 w-64 bg-[#3B28CC] text-blue-100 transform md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col justify-between border-r border-blue-700/50">
    <div>
        <div class="px-4 pt-6">
            <div class="flex items-center justify-between p-3 bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" class="w-10 h-10 rounded-full object-cover bg-white border-2 border-white/20">
                    <div class="truncate">
                        <h2 class="text-sm font-bold text-white truncate">Nama Perusahaan</h2>
                        <p class="text-xs text-blue-200 font-medium capitalize">{{ Auth::user()->nama_role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="mt-6 px-3 space-y-1">
            @if(Auth::user()->nama_role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('kelola-akun.index') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('kelola-akun.*') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center mr-3 {{ request()->routeIs('kelola-akun.*') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Kelola Pengguna
                </a>
            @endif

            @if(Auth::user()->nama_role === 'manager')
                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('manager.dashboard') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('manager.dashboard') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('tugas.index') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('tugas.*') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-regular fa-square-check w-5 text-center mr-3 {{ request()->routeIs('tugas.*') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Kelola Tugas
                </a>

                <a href="{{ route('jadwal.index') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('jadwal.*') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-regular fa-calendar w-5 text-center mr-3 {{ request()->routeIs('jadwal.*') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Kelola Jadwal
                </a>

                <a href="#"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff-divisi.*') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center mr-3 {{ request()->routeIs('staff-divisi.*') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Staff Divisi
                </a>
            @endif

            @if(Auth::user()->nama_role === 'staff')
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff.dashboard') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center mr-3 {{ request()->routeIs('staff.dashboard') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Staff Dashboard
                </a>

                <a href="#"
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('tugas-saya.*') ? 'text-white font-semibold bg-white/15 shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-regular fa-square-check w-5 text-center mr-3 {{ request()->routeIs('tugas-saya.*') ? 'text-white' : 'text-blue-200 group-hover:text-white' }}"></i>
                    Tugas Saya
                </a>
            @endif
        </nav>
    </div>
</div>

<div id="sidebar-backdrop" class="hidden fixed inset-0 z-10 bg-slate-900/40 backdrop-blur-xs md:hidden transition-opacity duration-300 opacity-0"></div>