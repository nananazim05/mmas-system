<x-app-layout>
    <x-slot name="header">
        {{ __('messages.edit_header') }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                <strong class="font-bold">Alamak! Ada masalah sikit.</strong>
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

                <form action="{{ route('activities.update', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.title_label') }}</label>
                        <input type="text" name="title" value="{{ old('title', $meeting->title) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.date_label') }}</label>
                            <input type="date" name="date" value="{{ old('date', $meeting->date) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.start_time_label') }}</label>
                            <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($meeting->start_time)->format('H:i')) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.end_time_label') }}</label>
                            <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($meeting->end_time)->format('H:i')) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.venue_label') }}</label>
                        <input type="text" name="venue" value="{{ old('venue', $meeting->venue) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.type_label') }}</label>
                            <select name="activity_type" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]">
                                <option value="Mesyuarat" {{ old('activity_type', $meeting->activity_type) == 'Mesyuarat' ? 'selected' : '' }}>{{ __('messages.meeting') }}</option>
                                <option value="Bengkel" {{ old('activity_type', $meeting->activity_type) == 'Bengkel' ? 'selected' : '' }}>{{ __('messages.workshop') }}</option>
                                <option value="Kursus" {{ old('activity_type', $meeting->activity_type) == 'Kursus' ? 'selected' : '' }}>{{ __('messages.course') }}</option>
                                <option value="Lain-lain" {{ old('activity_type', $meeting->activity_type) == 'Lain-lain' ? 'selected' : '' }}>{{ __('messages.others') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.organizer_label') }}</label>
                            <select name="organizer_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('organizer_id', $meeting->organizer_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4">
                        <p class="text-sm text-blue-700">
                            <strong>{{ __('messages.update_note') }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('activities.my') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition transform hover:scale-105">
                            {{ __('messages.update_submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>