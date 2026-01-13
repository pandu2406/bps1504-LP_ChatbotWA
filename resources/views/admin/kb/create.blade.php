@extends('layouts.admin')

@section('title', 'Tambah Knowledge')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Tambah Data Baru ke AI</h2>

            <form action="{{ route('knowledge-base.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Pertanyaan (User Query)</label>
                    <input type="text" name="question" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500"
                        placeholder="Contoh: Siapa kepala kantor BPS?">
                    <p class="text-xs text-gray-500 mt-1">Simulasi pertanyaan yang mungkin diajukan user.</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Keywords (Kata Kunci)</label>
                    <input type="text" name="keywords"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500"
                        placeholder="Contoh: kepala kantor bps batang hari">
                    <p class="text-xs text-gray-500 mt-1">Kata kunci untuk pencarian database (pisahkan dengan spasi).</p>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 font-bold mb-2">Jawaban (AI Context)</label>
                    <textarea name="answer" rows="5" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500"
                        placeholder="Masukkan jawaban yang benar..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Jawaban ini akan disuntikkan ke otak AI sebagai 'Fakta'.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('knowledge-base.index') }}"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-colors">Batal</a>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-lg">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection