<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kehadiran - {{ $meeting->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .active-tab {
            background-color: #B6192E;
            color: white;
            border-color: #B6192E;
        }
        .inactive-tab {
            background-color: white;
            color: #4b5563;
            border-color: #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="bg-[#B6192E] p-6 text-center text-white">
            <h2 class="text-xl font-bold uppercase tracking-wide">Sahkan Kehadiran</h2>
            <p class="text-xs opacity-80 mt-1">MTIB Meeting Attendance System</p>
        </div>

        <div class="p-6">
            <div class="mb-6 text-center border-b pb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2 leading-tight">{{ $meeting->title }}</h1>
                <div class="flex justify-center items-center gap-4 text-sm text-gray-600 mt-2">
                    <div class="flex items-center">
                        <span class="mr-1">📅</span> 
                        {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}
                    </div>
                    <div class="flex items-center">
                        <span class="mr-1">📍</span> 
                        {{ $meeting->venue }}
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 mb-6 rounded-lg text-center shadow-sm">
                    <div class="text-3xl mb-2">✅</div>
                    <p class="font-bold text-lg">Kehadiran Direkodkan!</p>
                    <p class="text-sm mt-1">{{ session('success') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400">Anda boleh menutup halaman ini sekarang.</p>
                </div>
            @elseif(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-lg text-center">
                    <p class="font-bold">Ralat</p>
                    <p>{{ session('error') }}</p>
                </div>
            @else

                <form action="{{ route('attendance.store') }}" method="POST"> 
                    @csrf
                    <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">

                    <div class="grid grid-cols-2 gap-2 mb-6">
                        <button type="button" onclick="switchTab('staff')" id="btn-staff" class="active-tab py-2 px-4 rounded-lg font-semibold text-sm border transition duration-200">
                            Staf MTIB
                        </button>
                        <button type="button" onclick="switchTab('guest')" id="btn-guest" class="inactive-tab py-2 px-4 rounded-lg font-semibold text-sm border transition duration-200">
                            Peserta Luar
                        </button>
                    </div>

                    <div class="space-y-4">
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                <span id="label-email">Alamat E-mel Rasmi (Staf)</span>
                            </label>
                            <input type="email" name="email" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B6192E] focus:border-transparent outline-none transition" 
                                placeholder="nama@mtib.gov.my">
                            <p id="hint-staff" class="text-xs text-blue-600 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Nama & Bahagian akan dikesan automatik dari sistem.
                            </p>
                        </div>

                        <div id="guest-fields" class="hidden space-y-4 border-t pt-4 mt-2">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penuh</label>
                                <input type="text" name="name" id="input-name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B6192E] focus:border-transparent outline-none transition" 
                                    placeholder="Contoh: Ali bin Abu">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Syarikat / Agensi</label>
                                <input type="text" name="company" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B6192E] focus:border-transparent outline-none transition" 
                                    placeholder="Contoh: Kementerian Perladangan">
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="w-full bg-[#B6192E] hover:bg-[#900000] text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition transform active:scale-95 mt-6 flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Sahkan Kehadiran
                    </button>
                </form>
            @endif
        </div>
        
        <div class="bg-gray-50 p-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} MTIB MMAS - Sistem Pengurusan Mesyuarat
        </div>
    </div>

    <script>
        function switchTab(type) {
            const btnStaff = document.getElementById('btn-staff');
            const btnGuest = document.getElementById('btn-guest');
            const guestFields = document.getElementById('guest-fields');
            const hintStaff = document.getElementById('hint-staff');
            const labelEmail = document.getElementById('label-email');
            const inputName = document.getElementById('input-name');

            if (type === 'staff') {
                // Aktifkan Tab Staf
                btnStaff.classList.add('active-tab');
                btnStaff.classList.remove('inactive-tab');
                btnGuest.classList.add('inactive-tab');
                btnGuest.classList.remove('active-tab');

                // Sorok field guest
                guestFields.classList.add('hidden');
                hintStaff.classList.remove('hidden');
                
                // Ubah label
                labelEmail.innerText = 'Alamat E-mel Rasmi (Staf)';
                inputName.required = false; // 
            } else {
                // Aktifkan Tab Guest
                btnGuest.classList.add('active-tab');
                btnGuest.classList.remove('inactive-tab');
                btnStaff.classList.add('inactive-tab');
                btnStaff.classList.remove('active-tab');

                // Tunjuk field guest
                guestFields.classList.remove('hidden');
                hintStaff.classList.add('hidden');

                // Ubah label
                labelEmail.innerText = 'Alamat E-mel';
                inputName.required = true; // 
            }
        }
    </script>
</body>
</html>