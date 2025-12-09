<x-app-layout>
    <x-slot name="header">
        {{ __('messages.attendance_record_for', ['name' => $user->name]) }}
    </x-slot>

    <div class="space-y-6">
        
        <div class="flex justify-between items-center mb-6">
            
            <a href="{{ route('staff.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#B6192E] font-medium transition group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('messages.back_to_staff_list') }}
            </a>

            <a href="{{ route('staff.report', $user->id) }}" class="inline-flex items-center px-5 py-2.5 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition transform hover:scale-105 text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('messages.download_record_pdf') }}
            </a>

        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500">{{ __('messages.position') }}:</span>
                    <span class="font-bold text-gray-800">{{ $user->section }} ({{ $user->grade }})</span>
                </div>
                <div>
                    <span class="block text-gray-500">{{ __('messages.staff_no') }}:</span>
                    <span class="font-bold text-gray-800">{{ $user->staff_number }}</span>
                </div>
                <div>
                    <span class="block text-gray-500">{{ __('messages.total_invitations') }}:</span>
                    <span class="font-bold text-gray-800">{{ __('messages.activities_count', ['count' => $histories->count()]) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.no') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.activity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.date') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($histories as $index => $invite)
                            @php
                                $attendance = $invite->meeting->attendances->first();
                                // Logic gabungan tarikh + masa untuk tentukan "Lepa"
                                $meetingEnd = \Carbon\Carbon::parse($invite->meeting->date . ' ' . $invite->meeting->end_time);
                                $isPast = now()->greaterThan($meetingEnd);
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $invite->meeting->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $invite->meeting->venue }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($invite->meeting->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($attendance)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ __('messages.status_present') }} ({{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i') }})
                                        </span>
                                    @elseif($isPast)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ __('messages.status_absent') }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ __('messages.status_upcoming') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    {{ __('messages.no_participation_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>