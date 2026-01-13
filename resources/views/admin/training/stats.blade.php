@extends('layouts.admin')

@section('title', 'Training Statistics')

@section('content')
    {{-- Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Training Logs</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $totalLogs }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-database text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Actions This Month</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $recentDays->sum('count') }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Active Contributors</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $logsByUser->count() }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Actions Breakdown --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Actions Breakdown</h2>
                <p class="text-sm text-gray-500 mt-1">Distribution of training activities</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($logsByAction as $item)
                        @php
                            $percentage = $totalLogs > 0 ? ($item->count / $totalLogs) * 100 : 0;
                            $color = $item->action === 'create' ? 'green' : ($item->action === 'update' ? 'blue' : 'red');
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 capitalize">
                                    <i
                                        class="fas fa-{{ $item->action === 'create' ? 'plus' : ($item->action === 'update' ? 'edit' : 'trash') }} mr-2 text-{{ $color }}-600"></i>
                                    {{ $item->action }}
                                </span>
                                <span class="text-sm font-bold text-gray-800">{{ $item->count }}
                                    ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-{{ $color }}-500 h-3 rounded-full transition-all"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach

                    @if($logsByAction->isEmpty())
                        <p class="text-center text-gray-500 py-8">No data available</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Contributors --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Top Contributors</h2>
                <p class="text-sm text-gray-500 mt-1">Most active users in training</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($logsByUser->take(5) as $item)
                        @php
                            $percentage = $totalLogs > 0 ? ($item->count / $totalLogs) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($item->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $item->user->name ?? 'Unknown' }}
                                    </span>
                                </div>
                                <span class="text-sm font-bold text-gray-800">{{ $item->count }} actions</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach

                    @if($logsByUser->isEmpty())
                        <p class="text-center text-gray-500 py-8">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Timeline (Last 30 Days) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Activity Timeline (Last 30 Days)</h2>
            <p class="text-sm text-gray-500 mt-1">Daily training activity trends</p>
        </div>
        <div class="p-6">
            @if($recentDays->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Activities</th>
                                <th class="px-6 py-3">Visual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentDays as $day)
                                @php
                                    $maxCount = $recentDays->max('count');
                                    $barWidth = $maxCount > 0 ? ($day->count / $maxCount) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-700">
                                        {{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-800">
                                        {{ $day->count }} activities
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="w-full bg-gray-200 rounded-full h-6 flex items-center">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-6 rounded-full flex items-center justify-end pr-2 text-white text-xs font-bold transition-all"
                                                style="width: {{ $barWidth }}%; min-width: 30px;">
                                                {{ $day->count }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 py-8">No activity data for the last 30 days</p>
            @endif
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-6">
        <a href="{{ route('admin.training.index') }}"
            class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Training Monitor
        </a>
    </div>
@endsection