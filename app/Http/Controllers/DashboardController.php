<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $query = Report::with(['user', 'category', 'logs' => function($q) {
            $q->latest();
        }]);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $allReports = $query->get();

        $stats = [
            'total' => $allReports->count(),
            'pending' => 0,
            'validated' => 0,
            'on_progress' => 0,
            'done' => 0,
            'rejected' => 0,
        ];

        foreach ($allReports as $report) {
            $status = $report->logs->first()->status ?? 'pending';
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        $recentReports = $allReports->sortByDesc('created_at')->take(5);

        return view('dashboard', compact('stats', 'recentReports'));
    }
}
