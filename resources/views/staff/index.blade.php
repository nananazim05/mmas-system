<x-app-layout>
    <x-slot name="header">
        {{ __('messages.staff_management') }}
    </x-slot>

    <div class="space-y-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-lg shadow-sm border-l-4 border-[#B6192E]">
            
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="searchInput" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-[#B6192E] focus:ring-[#B6192E] sm:text-sm transition duration-150 ease-in-out" 
                       placeholder="{{ __('messages.search_staff_placeholder') }}">
            </div>



        @if(auth()->user()->role === 'admin') 

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
        
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
                    {{-- Kiri: Tajuk & Penerangan --}}
                 <div class="text-gray-700">
                      <h3 class="font-bold text-lg text-gray-800">📥 {{ __('messages.import_title') }}</h3>
                       <p class="text-sm text-gray-500">{{ __('messages.import_desc') }}</p>
                 </div>

                   {{-- Kanan: Form Upload --}}
                    <form action="{{ route('staff.import') }}" 
                        method="POST" 
                        enctype="multipart/form-data" 
                        class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                
                      @csrf 

                     {{-- Input Fail --}}
                     <input type="file" 
                           name="file" 
                           required
                           accept=".xlsx, .xls"
                           class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100
                              border border-gray-300 rounded-md cursor-pointer p-1">

                     {{-- Butang Submit --}}
                      <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition duration-200 flex items-center justify-center gap-2">
                     <span>{{ __('messages.upload_btn') }}</span>
                     </button>
                    </form>
                </div>
         
                {{-- Jika Berjaya --}}
              @if(session('success'))
                   <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded flex items-center gap-2">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                       <span>{{ session('success') }}</span>
                   </div>
              @endif

              {{-- Jika Gagal / Error --}}
              @if($errors->any())
                   <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                           Terdapat Masalah:
                               </p>
                                <ul class="list-disc list-inside ml-5 mt-1 text-sm">
                                   @foreach ($errors->all() as $error)
                                       <li>{{ $error }}</li>
                                   @endforeach
                                </ul>
                    </div>
                @endif

            </div>

        @endif
            
            <a href="{{ route('staff.create') }}" class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-[#B6192E] text-white font-bold rounded-lg hover:bg-[#900000] shadow-md transition text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('messages.add_staff') }}
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">{{ __('messages.no') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.staff_no') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.name_email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.position') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.role') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="staffTableBody" class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                                    {{ $user->staff_number ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ __('messages.ic') }}: {{ $user->ic_number }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->section }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->division }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                        {{ __('messages.grade') }} {{ $user->grade }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ __('messages.role_admin') }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ __('messages.role_staff') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('staff.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 p-1 bg-blue-50 rounded" title="Lihat Rekod">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        <a href="{{ route('staff.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-900 p-1 bg-yellow-50 rounded" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        @if(Auth::id() !== $user->id)
                                            <form action="{{ route('staff.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Adakah anda pasti?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 bg-red-50 rounded" title="Padam">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noRecordsRow">
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <p>{{ __('messages.no_records') }}</p>
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
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = this.value.toLowerCase();
            var rows = document.querySelectorAll('#staffTableBody tr');
            
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