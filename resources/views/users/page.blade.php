<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Daftar Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        isModalOpen: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        isEditModalOpen: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }},
        isDeleteModalOpen: false,
        editData: {
            id: '{{ old('id') }}',
            nama: '{{ old('nama') }}',
            email: '{{ old('email') }}',
            telepon: '{{ old('telepon') }}',
            peran: '{{ old('peran', 'pengguna') }}'
        },
        deleteData: {
            id: '',
            name: ''
        },
        openEditModal(user) {
            this.editData = { id: user.id, nama: user.nama, email: user.email, telepon: user.telepon, peran: user.peran };
            this.isEditModalOpen = true;
        },
        openDeleteModal(id, name) {
            this.deleteData = { id, name };
            this.isDeleteModalOpen = true;
        }
    }">
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
                                <option value="admin" {{ request('peran') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pengguna" {{ request('peran') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                            </select>
                        </div>

                        <!-- Items per page -->
                        <div class="relative w-full sm:w-auto">
                            <select name="per_halaman" onchange="this.form.submit()" class="bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-full focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full pl-4 pr-10 py-2.5 dark:text-slate-300 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                                <option value="5" {{ request('per_halaman') == '5' ? 'selected' : '' }}>5 / hal</option>
                                <option value="10" {{ request('per_halaman', '5') == '10' ? 'selected' : '' }}>10 / hal</option>
                                <option value="25" {{ request('per_halaman') == '25' ? 'selected' : '' }}>25 / hal</option>
                                <option value="50" {{ request('per_halaman') == '50' ? 'selected' : '' }}>50 / hal</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari data..."
                                   class="w-full pl-10 pr-4 py-2.5 bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-full text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-slate-300 transition-all shadow-sm hover:bg-slate-50">
                        </div>
                        
                        <!-- Manual Search Button -->
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-6 py-2.5 rounded-full font-bold text-sm transition-all hover:bg-slate-800 dark:hover:bg-white shadow-lg active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
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
                                <th class="px-6 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($pengguna as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">#{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->foto && file_exists(public_path($user->foto)))
                                            <img src="{{ asset($user->foto) }}" alt="{{ $user->nama }}" class="h-8 w-8 rounded-full object-cover border border-slate-100 dark:border-slate-800 shadow-sm">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-xs uppercase">
                                                {{ substr($user->nama, 0, 2) }}
                                            </div>
                                        @endif
                                        <a href="{{ route('users.show', $user) }}" class="text-sm font-bold text-slate-800 dark:text-slate-200 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $user->nama }}</a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $user->telepon ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->peran === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800/50">
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            Pengguna
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openEditModal({ id: '{{ $user->id }}', nama: '{{ addslashes($user->nama) }}', email: '{{ $user->email }}', telepon: '{{ $user->telepon }}', peran: '{{ $user->peran }}' })"
                                                class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button @click="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->nama) }}')"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
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
                    {{ $pengguna->links('vendor.pagination.tailwind') }}
                </div>
            </div>

            <!-- Modals -->
            @include('users.partials.create')
            @include('users.partials.edit')
            @include('users.partials.delete')
        </div>
    </div>
</x-app-layout>
