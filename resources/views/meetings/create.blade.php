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

                <form action="{{ route('activities.store') }}" method="POST" id="createActivityForm">
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
                            <input type="text" 
                                   name="organizer" 
                                   class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" 
                                   placeholder="Contoh: Unit Integriti / Kelab Sukan"
                                   required>
                        </div>
                    </div>

                    <div class="mb-8 border-t pt-6">
                        <h4 class="text-lg font-bold text-gray-700 mb-4">{{ __('messages.invitation_section') }}</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.select_staff') }}</label>
                                
                                <div class="flex flex-col gap-2 mb-2 bg-gray-100 p-2 rounded border border-gray-200">
                                    <input type="text" id="staffSearch" placeholder="Cari nama staf..." 
                                           class="w-full px-3 py-1 border rounded text-sm focus:outline-none focus:border-[#B6192E]">
                                    
                                    <div class="flex items-center mt-1">
                                        <input type="checkbox" id="selectAllStaff" class="rounded text-[#B6192E] focus:ring-[#B6192E] h-4 w-4">
                                        <label for="selectAllStaff" class="ml-2 text-xs font-bold text-gray-600 cursor-pointer uppercase">
                                            Pilih Semua / Select All
                                        </label>
                                    </div>
                                </div>

                                <div id="staffListContainer" class="h-48 overflow-y-auto border rounded-lg p-3 bg-gray-50 space-y-2">
                                    @foreach($users as $user)
                                        <div class="staff-item flex items-start">
                                            <input type="checkbox" 
                                                   name="invited_staff[]" 
                                                   value="{{ $user->id }}" 
                                                   class="staff-checkbox mt-1 rounded text-[#B6192E] focus:ring-[#B6192E]">
                                            <label class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                <span class="staff-name font-semibold">{{ $user->name }}</span>
                                                <span class="text-xs text-gray-500 block">({{ $user->section ?? 'Staf MTIB' }})</span>
                                            </label>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            const form = document.getElementById('createActivityForm'); 
            const backupKey = 'mmas_create_activity_backup';

            // 1. AUTO SAVE
            function saveFormData() {
                const formData = {};
                form.querySelectorAll('input:not([type="checkbox"]), textarea, select').forEach(el => {
                    if (el.name) formData[el.name] = el.value;
                });
                const checkedStaff = [];
                form.querySelectorAll('input[name="invited_staff[]"]:checked').forEach(el => {
                    checkedStaff.push(el.value);
                });
                formData['invited_staff'] = checkedStaff;
                localStorage.setItem(backupKey, JSON.stringify(formData));
            }

            function loadFormData() {
                const savedData = localStorage.getItem(backupKey);
                if (!savedData) return;
                try {
                    const formData = JSON.parse(savedData);
                    for (const [name, value] of Object.entries(formData)) {
                        if (name === 'invited_staff') continue; 
                        const el = form.querySelector(`[name="${name}"]`);
                        if (el) el.value = value;
                    }
                    if (formData.invited_staff && Array.isArray(formData.invited_staff)) {
                        formData.invited_staff.forEach(id => {
                            const checkbox = document.querySelector(`input[value="${id}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                } catch (e) { console.error(e); }
            }

            form.addEventListener('input', saveFormData);
            form.addEventListener('change', saveFormData);
            form.addEventListener('submit', () => localStorage.removeItem(backupKey));
            loadFormData();

            // 2. LIVE SEARCH & SELECT ALL
            const searchInput = document.getElementById('staffSearch');
            const selectAllCheckbox = document.getElementById('selectAllStaff');
            const staffItems = document.querySelectorAll('.staff-item');

            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                staffItems.forEach(item => {
                    const name = item.querySelector('.staff-name').innerText.toLowerCase();
                    item.style.display = name.includes(term) ? 'flex' : 'none';
                });
            });

            selectAllCheckbox.addEventListener('change', function(e) {
                const isChecked = e.target.checked;
                staffItems.forEach(item => {
                    if (item.style.display !== 'none') {
                        item.querySelector('.staff-checkbox').checked = isChecked;
                    }
                });
                saveFormData();
            });
        });
    </script>
</x-app-layout>