<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontraktor Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom transition for sidebar */
        .transition-all-300 {
            transition: all 300ms ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024">
        <!-- Sidebar -->
        <aside 
            class="bg-white text-gray-800 w-64 fixed inset-y-0 left-0 z-30 transform transition-all-300 border-r border-gray-200"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <div class="flex flex-col h-full">
                <!-- Logo & Menu -->
                <div>
                    <div class="p-6">
                        <a href="{{ route('dashboard.kontraktor') }}" class="text-2xl font-bold">Tohjaya.<span class="text-blue-400">contractor</span></a>
                    </div>
                    <nav class="mt-4">
                        <ul>
                            <li class="px-4 py-1">
                                <a href="{{ route('dashboard.kontraktor') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('dashboard.kontraktor') ? 'bg-blue-600' : '' }}">
                                    <!-- Icon -->
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    Overview
                                </a>
                            </li>
                            <li class="px-4 py-1">
                                <a href="{{ route('proyek.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('proyek.index') ? 'bg-blue-600' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Proyek
                                </a>
                            </li>
                            <li class="px-4 py-1">
                                <a href="{{ route('tagihan.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('tagihan.index') ? 'bg-blue-600' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Tagihan
                                </a>
                            </li>
                            <li class="px-4 py-1">
                                <a href="{{ route('pengeluaran.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('pengeluaran.index') ? 'bg-blue-600' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Pengeluaran
                                </a>
                            </li>
                            <li class="px-4 py-1">
                                <a href="{{ route('tukang.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('tukang.index') ? 'bg-blue-600' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Tukang
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Tombol Logout -->
                <div class="mt-auto p-6">
                     @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                     @endauth
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all-300" :class="{'lg:ml-64': sidebarOpen}">
            <!-- Header Konten -->
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <!-- Tombol Trigger Sidebar -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <!-- User Info -->
                <div class="flex items-center">
                    @auth
                        <span class="text-gray-800 mr-4 hidden sm:block">Selamat datang, <span class="font-bold">{{ Auth::user()->name }}</span></span>
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endauth
                </div>
            </header>
            
            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

