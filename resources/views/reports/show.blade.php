<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('Detail Laporan Pengaduan') }}
            </h2>
            <a href="{{ route('report.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition-colors font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    @php
        $latestStatus = $report->logs->first()->status ?? 'pending';
        // Status transition logic
        $allowedNextStatuses = [];
        if ($latestStatus === 'pending') {
            $allowedNextStatuses = ['validated' => 'Validasi', 'rejected' => 'Tolak'];
        } elseif ($latestStatus === 'validated') {
            $allowedNextStatuses = ['on_progress' => 'Mulai Proses'];
        } elseif ($latestStatus === 'on_progress') {
            $allowedNextStatuses = ['done' => 'Selesaikan'];
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-emerald-800 dark:text-emerald-300 font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800">
                    <ul class="list-disc list-inside text-rose-800 dark:text-rose-300 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Pipeline Section (Horizontal) -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8">
                <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-10 flex items-center gap-2">
                    <span class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    Proses Laporan
                </h4>

                <div class="relative">
                    <!-- Progress Line Backdrop -->
                    <div class="absolute top-5 left-0 w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                    
                    <div class="relative flex justify-between">
                        @php
                            $statusSteps = [
                                'pending' => 'Pending',
                                'validated' => 'Validasi',
                                'on_progress' => 'Proses',
                                'done' => 'Selesai'
                            ];
                            // Determine current step index
                            $currentIndex = array_search($latestStatus, array_keys($statusSteps));
                            if ($latestStatus === 'rejected') $currentIndex = -1; // Special case
                        @endphp

                        @foreach($statusSteps as $key => $label)
                            @php
                                $stepIndex = array_search($key, array_keys($statusSteps));
                                $isActive = ($key === $latestStatus);
                                $isCompleted = ($currentIndex !== false && $stepIndex <= $currentIndex);
                            @endphp
                            <div class="flex flex-col items-center group relative z-10 w-1/4">
                                <!-- Step Circle -->
                                <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center transition-all duration-500 shadow-sm
                                    @if($isActive) bg-primary-600 border-primary-200 dark:border-primary-900/50 scale-110 shadow-primary-500/50
                                    @elseif($isCompleted) bg-emerald-500 border-emerald-100 dark:border-emerald-900/50
                                    @else bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700
                                    @endif">
                                    @if($isCompleted && !$isActive)
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="text-xs font-black @if($isActive) text-white @else text-slate-400 @endif">{{ $stepIndex + 1 }}</span>
                                    @endif
                                </div>
                                <span class="mt-3 text-xs font-bold uppercase tracking-wider @if($isActive) text-primary-600 dark:text-primary-400 @elseif($isCompleted) text-emerald-600 dark:text-emerald-400 @else text-slate-400 @endif">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($latestStatus === 'rejected')
                <div class="mt-8 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/40 rounded-full flex items-center justify-center text-rose-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-rose-800 dark:text-rose-300 font-bold text-sm">Laporan Ditolak</p>
                        <p class="text-rose-600 dark:text-rose-400/80 text-xs">{{ $report->logs->where('status', 'rejected')->first()->note ?? 'Tidak ada alasan penolakan.' }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Report Details -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/40 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">{{ $report->title }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-widest bg-primary-50 dark:bg-primary-900/30 px-2 py-0.5 rounded">#{{ $report->id }}</span>
                                    <span class="text-slate-300 dark:text-slate-700">•</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $report->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Isi Laporan</label>
                                <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl text-slate-700 dark:text-slate-300 leading-relaxed border border-slate-100 dark:border-slate-800">
                                    {{ $report->description }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Lampiran Foto</label>
                                @if(!empty($report->photo) && file_exists(public_path($report->photo)))
                                    <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800" @click="window.open('{{ asset($report->photo) }}', '_blank')">
                                        <img src="{{ asset($report->photo) }}" alt="Lampiran" class="w-full h-auto max-h-[400px] object-contain transition-all group-hover:scale-[1.02]">
                                        <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                            <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-full border border-white/30 text-white font-bold text-sm">Klik untuk Perbesar</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-10 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 dark:bg-slate-800/20">
                                        <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-medium">Tidak ada file lampiran</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($report->user && $report->user->photo && file_exists(public_path($report->user->photo)))
                                    <img src="{{ asset($report->user->photo) }}" alt="{{ $report->user->name }}" class="w-10 h-10 rounded-full object-cover shadow-md">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-black text-slate-600 dark:text-slate-400">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelapor</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $report->user->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</p>
                                <span class="inline-flex mt-1 px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase">{{ $report->category->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Admin Management -->
                <div class="space-y-8">
                    @if(Auth::user()->role === 'admin')
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8 sticky top-8">
                        <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </span>
                            Kelola Laporan
                        </h4>

                        @if(empty($allowedNextStatuses))
                            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl text-center border border-slate-100 dark:border-slate-800">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Laporan Selesai</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Status laporan ini sudah final dan tidak dapat diubah lagi.</p>
                            </div>
                        @else
                            <form action="{{ route('report.update', $report) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <!-- Hidden current info for compatibility -->
                                <input type="hidden" name="title" value="{{ $report->title }}">
                                <input type="hidden" name="description" value="{{ $report->description }}">

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tindakan Cepat</label>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($allowedNextStatuses as $val => $text)
                                            <label class="relative block cursor-pointer group">
                                                <input type="radio" name="status" value="{{ $val }}" class="peer sr-only" required>
                                                <div class="p-3 border-2 border-slate-100 dark:border-slate-800 rounded-xl peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:translate-x-1 transition-transform">{{ $text }}</span>
                                                        <div class="w-4 h-4 rounded-full border-2 border-slate-300 dark:border-slate-700 peer-checked:border-primary-500 flex items-center justify-center">
                                                            <div class="w-2 h-2 rounded-full bg-primary-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="note" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Perubahan</label>
                                    <textarea name="note" id="note" rows="2" placeholder="Berikan catatan singkat jika perlu..." class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all relative z-20"></textarea>
                                </div>

                                <button type="submit" class="w-full bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:opacity-90 transition-all shadow-xl shadow-slate-900/10 active:scale-[0.98]">
                                    Perbarui Status
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif

                    <!-- History Log -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8 overflow-hidden">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-6 uppercase tracking-widest">Riwayat Aktivitas</h4>
                        <div class="space-y-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($report->logs as $log)
                                <div class="relative pl-6 pb-6 last:pb-0">
                                    @if(!$loop->last)
                                        <div class="absolute left-[3px] top-4 bottom-0 w-[1px] bg-slate-100 dark:bg-slate-800"></div>
                                    @endif
                                    <div class="absolute left-0 top-1.5 w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9px] font-black uppercase tracking-tighter
                                                @if($log->status === 'done') text-primary-500
                                                @elseif($log->status === 'rejected') text-rose-500
                                                @else text-slate-500
                                                @endif">
                                                {{ strtoupper($log->status) }}
                                            </span>
                                            <span class="text-[8px] font-medium text-slate-400">{{ $log->created_at->format('d/m/y H:i') }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300 leading-tight">
                                            {{ $log->note }}
                                        </p>
                                        @if($log->user)
                                            <p class="text-[8px] text-slate-400 italic">Oleh {{ $log->user->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
    </style>
</x-app-layout>
