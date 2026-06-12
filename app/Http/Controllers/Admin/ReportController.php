<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $cari = $request->input('cari');
        $perHalaman = $request->input('per_halaman', 5);

        $query = Report::with(['pengguna', 'kategori', 'logLaporan' => function ($q) {
            $q->latest();
        }]);

        if ($user->peran !== 'admin') {
            $query->where('pengguna_id', $user->id);
        }

        $laporan = $query->when($cari, function ($q, $cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            })
            ->latest()
            ->paginate($perHalaman)
            ->onEachSide(1)
            ->withQueryString();

        $kategori = Category::all();

        return view('reports.page', compact('laporan', 'cari', 'perHalaman', 'kategori'));
    }

    public function create()
    {
        $kategori = Category::all();
        return view('reports.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists'   => 'Kategori tidak valid.',
            'judul.required'       => 'Judul laporan wajib diisi.',
            'deskripsi.required'   => 'Deskripsi laporan wajib diisi.',
            'foto.image'           => 'File harus berupa gambar.',
            'foto.mimes'           => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max'             => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('laporan'), $filename);
                $fotoPath = 'laporan/' . $filename;
            }

            $laporan = Report::create([
                'pengguna_id' => Auth::id(),
                'kategori_id' => $validated['kategori_id'],
                'judul'       => $validated['judul'],
                'deskripsi'   => $validated['deskripsi'],
                'foto'        => $fotoPath,
            ]);

            ReportLog::create([
                'laporan_id'     => $laporan->id,
                'status'         => 'menunggu',
                'catatan'        => 'Pendataan laporan awal.',
                'diperbarui_oleh' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('report.index')->with('success', 'Laporan berhasil dikirim dan sedang dalam peninjauan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan laporan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Report $report)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin' && $report->pengguna_id !== $user->id) {
            abort(403);
        }

        $report->load(['pengguna', 'kategori', 'logLaporan' => function ($q) {
            $q->latest();
        }]);

        return view('reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $user = Auth::user();

        if ($user->peran !== 'admin' && $report->pengguna_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status'    => 'required|in:menunggu,tervalidasi,ditolak,diproses,selesai',
            'catatan'   => 'nullable|string',
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'status.required'    => 'Status wajib dipilih.',
            'status.in'          => 'Status tidak valid.',
            'judul.required'     => 'Judul laporan wajib diisi.',
            'deskripsi.required' => 'Deskripsi laporan wajib diisi.',
        ]);

        $statusTerkini = $report->logLaporan()->latest()->first()->status ?? 'menunggu';
        $statusBaru = $validated['status'];

        if ($statusTerkini !== $statusBaru) {
            $allowed = false;
            if ($statusTerkini === 'menunggu'   && in_array($statusBaru, ['tervalidasi', 'ditolak'])) $allowed = true;
            if ($statusTerkini === 'tervalidasi' && $statusBaru === 'diproses')                       $allowed = true;
            if ($statusTerkini === 'diproses'    && $statusBaru === 'selesai')                        $allowed = true;

            if (!$allowed && $user->peran === 'admin') {
                return back()->withErrors(['status' => "Transisi status dari '{$statusTerkini}' ke '{$statusBaru}' tidak diperbolehkan."]);
            }
        }

        try {
            DB::beginTransaction();

            $report->update([
                'judul'     => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
            ]);

            if ($request->filled('status') && $user->peran === 'admin' && $statusTerkini !== $statusBaru) {
                ReportLog::create([
                    'laporan_id'      => $report->id,
                    'status'          => $validated['status'],
                    'catatan'         => $validated['catatan'] ?? 'Status diperbarui oleh Admin.',
                    'diperbarui_oleh' => $user->id,
                ]);
            }

            DB::commit();
            return redirect()->route('report.show', $report)->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui laporan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Report $report)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin' && $report->pengguna_id !== $user->id) {
            abort(403);
        }

        $report->delete();
        return redirect()->route('report.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
