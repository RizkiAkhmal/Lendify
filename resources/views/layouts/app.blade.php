<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lendify') }}</title>

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
            /* Sleek Alerts */
            .alert-metronic {
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
                font-size: 0.875rem;
                animation: slideDown 0.3s ease-out;
            }
            .alert-metronic-success {
                background-color: #e8fff3;
                color: #50cd89;
                border: 1px solid #c1f0d5;
            }
            .alert-metronic-danger {
                background-color: #fff5f8;
                color: #f1416c;
                border: 1px solid #ffd3d0;
            }
            
            /* Validation Styles */
            .input-error-metronic {
                border-color: #f1416c !important;
                background-color: #fff5f8 !important;
                animation: shake 0.4s ease-in-out;
            }
            .error-text-metronic {
                color: #f1416c;
                font-size: 0.75rem;
                font-weight: 500;
                margin-top: 0.25rem;
                display: flex;
                align-items: center;
            }
            .error-text-metronic::before {
                content: '•';
                margin-right: 4px;
                font-weight: bold;
            }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
            }

            @keyframes slideDown {
                from { transform: translateY(-10px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
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
                        <span class="ml-3 text-xl font-bold text-white uppercase tracking-wider">LENDIFY</span>
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
                    <!-- Session Alerts -->
                    @if(session('success'))
                        <div class="alert-metronic alert-metronic-success" id="alert-success">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ session('success') }}</span>
                            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto opacity-50 hover:opacity-100">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-metronic alert-metronic-danger" id="alert-error">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ session('error') }}</span>
                            <button onclick="document.getElementById('alert-error').remove()" class="ml-auto opacity-50 hover:opacity-100">&times;</button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id, message = 'Hapus data ini secara permanen?') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#1e1e2d',
                cancelButtonColor: '#f1f1f1',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    title: 'text-lg font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500',
                    confirmButton: 'px-5 py-2 rounded-md font-semibold text-white text-xs uppercase tracking-wider',
                    cancelButton: 'px-5 py-2 rounded-md font-semibold text-gray-600 text-xs uppercase tracking-wider'
                },
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function confirmSubmit(formId, title = 'Konfirmasi Pengajuan', message = 'Apakah Anda yakin data yang diisi sudah benar?') {
            Swal.fire({
                title: title,
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#1e1e2d',
                cancelButtonColor: '#f1f1f1',
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Cek Kembali',
                reverseButtons: true,
                customClass: {
                    title: 'text-lg font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500',
                    confirmButton: 'px-6 py-2 rounded-md font-semibold text-white text-xs uppercase tracking-wider',
                    cancelButton: 'px-6 py-2 rounded-md font-semibold text-gray-600 text-xs uppercase tracking-wider'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</html>
