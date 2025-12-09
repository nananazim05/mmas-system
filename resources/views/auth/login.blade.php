<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MMAS - Log Masuk</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans text-gray-900 antialiased" 
          style="background: linear-gradient(135deg, #121521 10%, #38476b 35%, #b6192e 60%, #ffc1ac 75%, #f5a09a 100%); background-attachment: fixed;">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-[#FFFBF2] shadow-2xl overflow-hidden rounded-xl">
                
                <div class="flex flex-col items-center mb-8">
                    <img src="{{ asset('images/logo-mmas.png') }}" alt="MMAS Logo" class="h-24 w-auto mb-4 drop-shadow-md">
                    
                    <h1 class="text-3xl font-extrabold text-[#7F0000] tracking-tight">MMAS</h1>
                    <p class="text-gray-800 text-sm font-medium mt-1">MTIB Meeting Attendance System</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="ic_number" class="block font-bold text-[#7F0000] text-sm mb-2">No Kad Pengenalan</label>
                        <input id="ic_number" class="block w-full px-4 py-3 bg-[#E6E2DD] border-transparent focus:border-[#7F0000] focus:bg-white focus:ring-0 rounded-lg text-gray-900 placeholder-gray-500 transition-colors" type="text" name="ic_number" :value="old('ic_number')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('ic_number')" class="mt-2" />
                    </div>

                    <div class="mb-2" x-data="{ show: false }">
                        <label for="password" class="block font-bold text-[#7F0000] text-sm mb-2">Kata Laluan</label>
                        <div class="relative">
                            <input id="password" class="block w-full px-4 py-3 bg-[#E6E2DD] border-transparent focus:border-[#7F0000] focus:bg-white focus:ring-0 rounded-lg text-gray-900 placeholder-gray-500 transition-colors" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-6 mt-4">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#7F0000] shadow-sm focus:ring-[#7F0000] focus:ring-opacity-50" name="remember">
                            <span class="ml-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-[#7F0000] hover:text-red-800 transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                                {{ __('Lupa Kata Laluan ?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <button class="w-full py-3 bg-[#7F0000] hover:bg-[#600000] text-white font-bold rounded-full shadow-lg transition duration-200 transform hover:scale-105 active:scale-95 border-2 border-[#7F0000]">
                            {{ __('Log Masuk') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-white/60 text-xs">
                 &copy; {{ date('Y') }} MTIB. All rights reserved.
            </div>
        </div>
    </body>
</html>
