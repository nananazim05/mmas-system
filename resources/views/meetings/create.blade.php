<x-app-layout>
    <x-slot name="header">
        {{ __('messages.create_header') }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                <strong class="font-bold">Maklumat tidak tepat.</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#B6192E]">
            
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">{{ __('messages.activity_info') }}</h3>

                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.title_label') }}</label>
                        <input type="text" name="title" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.date_label') }}</label>
                            <input type="date" name="date" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.start_time_label') }}</label>
                            <input type="time" name="start_time" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.end_time_label') }}</label>
                            <input type="time" name="end_time" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.venue_label') }}</label>
                        <input type="text" name="venue" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.type_label') }}</label>
                            <select name="activity_type" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]">
                                <option value="Mesyuarat">{{ __('messages.meeting') }}</option>
                                <option value="Bengkel">{{ __('messages.workshop') }}</option>
                                <option value="Kursus">{{ __('messages.course') }}</option>
                                <option value="Lain-lain">{{ __('messages.others') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.organizer_label') }}</label>
                            <select name="organizer_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ Auth::id() == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-8 border-t pt-6">
                        <h4 class="text-lg font-bold text-gray-700 mb-4">{{ __('messages.invitation_section') }}</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.select_staff') }}</label>
                                <div class="h-40 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                                    @foreach($users as $user)
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="invited_staff[]" value="{{ $user->id }}" class="rounded text-[#B6192E] focus:ring-[#B6192E]">
                                            <span class="ml-2 text-sm text-gray-700">{{ $user->name }} ({{ $user->section }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.staff_help') }}</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.external_participants') }}</label>
                                <textarea name="guest_emails" rows="5" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" placeholder="ali@gmail.com, abu@yahoo.com"></textarea>
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.email_help') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition transform hover:scale-105">
                            {{ __('messages.create_submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>