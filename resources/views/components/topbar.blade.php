<header class="sticky top-0 z-30 h-16 bg-gray-200 border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 shadow-xs">
    <div class="flex items-center">
        <button id="open-sidebar" class="text-gray-500 hover:text-[#3B28CC] focus:outline-none md:hidden p-2 rounded-xl hover:bg-purple-50 transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <div class="flex items-center space-x-4">
        <div class="relative">
            <button id="user-menu-btn" class="flex items-center space-x-2 bg-gray-100 focus:outline-none rounded-full p-1 hover:bg-gray-50 transition-colors group cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->email) }}&background=3B28CC&color=fff" alt="Avatar" class="w-8 h-8 rounded-full object-cover shadow-xs">
                <span class="text-sm font-medium text-gray-700 pr-2">Profil</span>
            </button>

            <div id="user-menu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1 opacity-0 scale-95 transition-all duration-100 origin-top-right">
                <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-[10px] text-[#3B28CC] font-bold uppercase tracking-wider mt-0.5">{{ Auth::user()->nama_role }}</p>
                </div>
                <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-[#3B28CC] transition-colors">
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
