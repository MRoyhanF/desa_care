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
        $statusTerkini = $report->logLaporan->first()->status ?? 'menunggu';
        $statusBerikutnya = [];
        if ($statusTerkini === 'menunggu') {
            $statusBerikutnya = ['tervalidasi' => 'Validasi', 'ditolak' => 'Tolak'];
        } elseif ($statusTerkini === 'tervalidasi') {
            $statusBerikutnya = ['diproses' => 'Mulai Proses'];
        } elseif ($statusTerkini === 'diproses') {
            $statusBerikutnya = ['selesai' => 'Selesaikan'];
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

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

            <!-- Alur Status -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8">
                <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-10 flex items-center gap-2">
                    <span class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    Alur Proses Laporan
                </h4>

                <div class="relative">
                    <div class="absolute top-5 left-0 w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                    <div class="relative flex justify-between">
                        @php
                            $langkahStatus = [
                                'menunggu'   => 'Menunggu',
                                'tervalidasi' => 'Tervalidasi',
                                'diproses'   => 'Diproses',
                                'selesai'    => 'Selesai',
                            ];
                            $indeksSaat = array_search($statusTerkini, array_keys($langkahStatus));
                            if ($statusTerkini === 'ditolak') $indeksSaat = -1;
                        @endphp

                        @foreach($langkahStatus as $kunci => $label)
                            @php
                                $indeksLangkah = array_search($kunci, array_keys($langkahStatus));
                                $aktif = ($kunci === $statusTerkini);
                                $selesai = ($indeksSaat !== false && $indeksLangkah <= $indeksSaat);
                            @endphp
                            <div class="flex flex-col items-center group relative z-10 w-1/4">
                                <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center transition-all duration-500 shadow-sm
                                    @if($aktif) bg-primary-600 border-primary-200 dark:border-primary-900/50 scale-110 shadow-primary-500/50
                                    @elseif($selesai) bg-emerald-500 border-emerald-100 dark:border-emerald-900/50
                                    @else bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700
                                    @endif">
                                    @if($selesai && !$aktif)
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="text-xs font-black @if($aktif) text-white @else text-slate-400 @endif">{{ $indeksLangkah + 1 }}</span>
                                    @endif
                                </div>
                                <span class="mt-3 text-xs font-bold uppercase tracking-wider @if($aktif) text-primary-600 dark:text-primary-400 @elseif($selesai) text-emerald-600 dark:text-emerald-400 @else text-slate-400 @endif">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($statusTerkini === 'ditolak')
                <div class="mt-8 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/40 rounded-full flex items-center justify-center text-rose-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-rose-800 dark:text-rose-300 font-bold text-sm">Laporan Ditolak</p>
                        <p class="text-rose-600 dark:text-rose-400/80 text-xs">{{ $report->logLaporan->where('status', 'ditolak')->first()->catatan ?? 'Tidak ada alasan penolakan.' }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Detail Laporan -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/40 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">{{ $report->judul }}</h3>
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
                                    {{ $report->deskripsi }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Lampiran Foto</label>
                                @if(!empty($report->foto) && file_exists(public_path($report->foto)))
                                    <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800" @click="window.open('{{ asset($report->foto) }}', '_blank')">
                                        <img src="{{ asset($report->foto) }}" alt="Lampiran" class="w-full h-auto max-h-[400px] object-contain transition-all group-hover:scale-[1.02]">
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
                                @if($report->pengguna && $report->pengguna->foto && file_exists(public_path($report->pengguna->foto)))
                                    <img src="{{ asset($report->pengguna->foto) }}" alt="{{ $report->pengguna->nama }}" class="w-10 h-10 rounded-full object-cover shadow-md">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-black text-slate-600 dark:text-slate-400">
                                        {{ substr($report->pengguna->nama, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelapor</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $report->pengguna->nama }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</p>
                                <span class="inline-flex mt-1 px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 uppercase">{{ $report->kategori->nama }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Kontrol Admin -->
                <div class="space-y-8">
                    @if(Auth::user()->peran === 'admin')
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8 sticky top-8">
                        <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </span>
                            Kelola Laporan
                        </h4>

                        @if(empty($statusBerikutnya))
                            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl text-center border border-slate-100 dark:border-slate-800">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Status Sudah Final</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Status laporan ini tidak dapat diubah lagi.</p>
                            </div>
                        @else
                            <form action="{{ route('report.update', $report) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="judul" value="{{ $report->judul }}">
                                <input type="hidden" name="deskripsi" value="{{ $report->deskripsi }}">

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tindakan Cepat</label>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($statusBerikutnya as $val => $teks)
                                            <label class="relative block cursor-pointer group">
                                                <input type="radio" name="status" value="{{ $val }}" class="peer sr-only" required>
                                                <div class="p-3 border-2 border-slate-100 dark:border-slate-800 rounded-xl peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:translate-x-1 transition-transform">{{ $teks }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="catatan" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Perubahan</label>
                                    <textarea name="catatan" id="catatan" rows="2" placeholder="Berikan catatan singkat jika perlu..." class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all p-3"></textarea>
                                </div>

                                <button type="submit" class="w-full py-3 px-6 rounded-2xl bg-primary-600 text-white font-bold text-sm hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                                    Perbarui Status
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif

                    <!-- Riwayat Log -->
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8">
                        <h4 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Riwayat Perubahan
                        </h4>
                        <div class="space-y-4">
                            @forelse($report->logLaporan as $log)
                            @php
                                $warnaStatus = [
                                    'menunggu'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'tervalidasi' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'diproses'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'selesai'     => 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400',
                                    'ditolak'     => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                ];
                                $labelStatus = [
                                    'menunggu'    => 'Menunggu',
                                    'tervalidasi' => 'Tervalidasi',
                                    'diproses'    => 'Diproses',
                                    'selesai'     => 'Selesai',
                                    'ditolak'     => 'Ditolak',
                                ];
                            @endphp
                            <div class="flex gap-3">
                                <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 mt-2 shrink-0"></div>
                                <div class="flex-1 pb-4 border-b border-slate-50 dark:border-slate-800/50 last:border-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $warnaStatus[$log->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $labelStatus[$log->status] ?? ucfirst($log->status) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    @if($log->catatan)
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $log->catatan }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 mt-1">oleh {{ $log->pengguna->nama ?? 'Sistem' }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-slate-400 text-center py-4">Belum ada riwayat perubahan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
