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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { 
                font-family: 'Inter', sans-serif; 
                background: #09090b;
                background-image: 
                    radial-gradient(circle at 20% 20%, rgba(0, 158, 247, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 40%);
                min-height: 100vh;
                color: #fff;
            }
            .auth-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }
            /* Customizing standard Breeze inputs to look premium on dark */
            input[type="text"], input[type="email"], input[type="password"] {
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                color: white !important;
                border-radius: 12px !important;
                transition: all 0.3s ease !important;
            }
            input:focus {
                border-color: #009ef7 !important;
                background: rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 0 0 4px rgba(0, 158, 247, 0.2) !important;
            }
            label {
                color: rgba(255, 255, 255, 0.6) !important;
                font-weight: 600 !important;
                font-size: 0.75rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .btn-primary-lendify, button[type="submit"] {
                background: #009ef7 !important;
                color: white !important;
                border: none !important;
                border-radius: 12px !important;
                font-weight: 800 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.1em !important;
                transition: all 0.3s ease !important;
                padding: 0.75rem 1.5rem !important;
                cursor: pointer !important;
            }
            .btn-primary-lendify:hover, button[type="submit"]:hover {
                background: #0086d1 !important;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -5px rgba(0, 158, 247, 0.4) !important;
            }
            a {
                color: #009ef7 !important;
                text-decoration: none !important;
                font-weight: 600 !important;
                font-size: 0.825rem !important;
                transition: all 0.2s ease !important;
            }
            a:hover {
                color: #58c0ff !important;
                text-decoration: underline !important;
            }
            .text-gray-600 {
                color: rgba(255, 255, 255, 0.5) !important;
            }
            span.ms-2 {
                color: rgba(255, 255, 255, 0.7) !important;
                font-size: 0.875rem !important;
            }
        </style>
    </head>
    <body class="antialiased flex flex-col justify-center items-center py-12 px-6">
        <div class="w-full max-w-[440px] animate-fade-in">
            <div class="text-center mb-10">
                <a href="/" class="inline-block transform hover:scale-110 transition duration-500">
                    <x-application-logo />
                </a>
            </div>

            <div class="auth-card p-10 rounded-[2.5rem]">
                {{ $slot }}
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-[10px] font-black tracking-[0.3em] text-gray-600 uppercase italic">
                    &copy; {{ date('Y') }} Lendify Ecosystem
                </p>
            </div>
        </div>
    </body>
</html>
