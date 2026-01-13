<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingMonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\TrainingLog::with('user');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = \App\Models\User::all();

        return view('admin.training.index', compact('logs', 'users'));
    }

    public function stats()
    {
        $totalLogs = \App\Models\TrainingLog::count();
        $logsByAction = \App\Models\TrainingLog::selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->get();

        $logsByUser = \App\Models\TrainingLog::with('user')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->get();

        $recentDays = \App\Models\TrainingLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.training.stats', compact('totalLogs', 'logsByAction', 'logsByUser', 'recentDays'));
    }
}
