<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->peran === 'admin';

        $jumlahStatus = DB::table('laporan')
            ->select(
                DB::raw('COALESCE(log_terkini.status, "menunggu") as status'),
                DB::raw('COUNT(*) as total')
            )
            ->leftJoinSub(
                DB::table('log_laporan')
                    ->select('laporan_id', 'status')
                    ->whereIn('id', function ($sub) {
                        $sub->select(DB::raw('MAX(id)'))
                            ->from('log_laporan')
                            ->groupBy('laporan_id');
                    }),
                'log_terkini',
                'laporan.id',
                '=',
                'log_terkini.laporan_id'
            )
            ->when(!$isAdmin, fn($q) => $q->where('laporan.pengguna_id', $user->id))
            ->whereNull('laporan.deleted_at')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statistik = [
            'total'      => $jumlahStatus->sum(),
            'menunggu'   => $jumlahStatus->get('menunggu', 0),
            'tervalidasi' => $jumlahStatus->get('tervalidasi', 0),
            'diproses'   => $jumlahStatus->get('diproses', 0),
            'selesai'    => $jumlahStatus->get('selesai', 0),
            'ditolak'    => $jumlahStatus->get('ditolak', 0),
        ];

        $semuaStatus = $jumlahStatus->keys()->toArray();

        $laporanPerKategori = Category::select('kategori.id', 'kategori.nama')
            ->selectRaw('COUNT(laporan.id) as total')
            ->leftJoin('laporan', function ($join) use ($isAdmin, $user) {
                $join->on('kategori.id', '=', 'laporan.kategori_id')
                     ->whereNull('laporan.deleted_at');
                if (!$isAdmin) {
                    $join->where('laporan.pengguna_id', '=', $user->id);
                }
            })
            ->groupBy('kategori.id', 'kategori.nama')
            ->orderByDesc('total')
            ->get();

        $statusPerKategori = DB::table('laporan')
            ->select(
                'kategori.nama as kategori',
                DB::raw('COALESCE(log_terkini.status, "menunggu") as status'),
                DB::raw('COUNT(*) as total')
            )
            ->join('kategori', 'laporan.kategori_id', '=', 'kategori.id')
            ->leftJoinSub(
                DB::table('log_laporan')
                    ->select('laporan_id', 'status')
                    ->whereIn('id', function ($sub) {
                        $sub->select(DB::raw('MAX(id)'))
                            ->from('log_laporan')
                            ->groupBy('laporan_id');
                    }),
                'log_terkini',
                'laporan.id',
                '=',
                'log_terkini.laporan_id'
            )
            ->when(!$isAdmin, fn($q) => $q->where('laporan.pengguna_id', $user->id))
            ->whereNull('laporan.deleted_at')
            ->groupBy('kategori.nama', 'status')
            ->get();

        $totalKategori = Category::count();

        $laporanTerbaru = Report::with(['kategori', 'logLaporan' => fn($q) => $q->latest()])
            ->when(!$isAdmin, fn($q) => $q->where('pengguna_id', $user->id))
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'statistik',
            'laporanTerbaru',
            'laporanPerKategori',
            'statusPerKategori',
            'semuaStatus',
            'totalKategori'
        ));
    }
}
