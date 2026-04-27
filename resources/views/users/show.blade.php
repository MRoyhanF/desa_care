<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="p-2 text-slate-400 hover:text-primary-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('Detail Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- User Profile Card -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Profile Photo -->
                        <div class="shrink-0">
                            @if($user->photo && file_exists(public_path($user->photo)))
                                <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="h-32 w-32 object-cover rounded-3xl border-4 border-slate-50 dark:border-slate-800 shadow-2xl">
                            @else
                                <div class="h-32 w-32 rounded-3xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center border-4 border-slate-50 dark:border-slate-800 shadow-2xl">
                                    <span class="text-5xl font-black text-primary-600 dark:text-primary-400 capitalize">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 space-y-6">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $user->name }}</h3>
                                    @if($user->role === 'admin')
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800/50">Petugas</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">Warga</span>
                                    @endif
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Email</label>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Telepon</label>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Laporan</label>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->reports->count() }} Laporan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Reports History -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-600 rounded-full"></span>
                        Riwayat Laporan Pengguna
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/30 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-8 py-5">Subjek</th>
                                <th class="px-8 py-5">Kategori</th>
                                <th class="px-8 py-5">Status Terakhir</th>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($user->reports->sortByDesc('created_at') as $report)
                            @php
                                $ls = $report->logs->first()->status ?? 'pending';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all">
                                <td class="px-8 py-5">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $report->title }}</p>
                                        <p class="text-[10px] text-slate-400">ID: #{{ $report->id }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-black uppercase px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">{{ $report->category->name }}</span>
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
                                <td class="px-8 py-5 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    {{ $report->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('report.show', $report) }}" class="inline-flex items-center justify-center p-2 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 hover:bg-primary-100 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400 text-sm font-medium italic">Pengguna ini belum pernah membuat laporan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
