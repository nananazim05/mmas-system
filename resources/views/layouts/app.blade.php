<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MMAS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-[#FFFBF2]" x-data="{ sidebarOpen: false, sidebarExpanded: true, sidebarHovered: false, forceClose: false }"> 
    
    <div class="min-h-screen flex flex-row">
        
        <div class="md:hidden fixed w-full bg-[#B6192E] text-white z-50 flex items-center justify-between px-4 h-16 shadow-md">
            <span class="text-xl font-bold tracking-widest">MMAS</span>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded focus:bg-[#900000]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <aside 
            @mouseenter="if(!forceClose) sidebarHovered = true"
            @mouseleave="sidebarHovered = false; forceClose = false"
            :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'w-64' : 'w-20'" 
            class="hidden md:flex flex-col shadow-xl fixed h-full z-40 bg-[#B6192E] text-white transition-all duration-300 ease-in-out"> 
            
            <div class="h-20 flex items-center px-0 border-b border-white/20 bg-[#7F0000] transition-all duration-300"
                 :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'justify-between px-6' : 'justify-center'">
                
                <div class="flex items-center space-x-2 overflow-hidden whitespace-nowrap" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>
                    <span class="text-2xl font-extrabold tracking-widest">MMAS</span>
                </div>

                <button @click="
                            sidebarExpanded = !sidebarExpanded; 
                            if(!sidebarExpanded) { forceClose = true; sidebarHovered = false; }
                        " 
                        class="text-white hover:bg-white/10 p-2 rounded-lg transition">
                    
                    <template x-if="sidebarExpanded || (sidebarHovered && !forceClose)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </template>
                    <template x-if="!(sidebarExpanded || (sidebarHovered && !forceClose))">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </template>
                </button>
            </div>

            <div class="border-b border-white/10 bg-[#900000] overflow-hidden transition-all duration-300"
                 :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'p-6' : 'p-2 py-4 text-center'">
                
                <div x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition.opacity.duration.200ms>
                    <p class="text-xs text-white/60 uppercase tracking-wider mb-1">Log Masuk Sebagai</p>
                    <p class="font-bold text-lg truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/80 capitalize mb-3">{{ Auth::user()->role }}</p>
                    
                    <div class="flex justify-center space-x-2 text-xs font-bold">
                        <a href="{{ route('change.lang', 'ms') }}" class="{{ app()->getLocale() == 'ms' ? 'text-yellow-300 underline' : 'text-white/50 hover:text-white' }}">BM</a>
                        <span class="text-white/30">|</span>
                        <a href="{{ route('change.lang', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-yellow-300 underline' : 'text-white/50 hover:text-white' }}">EN</a>
                    </div>
                </div>

                <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="text-[10px] font-bold uppercase text-yellow-300">
                        {{ app()->getLocale() }}
                    </div>
                </div>
            </div>

            <nav class="flex-1 py-6 space-y-2" 
                 :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'overflow-y-auto overflow-x-hidden' : 'overflow-visible'">
                
                <a href="{{ route('dashboard') }}" class="flex items-center py-3 rounded-lg hover:bg-white/10 transition-colors relative group"
                   :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'px-4' : 'justify-center px-2'">
                    <svg class="w-6 h-6 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-yellow-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.dashboard') }}</span>
                    <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none">{{ __('messages.dashboard') }}</div>
                </a>

                <a href="{{ route('activities.my') }}" class="flex items-center py-3 rounded-lg hover:bg-white/10 transition-colors relative group"
                   :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'px-4' : 'justify-center px-2'">
                    <svg class="w-6 h-6 shrink-0 transition-colors {{ request()->routeIs('activities.my') ? 'text-yellow-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.my_activities') }}</span>
                    <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-nowrap">{{ __('messages.my_activities') }}</div>
                </a>

                <a href="{{ route('activities.index') }}" class="flex items-center py-3 rounded-lg hover:bg-white/10 transition-colors relative group"
                   :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'px-4' : 'justify-center px-2'">
                    <svg class="w-6 h-6 shrink-0 transition-colors {{ request()->routeIs('activities.index') ? 'text-yellow-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 00-2-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.activity_list') }}</span>
                    <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-nowrap">{{ __('messages.activity_list') }}</div>
                </a>

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('staff.index') }}" class="flex items-center py-3 rounded-lg hover:bg-white/10 transition-colors relative group"
                       :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'px-4' : 'justify-center px-2'">
                        <svg class="w-6 h-6 shrink-0 transition-colors {{ request()->routeIs('staff.*') ? 'text-yellow-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.staff_profile') }}</span>
                        <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-nowrap">{{ __('messages.staff_profile') }}</div>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="flex items-center py-3 rounded-lg hover:bg-white/10 transition-colors relative group"
                   :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'px-4' : 'justify-center px-2'">
                    <svg class="w-6 h-6 shrink-0 transition-colors {{ request()->routeIs('profile.edit') ? 'text-yellow-300' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.my_profile') }}</span>
                    <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-nowrap">{{ __('messages.my_profile') }}</div>
                </a>

            </nav>

            <div class="border-t border-white/10 bg-[#900000] transition-all duration-300"
                 :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'p-4' : 'p-2 py-4 flex justify-center'">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center rounded-lg transition-colors group relative"
                            :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'w-full px-4 py-2 text-white/80 hover:text-white hover:bg-white/10' : 'text-white hover:text-white justify-center'">
                        <svg class="w-6 h-6 shrink-0 transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="sidebarExpanded || (sidebarHovered && !forceClose)" x-transition>{{ __('messages.logout') }}</span>
                        <div x-show="!(sidebarExpanded || (sidebarHovered && !forceClose))" class="absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-nowrap">{{ __('messages.logout') }}</div>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 transition-all duration-300 ease-in-out flex flex-col min-h-screen"
              :class="(sidebarExpanded || (sidebarHovered && !forceClose)) ? 'md:ml-64' : 'md:ml-20'">
            
            <div class="p-8 pt-20 md:pt-8">
                @if (isset($header))
                    <header class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800">
                            {{ $header }}
                        </h2>
                    </header>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
