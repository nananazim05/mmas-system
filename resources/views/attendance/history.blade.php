<x-app-layout>
    <x-slot name="header">
        Rekod Aktiviti & Kehadiran Saya
    </x-slot>

    <div class="space-y-6">
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Senarai Jemputan & Status</h3>
                
                @if($histories->isEmpty())
                    <p class="text-gray-500 text-center py-8">Anda belum dijemput ke sebarang aktiviti.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktiviti</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarikh & Masa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($histories as $index => $invite)
                                    @php
                                        // Cek adakah user dah scan QR (Attendance wujud?)
                                        $attendance = $invite->meeting->attendances->first();
                                        
                                        // Cek adakah tarikh meeting dah lepas?
                                        $isPast = \Carbon\Carbon::parse($invite->meeting->date)->endOfDay()->isPast();
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $invite->meeting->title }}</div>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $invite->meeting->activity_type }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($invite->meeting->date)->format('d M Y') }} <br>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($invite->meeting->start_time)->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invite->meeting->venue }}</td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            @if($attendance)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Hadir ({{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i') }})
                                                </span>
                                            @elseif($isPast)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Tidak Hadir
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Belum Berlangsung
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>