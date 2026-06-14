@php
    $user = Auth::user();
    $unreadCount = 0;
    $notifications = collect();

    if ($user) {
        $departemenId = $user->departemen_id;
        $userId = $user->id;

        
        if ($user->nama_role === 'manager' && $departemenId) {
            $nearingDeadlineTasks = \App\Models\Tugas::where('departemen_id', $departemenId)
                ->where('status_tugas', '!=', 'Selesai')
                ->whereBetween('deadline_tugas', [now(), now()->addHours(24)])
                ->get();

            foreach ($nearingDeadlineTasks as $task) {
                $exists = \App\Models\Notification::where('user_id', $userId)
                    ->where('type', 'deadline_mendekati')
                    ->where('related_id', $task->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\Notification::create([
                        'user_id'    => $userId,
                        'title'      => 'Tugas Mendekati Deadline',
                        'message'    => 'Tugas "' . $task->nama_tugas . '" mendekati batas waktu pengerjaan (' . \Carbon\Carbon::parse($task->deadline_tugas)->diffForHumans() . ').',
                        'type'       => 'deadline_mendekati',
                        'related_id' => $task->id,
                    ]);
                }
            }
        } elseif ($user->nama_role === 'staff' && $departemenId) {
            
            $myGrupIds = \App\Models\GrupKerja::whereHas('anggota', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            })->pluck('id');

            $nearingDeadlineTasks = \App\Models\Tugas::where('departemen_id', $departemenId)
                ->where('status_tugas', '!=', 'Selesai')
                ->whereBetween('deadline_tugas', [now(), now()->addHours(24)])
                ->where(function ($query) use ($userId, $myGrupIds) {
                    $query->whereHas('detailTugas', function ($q) use ($userId, $myGrupIds) {
                        $q->where('user_id', $userId)
                          ->orWhereIn('grup_kerja_id', $myGrupIds);
                    })
                    ->orWhereDoesntHave('detailTugas');
                })
                ->get();

            foreach ($nearingDeadlineTasks as $task) {
                $exists = \App\Models\Notification::where('user_id', $userId)
                    ->where('type', 'deadline_mendekati')
                    ->where('related_id', $task->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\Notification::create([
                        'user_id'    => $userId,
                        'title'      => 'Tugas Mendekati Deadline',
                        'message'    => 'Tugas "' . $task->nama_tugas . '" mendekati batas waktu pengerjaan (' . \Carbon\Carbon::parse($task->deadline_tugas)->diffForHumans() . ').',
                        'type'       => 'deadline_mendekati',
                        'related_id' => $task->id,
                    ]);
                }
            }
        }

        
        $notifications = \App\Models\Notification::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();
        
        $unreadCount = \App\Models\Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
