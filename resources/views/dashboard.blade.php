<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Dashboard Ringkasan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Section -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -m-4 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
                <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Sistem Pelayanan Pengaduan Masyarakat {{ Auth::user()->role === 'admin' ? '(Administrator)' : '' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('report.create') }}" class="px-6 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30 hover:-translate-y-0.5">
                            Buat Laporan Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Card -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6 transition-all hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total Laporan</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h4 class="text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $stats['total'] }}</h4>
                        <span class="text-xs font-bold text-slate-400">Seluruhnya</span>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6 transition-all hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest">Pending</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h4 class="text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $stats['pending'] }}</h4>
                        <span class="text-xs font-bold text-amber-500">Butuh Review</span>
                    </div>
                </div>

                <!-- In Progress Card -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6 transition-all hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <span class="text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest">Diproses</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h4 class="text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $stats['on_progress'] }}</h4>
                        <span class="text-xs font-bold text-blue-500">Sedang Berjalan</span>
                    </div>
                </div>

                <!-- Done Card -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6 transition-all hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">Selesai</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h4 class="text-4xl font-black text-slate-800 dark:text-white leading-none">{{ $stats['done'] }}</h4>
                        <span class="text-xs font-bold text-emerald-500">Terselesaikan</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-600 rounded-full"></span>
                        Aktivitas Laporan Terbaru
                    </h4>
                    <a href="{{ route('report.index') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">Lihat Semua Laporan →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/30">
                                <th class="px-8 py-5">Subjek</th>
                                <th class="px-8 py-5">Kategori</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentReports as $report)
                            @php
                                $ls = $report->logs->first()->status ?? 'pending';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500">
                                            #{{ $report->id }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $report->title }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-black uppercase bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-500">{{ $report->category->name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($ls === 'pending')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
                                    @elseif($ls === 'validated')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Valid</span>
                                    @elseif($ls === 'on_progress')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Proses</span>
                                    @elseif($ls === 'done')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">Selesai</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('report.show', $report) }}" class="inline-flex items-center justify-center p-2 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 hover:bg-primary-100 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-slate-400 text-sm font-medium italic">Belum ada aktivitas laporan terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
