<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Tohjaya Contractor</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen">
        <div class="flex flex-col lg:flex-row bg-white overflow-hidden min-h-screen">
            
            <!-- Kolom Kiri (Form Login) -->
            <div class="w-full lg:w-1/2 p-8 sm:p-16 flex flex-col justify-center">
                <!-- Logo -->
                <div class="flex items-center mb-10">
                    <div class="w-12 h-12 bg-blue-600 rounded-full mr-4 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-800">Tohjaya.contractor</span>
                </div>

                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Welcome Back!</h2>
                <p class="text-gray-500 mb-10">Please enter your details to sign in.</p>

                <!-- Menampilkan Error Validasi -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm relative" role="alert">
                        <strong class="font-bold">Oops! </strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                    @csrf

                    <!-- Input Username -->
                    <div>
                        <label for="username" class="block text-gray-600 text-sm font-bold mb-2">USERNAME</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition duration-300" 
                                   type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter your username" />
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-gray-600 text-sm font-bold mb-2">PASSWORD</label>
                         <div class="relative">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition duration-300"
                                   type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"/>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="remember">
                            <label for="remember_me" class="ml-2 text-gray-600">Remember me</label>
                        </div>
                        <a href="#" class="font-medium text-blue-600 hover:underline">Forgot password?</a>
                    </div>

                    <!-- Tombol Submit -->
                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105 text-lg shadow-lg hover:shadow-xl">
                            LOGIN
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan (Branding) -->
            <div class="hidden lg:flex relative w-full lg:w-1/2 flex-col justify-center items-center text-white bg-blue-600">
                <!-- Gambar Latar Belakang Abstrak -->
                <div class="absolute inset-0 opacity-20">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                      <path fill="currentColor" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,192C384,203,480,245,576,250.7C672,256,768,224,864,197.3C960,171,1056,149,1152,154.7C1248,160,1344,192,1392,208L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
                    </svg>
                     <svg class="w-full h-full absolute bottom-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                      <path fill="currentColor" fill-opacity="0.5" d="M0,96L48,128C96,160,192,224,288,229.3C384,235,480,181,576,170.7C672,160,768,192,864,208C960,224,1056,224,1152,208C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                    </svg>
                </div>
                
                <div class="text-center z-10 p-8">
                    <div class="bg-white/20 rounded-full p-4 inline-block mb-6 backdrop-blur-sm border border-white/30">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black tracking-widest leading-tight">TOHJAYA.CONTRACTOR</h3>
                    <p class="text-white/80 mt-4">Building The Future, Restoring The Past.</p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>

