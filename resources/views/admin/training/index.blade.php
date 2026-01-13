@extends('layouts.admin')

@section('title', 'Training Monitor')

@section('content')
    {{-- Filter Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Training Logs</h3>
        <form method="GET" action="{{ route('admin.training.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="action"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Actions</option>
                    <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="md:col-span-4 flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
                <a href="{{ route('admin.training.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
                <a href="{{ route('admin.training.stats') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors ml-auto">
                    <i class="fas fa-chart-bar mr-2"></i>View Statistics
                </a>
            </div>
        </form>
    </div>

    {{-- Training Logs Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Training Activity Logs</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor all AI training activities and changes</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                <div>{{ $log->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->action === 'create')
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                        <i class="fas fa-plus mr-1"></i>Create
                                    </span>
                                @elseif($log->action === 'update')
                                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                        <i class="fas fa-edit mr-1"></i>Update
                                    </span>
                                @elseif($log->action === 'delete')
                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($log->metadata)
                                    <button onclick="showMetadata({{ json_encode($log->metadata) }})"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-info-circle mr-1"></i>View
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No training logs found. Try adjusting your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- Metadata Modal (Simple Alert) --}}
    <script>
        function showMetadata(metadata) {
            let text = 'Metadata:\n\n';
            for (let key in metadata) {
                text += key + ': ' + metadata[key] + '\n';
            }
            alert(text);
        }
    </script>
@endsection