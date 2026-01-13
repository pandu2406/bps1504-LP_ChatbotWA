@extends('layouts.admin')

@section('title', 'Knowledge Base AI')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Kelola data pengetahuan tambahan untuk AI Chatbot.</p>
        @if(Auth::user()->canEdit())
            <a href="{{ route('knowledge-base.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-bold shadow-md transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                    <th class="px-6 py-4">Pertanyaan (User Query)</th>
                    <th class="px-6 py-4">Jawaban (AI Response)</th>
                    <th class="px-6 py-4">Keywords</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->question }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate">{{ $item->answer }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            @if($item->keywords)
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded">{{ $item->keywords }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            @if(Auth::user()->canEdit())
                                <a href="{{ route('knowledge-base.edit', $item->id) }}"
                                    class="text-yellow-500 hover:text-yellow-600">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if(Auth::user()->canDelete())
                                <form action="{{ route('knowledge-base.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data ini? AI akan melupakannya.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                            @if(!Auth::user()->canEdit() && !Auth::user()->canDelete())
                                <span class="text-gray-400 text-sm">View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data knowledge base. Silakan tambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>
@endsection