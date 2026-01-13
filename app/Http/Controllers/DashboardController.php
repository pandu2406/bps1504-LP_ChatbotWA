<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_knowledge' => \App\Models\AiKnowledgeBase::count(),
            'active_knowledge' => \App\Models\AiKnowledgeBase::where('is_active', true)->count(),
            'total_logs' => \App\Models\TrainingLog::count(),
            'total_users' => \App\Models\User::count(),
        ];

        $recentActivities = \App\Models\TrainingLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }
}
