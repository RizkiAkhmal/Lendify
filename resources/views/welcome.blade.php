<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lendify - Premium Tool Management System</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; background: #000; color: #fff; overflow-x: hidden; }
            .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
            .gradient-text { background: linear-gradient(135deg, #fff 0%, #009ef7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
            .hero-glow { position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(0,158,247,0.15) 0%, rgba(0,158,247,0) 70%); top: -200px; right: -100px; z-index: -1; filter: blur(60px); }
            .hero-glow-2 { position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, rgba(139,92,246,0) 70%); bottom: -100px; left: -100px; z-index: -1; filter: blur(60px); }
            @keyframes floating { 0% { transform: translateY(0px); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0px); } }
            .float { animation: floating 6s ease-in-out infinite; }
        </style>
    </head>
    <body class="antialiased selection:bg-[#009ef7] selection:text-white">
        <div class="hero-glow"></div>
        <div class="hero-glow-2"></div>

        <!-- Navbar -->
        <nav class="fixed top-0 w-full z-50 px-6 py-6 border-b border-white/5 bg-black/50 backdrop-blur-lg">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-[#009ef7] rounded-xl flex items-center justify-center shadow-lg shadow-[#009ef7]/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7L12 12L22 7L12 2Z"/><path d="M2 17L12 22L22 17" opacity="0.5"/><path d="M2 12L12 17L22 12" opacity="0.3"/></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter uppercase italic">Lendify</span>
                </div>
                
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 rounded-full border border-white/10 hover:bg-white/5 transition font-semibold text-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-semibold hover:text-[#009ef7] transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-[#009ef7] hover:bg-[#0086d1] text-white px-8 py-2 rounded-full text-sm font-bold shadow-lg shadow-[#009ef7]/30 transition transform hover:scale-105 active:scale-95">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-40 pb-20 px-6 overflow-hidden">
            <div class="max-w-7xl mx-auto text-center relative">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8 border-white/10">
                    <span class="flex h-2 w-2 rounded-full bg-[#009ef7]"></span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mt-0.5">Automated Gear Tracking</span>
                </div>
                
                <h1 class="text-5xl md:text-8xl font-black tracking-tighter mb-8 leading-[1.05]">
                    Simplify Your <br/>
                    <span class="gradient-text italic">Gear Management</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-400 max-w-3xl mx-auto mb-12 leading-relaxed">
                    Sistem peminjaman alat praktik tercanggih dengan pelacakan stok real-time, 
                    otomatisasi persetujuan, dan dashboard audit yang transparan.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-[#009ef7] text-white rounded-2xl font-black text-lg hover:bg-[#0086d1] transition shadow-2xl shadow-[#009ef7]/40">
                        Coba Gratis Sekarang
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 glass rounded-2xl font-bold text-lg hover:bg-white/5 transition border-white/10 italic">
                        Pelajari Fitur &rarr;
                    </a>
                </div>

                <!-- Dashboard Preview -->
                <div class="mt-24 relative max-w-5xl mx-auto">
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#009ef7] to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative glass rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="https://metronic.keenthemes.com/metronic/preview/assets/media/demos/demo1/light.png" alt="Dashboard Preview" class="w-full opacity-80" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                <div>
                    <h3 class="text-4xl font-black text-white mb-2">99.9%</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Uptime System</p>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-[#009ef7] mb-2">10k+</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tools Tracked</p>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-white mb-2">Instant</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Approval Flow</p>
                </div>
                <div>
                    <h3 class="text-4xl font-black text-[#009ef7] mb-2">Realtime</h3>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Audit Logs</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="mb-20">
                    <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-4">Kenapa Memilih <span class="italic text-[#009ef7]">Lendify?</span></h2>
                    <p class="text-gray-500 max-w-xl">Kami membangun standar baru dalam manajemen inventaris sekolah dan laboratorium.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <div class="p-8 rounded-3xl glass border border-white/10 hover:border-[#009ef7]/50 transition group">
                        <div class="w-14 h-14 bg-[#009ef7]/10 rounded-2xl flex items-center justify-center mb-6 text-[#009ef7] group-hover:bg-[#009ef7] group-hover:text-white transition duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-3">Approval Kilat</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Alur verifikasi otomatis oleh petugas memastikan peminjaman dilakukan dengan cepat dan terpantau.</p>
                    </div>

                    <div class="p-8 rounded-3xl glass border border-white/10 hover:border-[#009ef7]/50 transition group">
                        <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-3">Kondisi Alat Terjamin</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Sistem laporan kondisi fisik alat sebelum dan sesudah dipinjam untuk meminimalisir kerusakan.</p>
                    </div>

                    <div class="p-8 rounded-3xl glass border border-white/10 hover:border-[#009ef7]/50 transition group">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-3">Laporan Otomatis</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Ekspor laporan peminjaman bulanan atau per-user dengan sekali klik untuk keperluan administrasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-white/5 bg-black/80">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-gray-600 text-sm font-medium">&copy; {{ date('Y') }} Lendify. Built with &hearts; for modern education.</p>
            </div>
        </footer>
    </body>
</html>
