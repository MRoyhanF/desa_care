<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desa Bukit Mulya - Pelayanan Pengaduan Masyarakat</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logoMJ.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS (via CDN for instant styling without build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Pre-load Dark Mode to prevent FOUC -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 transition-colors duration-300 font-sans selection:bg-primary-500 selection:text-white"
      x-data="{ 
          darkMode: false, 
          mobileMenuOpen: false,
          init() { 
              this.darkMode = document.documentElement.classList.contains('dark'); 
          }, 
          toggleTheme() { 
              this.darkMode = !this.darkMode; 
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
          } 
      }">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-gray-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logoMJ.png') }}" alt="Logo MJ" class="w-12 h-12 object-contain">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg leading-tight tracking-tight text-slate-900 dark:text-white">Desa Bukit Mulya</span>
                        <span class="text-xs font-semibold tracking-wider text-primary-600 dark:text-primary-400 uppercase">Pengaduan Masyarakat</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#beranda" class="text-sm font-medium text-slate-600 hover:text-primary-600 dark:text-slate-300 dark:hover:text-primary-400 transition-colors">Beranda</a>
                    <a href="#tentang" class="text-sm font-medium text-slate-600 hover:text-primary-600 dark:text-slate-300 dark:hover:text-primary-400 transition-colors">Keunggulan</a>
                </div>

                <div class="flex items-center gap-3 md:gap-4">
                    <button @click="toggleTheme()" class="p-2.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all focus:outline-none ring-0">
                        <!-- Sun Icon -->
                        <svg x-show="darkMode" class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon -->
                        <svg x-show="!darkMode" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <div class="hidden md:flex gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full text-slate-600 dark:text-slate-300 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30">Daftar Akun</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 dark:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-all" x-collapse x-cloak>
            <div class="px-4 py-4 flex flex-col gap-4">
                <a href="#beranda" @click="mobileMenuOpen = false" class="text-sm font-medium text-slate-600 dark:text-slate-300">Beranda</a>
                <a href="#tentang" @click="mobileMenuOpen = false" class="text-sm font-medium text-slate-600 dark:text-slate-300">Keunggulan</a>
                <div class="h-px w-full bg-slate-200 dark:bg-slate-800"></div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-primary-600 dark:text-primary-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-300">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-primary-600 dark:text-primary-400">Daftar Akun</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden relative min-h-screen flex items-center">
        <!-- Floating Blobs Background -->
        <div class="absolute top-1/4 right-0 -mr-20 w-96 h-96 rounded-full bg-primary-400 blur-[120px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 w-96 h-96 rounded-full bg-teal-400 blur-[120px] opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-sm font-semibold mb-8 ring-1 ring-primary-200 dark:ring-primary-700/50">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                    </span>
                    Portal Resmi Pengaduan & Aspirasi
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6">
                    Membangun <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-teal-500 lowercase">Desa Bukit Mulya</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Jangan ragu untuk melaporkan masalah atau menyampaikan ide dan aspirasi Anda untuk kemajuan desa kita tercinta. Suara Anda sangat berarti bagi kami.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 px-8 py-4 rounded-full bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-all shadow-xl shadow-primary-600/30 hover:-translate-y-1">
                        Buat Pengaduan Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="#tentang" class="inline-flex justify-center items-center gap-2 px-8 py-4 rounded-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition-all hover:-translate-y-1 shadow-sm">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
                <div class="mt-16 relative group max-w-5xl mx-auto">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-teal-500 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden border border-slate-100 dark:border-slate-800 shadow-2xl">
                        <img src="{{ asset('images/KantorMJ.jpeg') }}" alt="Kantor Desa Bukit Mulya" class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent flex items-end p-8">
                            <div class="text-left">
                                <p class="text-white/80 text-sm font-medium mb-1">Kantor Pusat Pelayanan</p>
                                <h3 class="text-white text-2xl font-bold tracking-tight">Desa Bukit Mulya</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features Highlight -->
            <div id="tentang" class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto pt-10 border-t border-slate-200 dark:border-slate-800/50">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/40 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Respons Cepat</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Setiap laporan masuk akan segera diawasi dan ditindaklanjuti secara langsung oleh perangkat desa.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/40 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Aman & Rahasia</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Identitas privasi Anda terjamin aman. Sistem kami mencegah kebocoran informasi pelapor kepada pihak luar.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 bg-teal-50 dark:bg-teal-900/40 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Pantau Langsung</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Anda dapat memantau setiap proses dan tindak lanjut (progress tracking) dari laporan yang Anda berikan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logoMJ.png') }}" alt="Logo MJ" class="w-10 h-10 object-contain">
                <div class="flex flex-col">
                    <span class="font-bold text-lg text-slate-900 dark:text-white leading-tight">Desa Bukit Mulya</span>
                    <span class="text-xs text-slate-500">Pemerintah Desa</span>
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center">
                &copy; {{ date('Y') }} Sistem Informasi Pelayanan Pengaduan Masyarakat. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>
