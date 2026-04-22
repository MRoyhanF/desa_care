<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: false, 
          sidebarOpen: false,
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
    <body class="font-sans antialiased text-slate-900 selection:bg-primary-500 selection:text-white transition-colors duration-300">
        <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex relative overflow-hidden">
            
            <!-- Sidebar Area -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                @include('layouts.sidebar.admin')
            @else
                @include('layouts.sidebar.user')
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 h-screen overflow-y-auto relative z-0">
                <!-- Decorative Background Blobs for depth -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-primary-400/20 dark:bg-primary-900/20 blur-[100px] pointer-events-none -z-10"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-teal-400/20 dark:bg-teal-900/20 blur-[100px] pointer-events-none -z-10"></div>

                @include('layouts.navigation')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-sm border-b border-slate-100 dark:border-slate-800 sticky top-0 z-10 transition-colors duration-300">
                        <div class="py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
