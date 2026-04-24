<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Daftar Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ isModalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between" x-transition.duration.500ms>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-emerald-800 dark:text-emerald-300 font-medium text-sm">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-2xl shadow-primary-900/5 sm:rounded-3xl">
                <!-- Header Card & Search -->
                <div class="p-6 sm:p-8 flex items-center gap-4 w-full lg:w-auto justify-between border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/40 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200">Manajemen Pengguna</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $users->total() }} total data</p>
                        </div>
                    </div>
                    
                    <!-- Create User Button (Mobile & Desktop) -->
                    <button @click="isModalOpen = true" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white px-4 py-2.5 rounded-full font-medium text-sm transition-all shadow-lg shadow-primary-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">Tambah Pengguna</span>
                    </button>
                </div>
                <div class="p-6 sm:p-8 flex flex-col lg:flex-row justify-end items-start lg:items-center gap-4 border-b border-slate-100 dark:border-slate-800">

                    <!-- Search & Filter Form -->
                    <form method="GET" action="{{ route('users.index') }}" class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-3">
                        
                        <!-- Role Filter -->
                        <div class="relative w-full sm:w-auto">
                            <select name="role" onchange="this.form.submit()" class="bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-full focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full pl-4 pr-10 py-2.5 dark:text-slate-300 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                                <option value="">Semua Peran</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <!-- Items per page -->
                        <div class="relative w-full sm:w-auto">
                            <select name="per_page" onchange="this.form.submit()" class="bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-full focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full pl-4 pr-10 py-2.5 dark:text-slate-300 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                                <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 / hal</option>
                                <option value="10" {{ request('per_page', '5') == '10' ? 'selected' : '' }}>10 / hal</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 / hal</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 / hal</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..." 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-full text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-slate-300 transition-all shadow-sm hover:bg-slate-50">
                        </div>
                        
                        <!-- Submit button is hidden since we use onchange, but keep for enter key on search -->
                        <button type="submit" class="hidden">Cari</button>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/30 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-semibold">
                                <th class="px-6 py-5">ID</th>
                                <th class="px-6 py-5">Pengguna</th>
                                <th class="px-6 py-5">Email</th>
                                <th class="px-6 py-5">No. HP</th>
                                <th class="px-6 py-5">Role</th>
                                <th class="px-6 py-5">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">#{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-xs uppercase">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $user->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800/50">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-sm">Tidak ada data pengguna ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Embedded Pagination (Always shows when there are any results/links, even single page wrapper for consistency) -->
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 w-full flex-col items-center">
                    {{ $users->links('vendor.pagination.tailwind') }}
                </div>
            </div>

            <!-- Modal Tambah Pengguna -->
            <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div x-show="isModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

                <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                    <!-- Modal Panel -->
                    <div x-show="isModalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         @click.away="isModalOpen = false"
                         class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100 dark:border-slate-800">
                        
                        <!-- Modal Header -->
                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200" id="modal-title">Tambah Pengguna Baru</h3>
                            <button @click="isModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body (Form) -->
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="px-6 py-6 space-y-4">
                                
                                <!-- Name Input -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            class="block w-full pl-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email Input -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            class="block w-full pl-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            placeholder="email@example.com">
                                    </div>
                                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Role Select -->
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Peran</label>
                                        <select name="role" id="role" required class="block w-full bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors cursor-pointer pr-10 @error('role') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Phone Input -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">No. HP (Opsional)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            </div>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                                class="block w-full pl-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('phone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                                placeholder="0812...">
                                        </div>
                                        @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Password Input -->
                                <div x-data="{ showPassword: false }">
                                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Kata Sandi
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                                            class="block w-full pl-10 pr-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            placeholder="Minimal 8 karakter">
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-primary-500 transition-colors">
                                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-cloak x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                    @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <!-- Confirm Password Input -->
                                <div x-data="{ showConfirmPassword: false }">
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                        Konfirmasi Kata Sandi
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                                            class="block w-full pl-10 pr-10 bg-slate-50 border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm text-slate-900 dark:text-white transition-colors @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            placeholder="Ulangi kata sandi">
                                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-primary-500 transition-colors">
                                            <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-cloak x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        @if($message === __('The password field confirmation does not match.'))
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @endif
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-lg shadow-primary-500/30 transition-all">
                                    Simpan Pengguna
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
