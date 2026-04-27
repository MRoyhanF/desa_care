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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('reports.create', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = Report::with(['user', 'category', 'logs' => function($q) {
            $q->latest();
        }]);

        // If not admin, only show own reports
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $reports = $query->when($search, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->onEachSide(1)
            ->withQueryString();

        $categories = Category::all();

        return view('reports.page', compact('reports', 'search', 'perPage', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('reports'), $filename);
                $photoPath = 'reports/' . $filename;
            }

            $report = Report::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'photo' => $photoPath,
            ]);

            // Create default log
            ReportLog::create([
                'report_id' => $report->id,
                'status' => 'pending',
                'note' => 'Pendataan laporan awal',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('report.index')->with('success', 'Laporan berhasil dikirim dan sedang dalam peninjauan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan laporan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403);
        }

        $report->load(['user', 'category', 'logs' => function($q) {
            $q->latest();
        }]);

        return view('reports.show', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        // Only admin can update status (via log) or title/desc
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,validated,rejected,on_progress,done',
            'note' => 'nullable|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Status transition logic
        $latestStatus = $report->logs()->latest()->first()->status ?? 'pending';
        $newStatus = $validated['status'];

        if ($latestStatus !== $newStatus) {
            $allowed = false;
            if ($latestStatus === 'pending' && in_array($newStatus, ['validated', 'rejected'])) $allowed = true;
            if ($latestStatus === 'validated' && $newStatus === 'on_progress') $allowed = true;
            if ($latestStatus === 'on_progress' && $newStatus === 'done') $allowed = true;

            if (!$allowed && $user->role === 'admin') {
                return back()->withErrors(['status' => "Transisi status dari {$latestStatus} ke {$newStatus} tidak diperbolehkan."]);
            }
        }

        try {
            DB::beginTransaction();

            $report->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            if ($request->filled('status') && $user->role === 'admin' && $latestStatus !== $newStatus) {
                ReportLog::create([
                    'report_id' => $report->id,
                    'status' => $validated['status'],
                    'note' => $validated['note'] ?? 'Status diperbarui oleh Admin',
                    'updated_by' => $user->id,
                ]);
            }

            DB::commit();
            return redirect()->route('report.show', $report)->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui laporan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $report->user_id !== $user->id) {
            abort(403);
        }

        $report->delete();
        return redirect()->route('report.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
