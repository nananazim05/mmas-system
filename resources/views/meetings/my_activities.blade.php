<x-app-layout>
    <x-slot name="header">
        {{ __('messages.my_activities') }}
    </x-slot>

    <div class="space-y-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-[#B6192E]">
            <form method="GET" action="{{ route('activities.my') }}" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                
                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                    
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" id="activitySearchInput" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E] sm:text-sm" 
                               placeholder="{{ __('messages.search_placeholder') }}">
                    </div>

                    <select name="month" class="border border-gray-300 rounded-lg text-sm focus:ring-[#B6192E] focus:border-[#B6192E]">
                        <option value="">{{ __('messages.month_placeholder') }}</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year" class="border border-gray-300 rounded-lg text-sm focus:ring-[#B6192E] focus:border-[#B6192E]">
                        <option value="">{{ __('messages.year_placeholder') }}</option>
                        @foreach(range(date('Y') - 4, date('Y') + 1) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-900 transition text-sm">
                        {{ __('messages.filter_btn') }}
                    </button>

                    @if(request('search') || request('month') || request('year'))
                        <a href="{{ route('activities.my') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition text-sm flex items-center justify-center">
                            {{ __('messages.reset_btn') }}
                        </a>
                    @endif
                </div>

                <a href="{{ route('activities.create') }}" class="w-full md:w-auto px-4 py-2 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition flex items-center justify-center text-sm whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('messages.add_new') }}
                </a>

            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.no') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.activity_title') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.date_time') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.venue') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="activityTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse ($meetings as $index => $meeting)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $meeting->title }}</div>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">{{ $meeting->activity_type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $meeting->venue }}</td>
                                <td class="px-6 py-4 text-center text-sm font-medium flex justify-center space-x-2">
                                    
                                    <a href="{{ route('activities.show', $meeting->id) }}" class="text-blue-600 hover:text-blue-900 p-1 bg-blue-50 rounded" title="Lihat & QR">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    
                                    <a href="{{ route('activities.edit', $meeting->id) }}" class="text-yellow-600 hover:text-yellow-900 p-1 bg-yellow-50 rounded" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('activities.destroy', $meeting->id) }}" method="POST" onsubmit="return confirm('Adakah anda pasti?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1 bg-red-50 rounded" title="Padam">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr id="noRecordsRow">
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <p>{{ __('messages.no_activities_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('activitySearchInput').addEventListener('keyup', function() {
            var input = this.value.toLowerCase();
            var rows = document.querySelectorAll('#activityTableBody tr');
            
            rows.forEach(function(row) {
                
                if (row.id === 'noRecordsRow') return;

                var text = row.innerText.toLowerCase();
                if (text.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</x-app-layout>