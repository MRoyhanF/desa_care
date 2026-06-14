<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Dashboard Ringkasan') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Section -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -m-4 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
                <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <img src="{{ asset('images/logoMJ.png') }}" alt="Logo Desa" class="w-16 h-16 object-contain">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Sistem Pelayanan Pengaduan Masyarakat {{ Auth::user()->role === 'admin' ? '(Administrator)' : '' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        @if(Auth::user()->role === 'user')
                        <a href="{{ route('report.create') }}" class="px-6 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30 hover:-translate-y-0.5">
                            Buat Laporan Baru
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $cards = [
                        ['label' => 'Total Laporan',  'value' => $statistik['total'],       'color' => 'slate',   'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['label' => 'Kategori',       'value' => $totalKategori,             'color' => 'violet',  'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                        ['label' => 'Menunggu',       'value' => $statistik['menunggu'],    'color' => 'amber',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Diproses',       'value' => $statistik['diproses'],    'color' => 'blue',    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                        ['label' => 'Selesai',        'value' => $statistik['selesai'],     'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Ditolak',        'value' => $statistik['ditolak'],     'color' => 'rose',    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-2xl shadow-lg p-5 transition-all hover:scale-[1.03]">
                    <div class="w-10 h-10 bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-slate-800 dark:text-white leading-none mb-1">{{ $card['value'] }}</p>
                    <p class="text-[10px] font-black uppercase tracking-widest text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            @if($statistik['total'] > 0)
            <!-- Charts Row 1: Per Category + Status Distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Chart 1: Reports per Category (Bar) -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6">
                    <h4 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-6">
                        <span class="w-1 h-5 bg-primary-600 rounded-full"></span>
                        Jumlah Laporan per Kategori
                    </h4>
                    @if($laporanPerKategori->where('total', '>', 0)->count() > 0)
                    <div class="relative" style="height:280px">
                        <canvas id="chartPerCategory"></canvas>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                        <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <p class="text-sm font-medium">Belum ada data kategori</p>
                    </div>
                    @endif
                </div>

                <!-- Chart 2: Overall Status Distribution (Doughnut) -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6">
                    <h4 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-6">
                        <span class="w-1 h-5 bg-violet-600 rounded-full"></span>
                        Distribusi Status Seluruh Laporan
                    </h4>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="relative flex-shrink-0" style="height:220px;width:220px">
                            <canvas id="chartStatusDoughnut"></canvas>
                        </div>
                        <!-- Legend -->
                        <div class="flex flex-col gap-2 w-full">
                            @php
                                $statusMeta = [
                                    'menunggu'    => ['label' => 'Menunggu',    'color' => '#f59e0b'],
                                    'tervalidasi' => ['label' => 'Tervalidasi', 'color' => '#10b981'],
                                    'diproses'    => ['label' => 'Diproses',    'color' => '#3b82f6'],
                                    'selesai'     => ['label' => 'Selesai',     'color' => '#22c55e'],
                                    'ditolak'     => ['label' => 'Ditolak',     'color' => '#f43f5e'],
                                ];
                            @endphp
                            @foreach($semuaStatus as $status)
                            @php $meta = $statusMeta[$status] ?? ['label' => ucfirst($status), 'color' => '#94a3b8']; @endphp
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $meta['color'] }}"></span>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $meta['label'] }}</span>
                                </div>
                                <span class="text-xs font-black text-slate-800 dark:text-white">{{ $statistik[$status] ?? 0 }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Status per Category (Stacked Bar) -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-6">
                <h4 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 mb-6">
                    <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
                    Distribusi Status per Kategori
                </h4>
                @if($statusPerKategori->count() > 0)
                <div class="relative" style="height:320px">
                    <canvas id="chartStatusPerCategory"></canvas>
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-48 text-slate-400">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <p class="text-sm font-medium">Belum ada data</p>
                </div>
                @endif
            </div>
            @else
            <!-- Empty state when no reports at all -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-100 dark:border-slate-800 rounded-3xl shadow-xl p-16 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-slate-500 dark:text-slate-400 font-semibold text-lg">Belum ada laporan</p>
                <p class="text-slate-400 text-sm mt-1">Statistik akan ditampilkan setelah laporan pertama dibuat.</p>
            </div>
            @endif

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
                            @forelse($laporanTerbaru as $report)
                            @php $ls = $report->logLaporan->first()->status ?? 'menunggu'; @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500">#{{ $report->id }}</div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $report->judul }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-black uppercase bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-slate-500">{{ $report->kategori->nama }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $statusBadge = [
                                            'menunggu'    => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',     'Menunggu'],
                                            'tervalidasi' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Tervalidasi'],
                                            'diproses'    => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',             'Diproses'],
                                            'selesai'     => ['bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400', 'Selesai'],
                                            'ditolak'     => ['bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',             'Ditolak'],
                                        ];
                                        [$badgeClass, $badgeLabel] = $statusBadge[$ls] ?? ['bg-slate-100 text-slate-700', ucfirst($ls)];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $badgeClass }}">{{ $badgeLabel }}</span>
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

    @if($statistik['total'] > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const isDark = () => document.documentElement.classList.contains('dark');
        const gridColor = () => isDark() ? 'rgba(148,163,184,0.1)' : 'rgba(148,163,184,0.2)';
        const textColor = () => isDark() ? '#94a3b8' : '#64748b';

        const STATUS_COLORS = {
            menunggu:    '#f59e0b',
            tervalidasi: '#10b981',
            diproses:    '#3b82f6',
            selesai:     '#22c55e',
            ditolak:     '#f43f5e',
        };
        const STATUS_LABELS = {
            menunggu:    'Menunggu',
            tervalidasi: 'Tervalidasi',
            diproses:    'Diproses',
            selesai:     'Selesai',
            ditolak:     'Ditolak',
        };

        // ── Chart 1: Reports per Category ──────────────────────────────
        @if($laporanPerKategori->where('total', '>', 0)->count() > 0)
        const catLabels = @json($laporanPerKategori->pluck('nama'));
        const catTotals = @json($laporanPerKategori->pluck('total'));

        const palette = ['#22c55e','#3b82f6','#f59e0b','#f43f5e','#8b5cf6','#06b6d4','#ec4899','#14b8a6','#f97316','#a3e635'];

        new Chart(document.getElementById('chartPerCategory'), {
            type: 'bar',
            data: {
                labels: catLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: catTotals,
                    backgroundColor: catLabels.map((_, i) => palette[i % palette.length] + 'cc'),
                    borderColor: catLabels.map((_, i) => palette[i % palette.length]),
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} laporan`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor() },
                        ticks: { color: textColor(), font: { size: 11, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor() },
                        ticks: { color: textColor(), precision: 0 }
                    }
                }
            }
        });
        @endif

        // ── Chart 2: Overall Status Doughnut ───────────────────────────
        @php
            $doughnutStatuses = array_filter(array_keys($statusMeta ?? [
                'menunggu'=>1,'tervalidasi'=>1,'diproses'=>1,'selesai'=>1,'ditolak'=>1
            ]), fn($s) => ($statistik[$s] ?? 0) > 0);
        @endphp
        @if(count($doughnutStatuses) > 0)
        const dStatuses = @json(array_values($doughnutStatuses));
        const dValues   = @json(array_map(fn($s) => $statistik[$s] ?? 0, array_values($doughnutStatuses)));

        new Chart(document.getElementById('chartStatusDoughnut'), {
            type: 'doughnut',
            data: {
                labels: dStatuses.map(s => STATUS_LABELS[s] ?? s),
                datasets: [{
                    data: dValues,
                    backgroundColor: dStatuses.map(s => (STATUS_COLORS[s] ?? '#94a3b8') + 'dd'),
                    borderColor: dStatuses.map(s => STATUS_COLORS[s] ?? '#94a3b8'),
                    borderWidth: 2,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed} laporan`
                        }
                    }
                }
            }
        });
        @endif

        // ── Chart 3: Status per Category (Stacked Bar) ─────────────────
        @if($statusPerKategori->count() > 0)
        const rawData = @json($statusPerKategori);
        const catSet = [...new Set(rawData.map(r => r.kategori))];
        const statusSet = [...new Set(rawData.map(r => r.status))];
        const stackedDatasets = statusSet.map(status => {
            return {
                label: STATUS_LABELS[status] ?? status,
                data: catSet.map(cat => {
                    const found = rawData.find(r => r.kategori === cat && r.status === status);
                    return found ? found.total : 0;
                }),
                backgroundColor: (STATUS_COLORS[status] ?? '#94a3b8') + 'cc',
                borderColor: STATUS_COLORS[status] ?? '#94a3b8',
                borderWidth: 2,
                borderRadius: 4,
                borderSkipped: false,
            };
        });

        new Chart(document.getElementById('chartStatusPerCategory'), {
            type: 'bar',
            data: { labels: catSet, datasets: stackedDatasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: textColor(),
                            font: { size: 11, weight: '600' },
                            boxWidth: 12,
                            borderRadius: 4,
                            useBorderRadius: true,
                            padding: 16,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} laporan`
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { color: gridColor() },
                        ticks: { color: textColor(), font: { size: 11, weight: '600' } }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: gridColor() },
                        ticks: { color: textColor(), precision: 0 }
                    }
                }
            }
        });
        @endif
    })();
    </script>
    @endif
</x-app-layout>
