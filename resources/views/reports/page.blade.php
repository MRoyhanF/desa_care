<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Manajemen Laporan Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        isModalOpen: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        isEditModalOpen: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }},
        isDeleteModalOpen: false,
        editData: {
            id: '{{ old('id') }}',
            title: '{{ old('title') }}',
            description: '{{ old('description') }}',
            category_id: '{{ old('category_id') }}',
            category_name: '',
            user_name: '',
            status: '',
            photo: '',
            logs: []
        },
        deleteData: {
            id: '',
            name: ''
        },
        openEditModal(report) {
            this.editData = { 
                id: report.id,
                title: report.title,
                description: report.description,
                category_id: report.category_id,
                category_name: report.category ? report.category.name : '-',
                user_name: report.user ? report.user.name : '-',
                status: report.logs.length > 0 ? report.logs[0].status : 'pending',
                photo: report.photo,
                logs: report.logs
            };
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
                <!-- Header Card -->
                <div class="p-6 sm:p-8 flex items-center gap-4 w-full lg:w-auto justify-between border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/40 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200">Daftar Laporan</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan {{ $reports->total() }} laporan masyarakat</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('report.create') }}" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-primary-500/30 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">Buat Laporan Baru</span>
                    </a>
                </div>

                <!-- Search & Filters -->
                <div class="p-6 sm:p-8 flex flex-col lg:flex-row justify-end items-start lg:items-center gap-4 border-b border-slate-100 dark:border-slate-800">
                    <form method="GET" action="{{ route('report.index') }}" class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-3">
                        <!-- Items per page -->
                        <div class="relative w-full sm:w-auto">
                            <select name="per_page" onchange="this.form.submit()" class="bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-full focus:ring-2 focus:ring-primary-500 focus:border-primary-500 block w-full pl-4 pr-10 py-2.5 dark:text-slate-300 shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 / hal</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari laporan..." 
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
                                <th class="px-6 py-5">Pelapor</th>
                                <th class="px-6 py-5">Judul Laporan</th>
                                <th class="px-6 py-5">Kategori</th>
                                <th class="px-6 py-5">Status</th>
                                <th class="px-6 py-5">Tanggal</th>
                                <th class="px-6 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($reports as $report)
                            @php
                                $latestStatus = $report->logs->first()->status ?? 'pending';
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">#{{ $report->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($report->user && $report->user->photo && file_exists(public_path($report->user->photo)))
                                            <img src="{{ asset($report->user->photo) }}" alt="{{ $report->user->name }}" class="h-7 w-7 rounded-full object-cover shadow-sm">
                                        @else
                                            <div class="h-7 w-7 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold uppercase text-slate-600 dark:text-slate-400">
                                                {{ substr($report->user->name ?? '?', 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $report->user->name ?? 'Anonim' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $report->title }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[200px]">{{ Str::limit($report->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        {{ $report->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($latestStatus === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
                                    @elseif($latestStatus === 'validated')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Valid</span>
                                    @elseif($latestStatus === 'on_progress')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Proses</span>
                                    @elseif($latestStatus === 'done')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">Selesai</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $report->created_at->format('d/m/Y') }}
                                </td>
                                 <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('report.show', $report) }}" 
                                           class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-colors" title="Lihat Detail & Pipeline">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                        @if(Auth::user()->role === 'admin' || $latestStatus === 'pending')
                                        <button @click="openDeleteModal('{{ $report->id }}', '{{ $report->title }}')" 
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm">Tidak ada laporan ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reports->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 w-full flex-col items-center">
                    {{ $reports->links('vendor.pagination.tailwind') }}
                </div>
                @endif
            </div>

            <!-- Modals -->
            @include('reports.partials.edit')
            @include('reports.partials.delete')
        </div>
    </div>
</x-app-layout>
