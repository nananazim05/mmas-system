<x-app-layout>
    <x-slot name="header">
        {{ __('messages.create_staff_header') }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">Sila semak semula borang!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#B6192E]">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">{{ __('messages.personal_info') }}</h3>

                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.full_name') }}</label>
                        <input type="text" name="name" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.official_email') }}</label>
                            <input type="email" name="email" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.ic_no') }}</label>
                            <input type="text" name="ic_number" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" placeholder="{{ __('messages.ic_placeholder') }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.staff_id_label') }}</label>
                            <input type="text" name="staff_number" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.grade_label') }}</label>
                            <input type="text" name="grade" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" placeholder="{{ __('messages.grade_placeholder') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.system_role') }}</label>
                            <select name="role" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]">
                                <option value="staff">{{ __('messages.role_staff') }}</option>
                                <option value="admin">{{ __('messages.role_admin') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.section_label') }}</label>
                            <input type="text" name="section" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.division_label') }}</label>
                            <input type="text" name="division" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('staff.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition transform hover:scale-105">
                            {{ __('messages.save_staff') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>