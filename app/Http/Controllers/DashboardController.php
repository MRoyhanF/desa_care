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
        $isAdmin = $user->role === 'admin';

        // Base report query scope
        $baseQuery = fn() => Report::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id));

        // Efficient stats: get latest log status per report via subquery
        $statusCounts = DB::table('reports')
            ->select(
                DB::raw('COALESCE(latest_logs.status, "pending") as status'),
                DB::raw('COUNT(*) as total')
            )
            ->leftJoinSub(
                DB::table('report_logs')
                    ->select('report_id', 'status')
                    ->whereIn('id', function ($sub) {
                        $sub->select(DB::raw('MAX(id)'))
                            ->from('report_logs')
                            ->groupBy('report_id');
                    }),
                'latest_logs',
                'reports.id',
                '=',
                'latest_logs.report_id'
            )
            ->when(!$isAdmin, fn($q) => $q->where('reports.user_id', $user->id))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total'       => $statusCounts->sum(),
            'pending'     => $statusCounts->get('pending', 0),
            'validated'   => $statusCounts->get('validated', 0),
            'on_progress' => $statusCounts->get('on_progress', 0),
            'done'        => $statusCounts->get('done', 0),
            'rejected'    => $statusCounts->get('rejected', 0),
        ];

        // All statuses present in data (dynamic)
        $allStatuses = $statusCounts->keys()->toArray();

        // Reports per category
        $reportsPerCategory = Category::select('categories.id', 'categories.name')
            ->selectRaw('COUNT(reports.id) as total')
            ->leftJoin('reports', function ($join) use ($isAdmin, $user) {
                $join->on('categories.id', '=', 'reports.category_id');
                if (!$isAdmin) {
                    $join->where('reports.user_id', '=', $user->id);
                }
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // Status distribution per category (for stacked chart)
        $statusPerCategory = DB::table('reports')
            ->select(
                'categories.name as category',
                DB::raw('COALESCE(latest_logs.status, "pending") as status'),
                DB::raw('COUNT(*) as total')
            )
            ->join('categories', 'reports.category_id', '=', 'categories.id')
            ->leftJoinSub(
                DB::table('report_logs')
                    ->select('report_id', 'status')
                    ->whereIn('id', function ($sub) {
                        $sub->select(DB::raw('MAX(id)'))
                            ->from('report_logs')
                            ->groupBy('report_id');
                    }),
                'latest_logs',
                'reports.id',
                '=',
                'latest_logs.report_id'
            )
            ->when(!$isAdmin, fn($q) => $q->where('reports.user_id', $user->id))
            ->groupBy('categories.name', 'status')
            ->get();

        // Total categories
        $totalCategories = Category::count();

        // Recent reports
        $recentReports = Report::with(['category', 'logs' => fn($q) => $q->latest()])
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentReports',
            'reportsPerCategory',
            'statusPerCategory',
            'allStatuses',
            'totalCategories'
        ));
    }
}
