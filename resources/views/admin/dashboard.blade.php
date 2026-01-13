@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Knowledge Base</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_knowledge'] }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-brain text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Entries</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_knowledge'] }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Training Logs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_logs'] }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('knowledge-base.index') }}"
            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <i class="fas fa-brain text-3xl mb-3"></i>
            <h3 class="text-lg font-bold">Knowledge Base</h3>
            <p class="text-sm text-blue-100 mt-1">Manage AI training data</p>
        </a>

        <a href="{{ route('admin.training.index') }}"
            class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <i class="fas fa-chart-line text-3xl mb-3"></i>
            <h3 class="text-lg font-bold">Training Monitor</h3>
            <p class="text-sm text-green-100 mt-1">View training activities</p>
        </a>

        <a href="{{ route('admin.training.stats') }}"
            class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <i class="fas fa-chart-bar text-3xl mb-3"></i>
            <h3 class="text-lg font-bold">Statistics</h3>
            <p class="text-sm text-purple-100 mt-1">View detailed stats</p>
        </a>
    </div>

    {{-- Recent Activities --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Recent Training Activities</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="px-6 py-4">Time</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentActivities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $activity->user->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($activity->action === 'create')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Create</span>
                                @elseif($activity->action === 'update')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">Update</span>
                                @elseif($activity->action === 'delete')
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Delete</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $activity->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No recent activities found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection