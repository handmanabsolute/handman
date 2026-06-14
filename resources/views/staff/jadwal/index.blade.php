@extends('layouts.app')

@section('title', 'Jadwal Kerja')

@section('content')
<div class="space-y-6 pb-10">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Jadwal Kerja</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau agenda kerja, deadline tugas, dan catatan harian Anda.</p>
        </div>
        
        
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-gray-100 shadow-2xs">
            @php
                $bulanIndo = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $monthVal = intval($month);
                $yearVal = intval($year);
                
                $prevMonth = $monthVal - 1;
                $prevYear = $yearVal;
                if ($prevMonth < 1) {
                    $prevMonth = 12;
                    $prevYear--;
                }
                
                $nextMonth = $monthVal + 1;
                $nextYear = $yearVal;
                if ($nextMonth > 12) {
                    $nextMonth = 1;
                    $nextYear++;
                }
            @endphp
            <a href="{{ route('staff.jadwal.index', ['month' => $prevMonth, 'year' => $prevYear]) }}" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 transition-colors">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            <span class="text-sm font-bold text-gray-800 px-2 min-w-[120px] text-center">
                {{ $bulanIndo[$monthVal] }} {{ $yearVal }}
            </span>
            <a href="{{ route('staff.jadwal.index', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 transition-colors">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        @php
            $startOfMonth = \Carbon\Carbon::createFromDate($yearVal, $monthVal, 1)->startOfMonth();
            $endOfMonth = \Carbon\Carbon::createFromDate($yearVal, $monthVal, 1)->endOfMonth();
            
            
            $startOfCalendar = $startOfMonth->copy()->subDays($startOfMonth->dayOfWeek);
            
            $endOfCalendar = $endOfMonth->copy()->addDays(6 - $endOfMonth->dayOfWeek);
            
            $days = [];
            $dateIterator = $startOfCalendar->copy();
            while ($dateIterator->lessThanOrEqualTo($endOfCalendar)) {
                $days[] = $dateIterator->copy();
                $dateIterator->addDay();
            }
        @endphp

        
        <div class="grid grid-cols-7 gap-px bg-gray-200 rounded-2xl overflow-hidden border border-gray-200 shadow-2xs">
            
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-red-500 uppercase tracking-wider">Min</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Sen</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Sel</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Rab</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kam</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jum</div>
            <div class="bg-gray-50 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Sab</div>

            
            @foreach($days as $day)
                @php
                    $isCurrentMonth = $day->month == $monthVal;
                    $isToday = $day->isToday();
                    $dayTasks = $tasks->filter(function($t) use ($day) {
                        return $day->isSameDay(\Carbon\Carbon::parse($t->deadline_tugas));
                    });
                    $dayNotes = $notes->filter(function($n) use ($day) {
                        return $n->tanggal === $day->format('Y-m-d');
                    });
                @endphp
                <div onclick="selectJadwalDate('{{ $day->format('Y-m-d') }}', '{{ $day->translatedFormat('d F Y') }}')" 
                     class="bg-white min-h-[120px] p-2.5 flex flex-col justify-between hover:bg-slate-50 transition-colors cursor-pointer group {{ $isCurrentMonth ? 'current-month-cell' : 'bg-gray-50/70' }} {{ $isToday ? 'border-2 border-[#3B28CC]' : '' }}"
                     id="cell-{{ $day->format('Y-m-d') }}"
                     data-is-today="{{ $isToday ? 'true' : 'false' }}"
                     data-tasks='@json($dayTasks->values())'
                     data-notes='@json($dayNotes->values())'>
                    
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold {{ $isCurrentMonth ? ($isToday ? 'bg-[#3B28CC] text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-800') : 'text-gray-300' }}">
                            {{ $day->day }}
                        </span>
                        
                        @if($dayNotes->count() > 0)
                            <span class="note-count-indicator flex items-center gap-0.5 text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md border border-amber-200/50">
                                <i class="fa-solid fa-sticky-note"></i>
                                <span class="note-count">{{ $dayNotes->count() }}</span>
                            </span>
                        @endif
                    </div>

                    
                    <div class="tasks-container mt-2 space-y-1 overflow-hidden">
                        @foreach($dayTasks->take(3) as $task)
                            <div class="text-[9px] px-1.5 py-0.5 rounded-md border truncate font-medium
                                {{ $task->prioritas === 'Tinggi' ? 'bg-red-50 text-red-700 border-red-100' : ($task->prioritas === 'Sedang' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-green-50 text-green-700 border-green-100') }}"
                                title="{{ $task->nama_tugas }}">
                                {{ $task->nama_tugas }}
                            </div>
                        @endforeach
                        @if($dayTasks->count() > 3)
                            <div class="text-[9px] text-gray-400 font-semibold pl-1">
                                +{{ $dayTasks->count() - 3 }} lainnya
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
    <div id="jadwal-detail-section" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
        
        
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-2">
                <i class="fa-solid fa-list-check text-[#3B28CC]"></i>
                Tugas Berjalan (<span id="detail-tasks-count">0</span>)
            </h4>
            <div id="detail-tasks-list" class="space-y-3">
                
            </div>
        </div>

        
        <div class="space-y-6">
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="fa-solid fa-sticky-note text-amber-500"></i>
                    Catatan Saya
                </h4>
                <div id="detail-notes-list" class="space-y-3">
                    
                </div>
            </div>

            
            <form id="note-form" action="/jadwal/notes" onsubmit="submitNoteForm(event)" class="bg-gray-50 border border-gray-100 rounded-xl p-4 space-y-4">
                @csrf
                <input type="hidden" id="note-date" name="tanggal" value="">
                <input type="hidden" id="note-id" name="note_id" value="">
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Catatan Penting (<span id="detail-note-date-label"></span>)</label>
                    <textarea id="note-content" name="catatan" rows="3" placeholder="Masukkan catatan agenda, rapat, atau catatan tugas di sini..." required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#3B28CC]/20 focus:border-[#3B28CC] text-gray-800 transition-all"></textarea>
                    <p class="text-xs text-red-600 error-msg hidden mt-1" id="error-catatan"></p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" id="btn-cancel-edit" onclick="resetNoteForm()" class="hidden px-3.5 py-2 border border-gray-200 text-gray-600 bg-white rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-note" class="px-3.5 py-2 bg-[#3B28CC] text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed cursor-pointer">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
