<style>
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        .print\:block { display: block !important; }
        .print\:hidden, .no-print { display: none !important; }
        #printableArea {
            position: absolute; left: 0; top: 0; width: 100%; height: 100%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: white; padding: 0; margin: 0; border: none; box-shadow: none;
        }
    }
</style>

<x-app-layout>
    <x-slot name="header">
        {{ __('messages.activity_details') }}
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        
        <a href="javascript:history.back()" class="inline-flex items-center text-gray-500 hover:text-[#B6192E] font-medium mb-4 transition no-print">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('messages.back_to_list') }}
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#B6192E]">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $meeting->title }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded mt-2 inline-block">{{ $meeting->activity_type }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm text-gray-500">{{ __('messages.status_label') }}</span>
                            <span class="text-green-600 font-bold uppercase text-sm">{{ $meeting->status }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t pt-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">{{ __('messages.date_label') }}</p>
                                <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($meeting->date)->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">{{ __('messages.start_time_label') }}</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">{{ __('messages.venue_label') }}</p>
                                <p class="text-lg font-bold text-gray-800">{{ $meeting->venue }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-gray-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">{{ __('messages.organizer_label') }}</p>
                                <p class="text-lg font-bold text-gray-800">{{ $meeting->organizer->name ?? 'Staf MTIB' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::id() === $meeting->organizer_id)
                
                <div id="printableArea" class="bg-white shadow-lg rounded-lg overflow-hidden p-8 flex flex-col items-center justify-center text-center border-t-4 border-gray-800 relative">
                    
                    <div class="hidden print:block mb-6 w-full text-center">
                        <h1 class="text-3xl font-bold text-black mb-2 uppercase">{{ $meeting->title }}</h1>
                        <div class="text-lg text-gray-600 border-b-2 border-gray-400 pb-4 mb-4">
                            <p><strong>{{ __('messages.date_label') }}:</strong> {{ \Carbon\Carbon::parse($meeting->date)->format('d F Y') }}</p>
                            <p><strong>{{ __('messages.start_time_label') }}:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($meeting->end_time)->format('H:i') }}</p>
                            <p><strong>{{ __('messages.venue_label') }}:</strong> {{ $meeting->venue }}</p>
                        </div>
                        <p class="text-base text-black font-semibold mb-2">
                            {{ __('messages.scan_instruction') }}
                        </p>
                    </div>

                    <h4 class="text-xl font-bold text-gray-800 mb-4 print:hidden">{{ __('messages.scan_attendance_title') }}</h4>
                    <p class="text-sm text-gray-500 mb-6 print:hidden">{{ __('messages.scan_instruction') }}</p>
                    
                    <div class="p-4 bg-white border-2 border-gray-200 rounded-lg inline-block print:border-4 print:border-black print:p-2">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate(route('attendance.scan', ['meeting' => $meeting->id, 'code' => $meeting->qr_code_string])) !!}
                    </div>

                    <div class="mt-6 no-print">
                        <p class="text-xs text-gray-400 mb-2">{{ __('messages.testing_only') }}</p>
                        <a href="{{ route('attendance.scan', ['meeting' => $meeting->id, 'code' => $meeting->qr_code_string]) }}" target="_blank" class="text-blue-600 underline text-sm font-bold hover:text-blue-800">
                            {{ __('messages.simulation_link') }}
                        </a>
                    </div>

                    <div class="mt-6 w-full no-print space-y-3">
                        <button onclick="window.print()" class="w-full bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-900 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span class="text-white">{{ __('messages.print_qr') }}</span>
                        </button>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('activities.report.view', $meeting->id) }}" target="_blank" class="w-1/2 bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span class="text-white text-xs">{{ __('messages.view_report') }}</span>
                            </a>

                            <a href="{{ route('activities.report', $meeting->id) }}" class="w-1/2 bg-red-700 text-white font-bold py-2 px-4 rounded hover:bg-red-800 transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="text-white text-xs">{{ __('messages.download_report') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

            @else
                
                <div class="bg-gray-50 rounded-lg p-8 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-300 h-full">
                    <div class="p-4 bg-white rounded-full shadow-sm mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-600">{{ __('messages.qr_hidden_title') }}</h4>
                    <p class="text-sm text-gray-500 mt-2">{{ __('messages.qr_hidden_desc') }}</p>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>