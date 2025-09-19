<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner Dashboard</title>

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
        .transition-all-300 {
            transition: all 300ms ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" @resize.window="sidebarOpen = window.innerWidth > 1024">
        <!-- Sidebar -->
        <aside 
            class="bg-white text-gray-800 w-64 fixed inset-y-0 left-0 z-30 transform transition-all-300 border-r border-gray-200"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <div class="flex flex-col h-full">
                <!-- Logo & Menu -->
                <div>
                    <div class="p-6 border-b border-gray-200">
                        <a href="{{ route('dashboard.owner') }}" class="text-2xl font-bold text-gray-800">Owner<span class="text-blue-600">Panel</span></a>
                    </div>
                    <nav class="mt-4">
                        <ul>
                            <li class="px-4 py-1">
                                <a href="{{ route('dashboard.owner') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('dashboard.owner') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Laporan Keuangan
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Tombol Logout -->
                <div class="mt-auto p-6 border-t border-gray-200">
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
            <header class="bg-white shadow-sm p-4 flex justify-between items-center border-b border-gray-200">
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

