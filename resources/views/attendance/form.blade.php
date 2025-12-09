<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kehadiran - {{ $meeting->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="bg-[#B6192E] p-6 text-center text-white">
            <h2 class="text-xl font-bold uppercase tracking-wide">Sahkan Kehadiran</h2>
            <p class="text-xs opacity-80 mt-1">MTIB Meeting Attendance System</p>
        </div>

        <div class="p-6">
            <div class="mb-6 text-center border-b pb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $meeting->title }}</h1>
                <p class="text-gray-600 text-sm mb-1">
                    📅 {{ \Carbon\Carbon::parse($meeting->date)->format('d M Y') }}
                </p>
                <p class="text-gray-600 text-sm">
                    📍 {{ $meeting->venue }}
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded text-center">
                    <p class="font-bold">Berjaya!</p>
                    <p>{{ session('success') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Anda boleh menutup halaman ini.</p>
                </div>
            @elseif(session('error'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded text-center">
                    <p class="font-bold">Info</p>
                    <p>{{ session('error') }}</p>
                </div>
            @else

                <form action="{{ route('attendance.store', $meeting->id) }}" method="POST">
                    @csrf

                    @auth
                        <input type="hidden" name="type" value="staff">
                        <div class="bg-blue-50 p-4 rounded-lg mb-6 text-center border border-blue-200">
                            <p class="text-sm text-gray-600 mb-1">Log masuk sebagai:</p>
                            <p class="font-bold text-lg text-blue-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->section ?? 'Staf MTIB' }}</p>
                        </div>
                    @else
                        <input type="hidden" name="type" value="guest">
                        
                        <div class="bg-yellow-50 p-3 rounded mb-4 text-xs text-yellow-800 border border-yellow-200">
                            Peserta Luar? Sila isi nama dan e-mel. <br>
                            Staf MTIB? <a href="{{ route('login') }}" class="underline font-bold">Log Masuk dahulu</a>.
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penuh</label>
                            <input type="text" name="guest_name" class="w-full px-4 py-2 border rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required placeholder="Contoh: Ali bin Abu">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat E-mel</label>
                            <input type="email" name="guest_email" class="w-full px-4 py-2 border rounded-lg focus:ring-[#B6192E] focus:border-[#B6192E]" required placeholder="ali@gmail.com">
                        </div>
                    @endauth

                    <button type="submit" class="w-full bg-[#B6192E] hover:bg-[#900000] text-white font-bold py-3 rounded-lg shadow transition transform active:scale-95">
                        Hadir & Masuk
                    </button>
                </form>
            @endif
        </div>
        
        <div class="bg-gray-50 p-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} MTIB MMAS
        </div>
    </div>
</body>
</html>