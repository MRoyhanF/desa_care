<!-- Admin Sidebar -->
<aside class="bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 w-64 h-screen hidden sm:flex flex-col transition-all duration-300 z-50 fixed sm:static"
       :class="{'hidden': !sidebarOpen, 'flex': sidebarOpen}">
    
    <!-- Sidebar Header (Logo) -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('images/logoMJ.png') }}" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform">
            <span class="font-bold text-slate-800 dark:text-slate-200 text-lg tracking-tight">Admin Area</span>
        </a>
        <!-- Mobile close button -->
        <button @click="sidebarOpen = false" class="sm:hidden ml-auto p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-slate-800 dark:hover:text-primary-400' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="truncate">Dashboard</span>
        </a>

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen</p>
        </div>

        <a href="{{ route('report.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('report.index') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-slate-800 dark:hover:text-primary-400' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="truncate">Daftar Laporan</span>
        </a>

        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('users.index') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-slate-800 dark:hover:text-primary-400' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="truncate">Daftar Pengguna</span>
        </a>
        
        <a href="{{ route('category.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('category.*') ? 'text-primary-600 bg-primary-50 dark:bg-slate-800 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-slate-800 dark:hover:text-primary-400' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <span class="truncate">Kategori Laporan</span>
        </a>
    </div>
</aside>

<!-- Mobile sidebar overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/50 z-40 sm:hidden backdrop-blur-sm" @click="sidebarOpen = false" x-transition.opacity></div>
