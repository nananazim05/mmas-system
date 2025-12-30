<x-app-layout>
    <x-slot name="header">
        {{ __('messages.dashboard') }}
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <a href="{{ route('activities.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center border-t-4 border-green-500 hover:bg-green-50 transition cursor-pointer group">
                <div class="p-3 rounded-full bg-green-100 mb-3 group-hover:bg-green-200 transition">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="text-gray-500 text-sm font-medium text-center">
                    {{ Auth::user()->role === 'admin' ? __('messages.total_org_activities') : __('messages.total_invited_activities') }} ({{ date('Y') }})
                </div>
                <div class="text-3xl font-bold text-green-600 mt-1">{{ $jumlahAktivitiTahunIni }}</div>
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center border-t-4 border-red-500">
                <div class="p-3 rounded-full bg-red-100 mb-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-gray-500 text-sm font-medium">{{ __('messages.upcoming') }}</div>
                <div class="text-3xl font-bold text-red-600 mt-1">{{ $jumlahAkanDatang }}</div>
            </div>

            <a href="{{ route('activities.create') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center border-t-4 border-blue-900 hover:bg-gray-50 transition cursor-pointer group">
                <div class="p-3 rounded-full bg-blue-100 mb-3 group-hover:bg-blue-200 transition">
                    <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div class="text-gray-500 text-sm font-medium">{{ __('messages.create_activity') }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ __('messages.click_to_add') }}</div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white overflow-visible shadow-sm sm:rounded-lg p-6">
                
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
                    <h3 class="text-lg font-bold text-gray-800 uppercase hidden sm:block">
                        {{ __('messages.calendar') }} - {{ $tarikhKalendar->translatedFormat('F Y') }}
                    </h3>

                    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                        <select name="month" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 py-1 pl-2 pr-8 cursor-pointer bg-gray-50 hover:bg-white transition">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == $tarikhKalendar->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select name="year" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 py-1 pl-2 pr-8 cursor-pointer bg-gray-50 hover:bg-white transition">
                            @foreach(range(date('Y') - 5, date('Y') + 2) as $y) 
                                <option value="{{ $y }}" {{ $y == $tarikhKalendar->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>

                    <div class="flex gap-1">
                        <a href="{{ route('dashboard', ['month' => $tarikhKalendar->copy()->subMonth()->month, 'year' => $tarikhKalendar->copy()->subMonth()->year]) }}" 
                           class="p-1.5 rounded hover:bg-gray-200 text-gray-500 transition" title="{{ __('messages.prev') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <a href="{{ route('dashboard', ['month' => $tarikhKalendar->copy()->addMonth()->month, 'year' => $tarikhKalendar->copy()->addMonth()->year]) }}" 
                           class="p-1.5 rounded hover:bg-gray-200 text-gray-500 transition" title="{{ __('messages.next') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                
                <div class="border rounded-lg p-4">
                    <div class="grid grid-cols-7 gap-2 text-center text-sm mb-2 font-bold text-gray-500 uppercase">
                        <div>{{ __('messages.days.sun') }}</div>
                        <div>{{ __('messages.days.mon') }}</div>
                        <div>{{ __('messages.days.tue') }}</div>
                        <div>{{ __('messages.days.wed') }}</div>
                        <div>{{ __('messages.days.thu') }}</div>
                        <div>{{ __('messages.days.fri') }}</div>
                        <div>{{ __('messages.days.sat') }}</div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-sm">
                        @php
                            $daysInMonth = $tarikhKalendar->daysInMonth;
                            $startDay = $tarikhKalendar->copy()->startOfMonth()->dayOfWeek;
                        @endphp
                        @for ($i = 0; $i < $startDay; $i++) <div class="p-2"></div> @endfor
                        
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $checkDate = $tarikhKalendar->copy()->day($day)->format('Y-m-d');
                                $isToday = $checkDate == now()->format('Y-m-d');
                                $hasMeeting = in_array($checkDate, $tarikhMeeting);

                                // Cari tajuk meeting untuk Tooltip (jika ada)
                                $meetingTitles = [];
                                if ($hasMeeting) {
                                    $meetingsOnDay = \App\Models\Meeting::whereDate('date', $checkDate)->get();
                                    foreach($meetingsOnDay as $m) {
                                        $meetingTitles[] = $m->title; // Simpan tajuk aktiviti
                                    }
                                }
                            @endphp
                            
                            <div class="p-2 rounded relative group {{ $isToday ? 'bg-green-100 text-green-800 font-bold' : 'hover:bg-gray-100' }} {{ $hasMeeting ? 'border-2 border-red-500 font-bold text-red-600 cursor-pointer' : '' }}">
                                {{ $day }}
                                
                                @if($hasMeeting) 
                                    <span class="absolute bottom-1 right-1 w-2 h-2 bg-red-600 rounded-full"></span> 
                                    
                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max max-w-xs bg-black text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-50 pointer-events-none">
                                        @foreach($meetingTitles as $title)
                                            <div class="truncate">• {{ $title }}</div>
                                        @endforeach
                                        <svg class="absolute text-black h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                                    </span>
                                @endif
                            </div>
                        @endfor
                    </div>
                    <div class="mt-4 text-xs text-gray-500 flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-600 rounded-full block"></span> {{ __('messages.activity_indicator') }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('messages.upcoming_activities') }}</h3>
                
                <div class="space-y-4">
                    @forelse($aktivitiAkanDatang as $meeting)
                        <div class="border-l-4 border-red-900 bg-gray-50 p-3 rounded-r-lg hover:shadow-md transition">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $meeting->title }}</h4>
                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }} | {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }}
                            </div>
                            <div class="mt-2 text-[10px] bg-yellow-100 text-yellow-800 px-2 py-1 rounded inline-block">
                                {{ __('messages.organizer') }}: {{ $meeting->organizer ?? 'Staf' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 py-4 text-sm">
                            {{ __('messages.no_upcoming_activities') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>