<header class="sticky top-0 z-30 h-16 bg-[#3B28CC] border-b border-blue-700/50 flex items-center justify-between px-4 sm:px-6 shadow-sm">
    <div class="flex items-center space-x-3 md:w-1/4">
        <button id="open-sidebar" class="text-blue-100 hover:text-white focus:outline-none md:hidden p-2 rounded-xl hover:bg-white/10 transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <div class="text-2xl font-bold text-white min-w-max">
            Handman
        </div>
    </div>

    <div class="hidden md:flex flex-1 justify-center max-w-xl mx-auto px-4">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-blue-200"></i>
            </span>
            <input type="text" placeholder="Cari Tugas...." class="w-full pl-10 pr-4 py-2 bg-white/10 border border-white/10 rounded-xl text-sm text-white placeholder-blue-200/60 focus:outline-none focus:ring-2 focus:ring-white/20 focus:border-white/40 focus:bg-white/15 transition-all">
        </div>
    </div>

    <div class="flex items-center space-x-2 sm:space-x-4 justify-end md:w-1/4">
        <button class="md:hidden p-2 text-blue-100 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>

        <button class="relative p-2 text-blue-100 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <i class="fa-regular fa-bell text-lg"></i>
        </button>

        <div class="relative">
            <button id="user-menu-btn" class="flex items-center space-x-2 bg-white/10 border border-white/10 focus:outline-none rounded-full py-1.5 pl-4 pr-1.5 hover:bg-white/15 transition-colors group cursor-pointer">
                <span class="text-sm font-semibold text-white">Profil</span>
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover bg-white shadow-xs">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_lengkap ?? Auth::user()->email) }}&background=ffffff&color=3B28CC" alt="Avatar" class="w-8 h-8 rounded-full object-cover shadow-xs">
                @endif
            </button>

            <div id="user-menu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1 opacity-0 scale-95 transition-all duration-100 origin-top-right">
                <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider mt-0.5">{{ Auth::user()->nama_role }}</p>
                </div>
                <a href="{{ route('profil.show') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-user w-4 mr-2.5 text-gray-400"></i> Profil Saya
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium border-t border-gray-100 transition-colors cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket w-4 mr-2.5 text-red-500"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>