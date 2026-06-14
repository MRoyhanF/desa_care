<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: false, 
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
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS & Config for Zero-setup theaming -->
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                        colors: {
                            primary: {
                                50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80',
                                500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Theme Initialization Script to prevent FOUC -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased selection:bg-primary-500 selection:text-white transition-colors duration-300">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 dark:bg-slate-950 relative overflow-hidden">
            <!-- Tombol Kembali -->
            <a href="/" class="absolute top-6 left-6 sm:top-8 sm:left-8 z-30 inline-flex items-center gap-2 px-4 py-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 rounded-full text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-sm font-medium">Beranda</span>
            </a>

            <!-- Decorative Background Blobs -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-primary-400 blur-[100px] opacity-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-teal-400 blur-[100px] opacity-20 pointer-events-none"></div>

            <div class="z-10 flex flex-col items-center">
                <a href="/" class="flex flex-col items-center gap-3 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logoMJ.png') }}" alt="Logo Desa" class="w-16 h-16 object-contain">
                </a>
                <h1 class="mt-4 text-2xl font-bold text-slate-800 dark:text-slate-200">Desa Bukit Mulya</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Sistem Pengaduan Masyarakat</p>
            </div>

            <div class="z-10 w-full sm:max-w-md mt-6 px-8 py-8 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-2xl shadow-primary-900/5 dark:shadow-black/40 overflow-hidden sm:rounded-3xl border border-slate-100 dark:border-slate-800">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