@endphp
<header class="sticky top-0 z-30 h-16 bg-[#3B28CC] border-b border-blue-700/50 flex items-center justify-between px-4 sm:px-6 shadow-sm">
    <div class="flex items-center space-x-3 md:w-1/4">
        <button id="open-sidebar" class="text-blue-100 hover:text-white focus:outline-none md:hidden p-2 rounded-xl hover:bg-white/10 transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <div class="text-2xl font-bold text-white min-w-max">
            Handman
        </div>
    </div>

    <form id="navbar-search-form" action="" method="GET" class="hidden md:flex flex-1 justify-center max-w-xl mx-auto px-4 m-0">
        <div class="relative w-full flex items-center bg-white/10 border border-white/10 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-white/20 focus-within:border-white/40 focus-within:bg-white/15 transition-all">
            
            
            <span class="pl-3 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-blue-200"></i>
            </span>
            
            <input type="text" name="search" id="navbar-search-input" value="{{ request('search') }}" placeholder="Cari..." class="w-full pl-2 pr-4 py-2 bg-transparent border-0 text-sm text-white placeholder-blue-200/60 focus:outline-none transition-all">
            
            <div class="h-5 w-px bg-white/20 self-center"></div>
            
            <select id="navbar-search-category" class="bg-transparent border-none text-xs font-semibold text-blue-200 focus:outline-none cursor-pointer pl-3 py-2 pr-1 h-full select-none appearance-none" style="background-color: transparent; border: none; color: #93c5fd; font-weight: 600; outline: none; appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                @if(Auth::user()->nama_role === 'admin')
                    <option class="text-gray-800" value="{{ route('admin.tugas.index') }}" {{ request()->routeIs('admin.tugas.*') ? 'selected' : '' }}>Monitor Tugas</option>
                    <option class="text-gray-800" value="{{ route('kelola-akun.index') }}" {{ request()->routeIs('kelola-akun.*') ? 'selected' : '' }}>Kelola Pengguna</option>
                    <option class="text-gray-800" value="{{ route('admin.laporan.index') }}" {{ request()->routeIs('admin.laporan.*') ? 'selected' : '' }}>Laporan Masuk</option>
                @elseif(Auth::user()->nama_role === 'manager')
                    <option class="text-gray-800" value="{{ route('tugas.index') }}" {{ request()->routeIs('tugas.*') ? 'selected' : '' }}>Kelola Tugas</option>
                    <option class="text-gray-800" value="{{ route('staff-divisi.index') }}" {{ request()->routeIs('staff-divisi.*') ? 'selected' : '' }}>Staff Divisi</option>
                @elseif(Auth::user()->nama_role === 'staff')
                    <option class="text-gray-800" value="{{ route('staff.tugas.index') }}" {{ request()->routeIs('staff.tugas.*') ? 'selected' : '' }}>Tugas Saya</option>
                    <option class="text-gray-800" value="{{ route('staff.laporan.index') }}" {{ request()->routeIs('staff.laporan.*') ? 'selected' : '' }}>Laporan Masalah</option>
                @endif
            </select>
        </div>
    </form>

    <div class="flex items-center space-x-2 sm:space-x-4 justify-end md:w-1/4">
        <button class="md:hidden p-2 text-blue-100 hover:text-white hover:bg-white/10 rounded-full transition-colors">
            <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>

        <div class="relative">
            <button id="notif-menu-btn" class="relative p-2 text-blue-100 hover:text-white hover:bg-white/10 rounded-full transition-colors focus:outline-none cursor-pointer">
                <i class="fa-regular fa-bell text-lg"></i>
                @if($unreadCount > 0)
                    <span id="notif-badge" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center border border-[#3B28CC]">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            
            <div id="notif-menu" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden origin-top-right z-50 transition-all duration-100 opacity-0 scale-95">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-bell text-[#3B28CC]"></i> Notifikasi
                        @if($unreadCount > 0)
                            <span id="notif-header-badge" class="px-1.5 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold rounded-md">{{ $unreadCount }} baru</span>
                        @endif
                    </span>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] text-[#3B28CC] hover:underline font-bold cursor-pointer">
                                Tandai semua dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="max-h-[320px] overflow-y-auto divide-y divide-gray-50">
                    @forelse($notifications as $notif)
                        <div class="relative group hover:bg-slate-50 transition-colors {{ !$notif->is_read ? 'bg-indigo-50/20' : '' }}">
                            <a href="{{ route('notifications.read', $notif->id) }}" class="block p-4 pr-10">
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center 
                                        @if($notif->type === 'laporan_masuk') bg-amber-50 text-amber-600
                                        @elseif($notif->type === 'tugas_dikumpulkan') bg-blue-50 text-blue-600
                                        @elseif($notif->type === 'revisi_tugas') bg-rose-50 text-rose-600
                                        @elseif($notif->type === 'tugas_baru') bg-green-50 text-green-700
                                        @else bg-indigo-50 text-[#3B28CC] @endif">
                                        <i class="fa-solid 
                                            @if($notif->type === 'laporan_masuk') fa-circle-exclamation
                                            @elseif($notif->type === 'tugas_dikumpulkan') fa-file-arrow-up
                                            @elseif($notif->type === 'revisi_tugas') fa-rotate-left
                                            @elseif($notif->type === 'tugas_baru') fa-clipboard-list
                                            @else fa-clock @endif text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="text-xs font-bold text-gray-900 truncate">{{ $notif->title }}</p>
                                            <span class="text-[9px] text-gray-400 font-medium whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-normal">{{ $notif->message }}</p>
                                    </div>
                                    @if(!$notif->is_read)
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 self-center shrink-0"></div>
                                    @endif
                                </div>
                            </a>
                            <button type="button" onclick="deleteNotification(event, '{{ $notif->id }}', '{{ route('notifications.destroy', $notif->id) }}', '{{ csrf_token() }}', this)" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-50 sm:opacity-0 sm:group-hover:opacity-100 hover:!opacity-100 transition-opacity z-10 w-6 h-6 bg-gray-50 hover:bg-rose-50 hover:text-rose-600 rounded-lg flex items-center justify-center text-[11px] text-gray-400 cursor-pointer shadow-xs border border-gray-200/50 transition-colors" title="Hapus Notifikasi">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 flex flex-col items-center gap-2">
                            <i class="fa-regular fa-bell-slash text-xl text-gray-300"></i>
                            <p class="text-xs font-medium">Tidak ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('navbar-search-form');
        const categorySelect = document.getElementById('navbar-search-category');
        const input = document.getElementById('navbar-search-input');

        if (form && categorySelect) {
            const updateAction = () => {
                form.action = categorySelect.value;
            };

            // Set initially
            updateAction();

            // When select is changed
            categorySelect.addEventListener('change', updateAction);

            // Set action on submit just in case
            form.addEventListener('submit', function() {
                updateAction();
            });
        }
    });

    function deleteNotification(event, id, url, csrfToken, buttonEl) {
        event.stopPropagation();
        event.preventDefault();
        
        const itemEl = buttonEl.closest('.relative.group');
        if (!itemEl) return;
        
        itemEl.style.transition = 'all 0.25s ease';
        itemEl.style.opacity = '0';
        itemEl.style.transform = 'scale(0.95)';
        
        const isUnread = itemEl.querySelector('.bg-red-500') !== null;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => {
            if (response.ok) {
                setTimeout(() => {
                    itemEl.remove();
                    
                    const remaining = document.querySelectorAll('#notif-menu .relative.group');
                    if (remaining.length === 0) {
                        const listContainer = document.querySelector('#notif-menu .divide-y');
                        if (listContainer) {
                            listContainer.innerHTML = `
                                <div class="p-8 text-center text-gray-400 flex flex-col items-center gap-2">
                                    <i class="fa-regular fa-bell-slash text-xl text-gray-300"></i>
                                    <p class="text-xs font-medium">Tidak ada notifikasi baru.</p>
                                </div>
                            `;
                        }
                    }
                    
                    if (isUnread) {
                        const badge = document.getElementById('notif-badge');
                        const headerBadge = document.getElementById('notif-header-badge');
                        
                        if (badge) {
                            let count = parseInt(badge.textContent.trim(), 10) - 1;
                            if (count <= 0) {
                                badge.remove();
                            } else {
                                badge.textContent = count;
                            }
                        }
                        
                        if (headerBadge) {
                            let count = parseInt(headerBadge.textContent.trim(), 10) - 1;
                            if (count <= 0) {
                                headerBadge.remove();
                            } else {
                                headerBadge.textContent = count + ' baru';
                            }
                        }
                    }
                }, 250);
            } else {
                itemEl.style.opacity = '1';
                itemEl.style.transform = 'none';
                alert('Gagal menghapus notifikasi.');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            itemEl.style.opacity = '1';
            itemEl.style.transform = 'none';
            alert('Terjadi kesalahan.');
        });
    }
</script>