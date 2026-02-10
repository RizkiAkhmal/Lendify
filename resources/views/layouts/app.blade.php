<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Peminjaman Alat') }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f5f8fa;
            }
            .sidebar {
                background-color: #1e1e2d;
                transition: all 0.3s;
            }
            .sidebar .nav-link {
                color: #a2a3b7;
                transition: all 0.2s;
            }
            .sidebar .nav-link:hover, .sidebar .nav-link.active {
                color: #ffffff;
                background-color: #1b1b28;
            }
            .sidebar .nav-link.active {
                color: #ffffff;
                border-left: 3px solid #009ef7;
            }
            .header {
                background-color: #ffffff;
                border-bottom: 1px solid #eff2f5;
            }
            .card-metronic {
                background-color: #ffffff;
                border-radius: 0.75rem;
                box-shadow: 0 0.1rem 1rem 0.25rem rgba(0, 0, 0, 0.05);
                border: none;
            }
            .btn-primary-metronic {
                background-color: #009ef7 !important;
                border-color: #009ef7 !important;
                color: #ffffff !important;
            }
            .btn-primary-metronic:hover {
                background-color: #0086d1 !important;
            }
        </style>
    </head>
    <body class="antialiased overflow-hidden">
        <div class="flex h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 sidebar transform lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <svg class="w-10 h-10 text-white fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="ml-3 text-xl font-bold text-white uppercase tracking-wider">SIPEMA</span>
                    </div>
                </div>

                <nav class="mt-10 overflow-y-auto h-[calc(100vh-100px)] px-4">
                    @include('layouts.sidebar-menu')
                </nav>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="header h-16 flex items-center justify-between px-6">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        
                        <div class="ml-4 flex items-center">
                            @isset($header)
                                <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                                    {{ $header }}
                                </h2>
                            @endisset
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500 font-bold overflow-hidden border-2 border-primary">
                                    <span class="uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </button>

                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 border border-gray-100">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f5f8fa] p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
