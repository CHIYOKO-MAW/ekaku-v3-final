@extends('layouts.app')

@section('title', 'Data Biodata')

@section('content')
<div class="page-wrap">
    <div class="mb-6 rounded-xl bg-white p-6 card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Biodata</h1>
                <p class="mt-1 text-sm text-gray-600">Daftar biodata pengguna yang telah diisi.</p>
            </div>
            <a href="{{ route('biodata.create') }}" class="btn-primary"><i class="fas fa-plus mr-2"></i>Tambah Biodata</a>
        </div>
    </div>

    <div class="table-scroll-wrapper rounded-xl bg-white card-shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">NIK</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($datas as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-700">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->nik }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->status_verifikasi }}</td>
                        <td class="px-4 py-3">
                            <a class="font-medium text-disnaker-600 hover:text-disnaker-700" href="{{ route('biodata.show', $item->id) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-12 text-center text-gray-500" colspan="4"><i class="fas fa-database mr-2"></i>Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-4 py-3">{{ $datas->links() }}</div>
    </div>
</div>
@endsection
