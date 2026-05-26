<div id="sidebar" class="-translate-x-full fixed inset-y-0 left-0 z-50 w-64 bg-[#3B28CC] text-white transform md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col justify-between shadow-xl">
    <div>
        <div class="h-16 flex items-center justify-between px-6 bg-[#2d1e9e] border-b border-[#4c3ae0]/30">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain brightness-0 invert">
                <span class="text-lg font-bold tracking-wider">HANDMAN</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-purple-200 hover:text-white focus:outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <nav class="mt-6 px-4 space-y-1">
            @if(Auth::user()->nama_role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-bars-progress w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Dashboard
                </a>
                <a href="{{ route('kelola-akun.index') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-users w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Kelola Pengguna
                </a>
            @endif

            @if(Auth::user()->nama_role === 'manager')
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-chart-pie w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Manager Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-file-invoice w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Laporan Analisis
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-list-check w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Persetujuan Tugas
                </a>
            @endif

            @if(Auth::user()->nama_role === 'staff')
                <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-house w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Staff Dashboard
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-purple-100 hover:bg-[#4c3ae0] hover:text-white rounded-xl transition-all duration-200 group">
                    <i class="fa-solid fa-list-check w-5 text-center mr-3 text-purple-300 group-hover:text-white transition-colors"></i>
                    Tugas Saya
                </a>
            @endif
        </nav>
    </div>
</div>

<div id="sidebar-backdrop" class="hidden fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs md:hidden transition-opacity duration-300 opacity-0"></div>
