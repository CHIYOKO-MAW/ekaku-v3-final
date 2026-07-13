@extends('layouts.app')

@section('title', 'Verifikasi Admin')

@section('content')
<div class="page-wrap" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">
  {{-- SKELETON --}}
  <div x-show="!loaded" class="space-y-6">
    <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
      <x-skeleton type="title" class="w-1/3" />
      <x-skeleton type="text-sm" class="mt-2 w-1/2" />
    </div>
    <div class="rounded-xl bg-gray-200 card-shadow animate-pulse">
      <div class="p-4 space-y-3">
        @foreach([1,2,3,4] as $i)
        <div class="flex items-center gap-4 rounded-lg bg-gray-300 p-4 animate-pulse">
          <x-skeleton type="text-sm" class="w-8" />
          <x-skeleton type="text" class="w-1/4" />
          <x-skeleton type="text-sm" class="w-1/4" />
          <x-skeleton type="badge" />
          <x-skeleton type="btn" />
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- REAL CONTENT --}}
  <div x-show="loaded" x-cloak class="space-y-6">
  <div class="mb-6 rounded-xl bg-white p-6 card-shadow">
    <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pengajuan</h1>
    <p class="mt-1 text-sm text-gray-600">Validasi biodata pengguna sebelum akses cetak kartu kuning.</p>
  </div>

    <div class="overflow-hidden rounded-xl bg-white card-shadow">
    <div class="table-scroll-wrapper">
      {{-- Desktop table --}}
      <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">NIK</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($datas as $data)
            <tr>
              <td class="px-4 py-3 text-gray-700">{{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}</td>
              <td class="px-4 py-3 font-medium text-gray-900">{{ $data->user->name }}</td>
              <td class="px-4 py-3 text-gray-700">{{ $data->nik }}</td>
              <td class="px-4 py-3"><span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">{{ strtoupper($data->status_verifikasi) }}</span></td>
              <td class="px-4 py-3"><a href="{{ route('admin.verifikasi.show', $data) }}" class="btn-secondary">Detail</a></td>
            </tr>
          @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500"><i class="fas fa-check-circle mr-2"></i>Tidak ada data verifikasi pending.</td></tr>
          @endforelse
        </tbody>
      </table>
      {{-- Mobile cards --}}
      <div class="sm:hidden space-y-3 p-4">
        @forelse($datas as $data)
          <x-mobile-card
              :title="$data->user->name"
              :subtitle="'NIK: ' . $data->nik"
              status="yellow"
              :badge="strtoupper($data->status_verifikasi)"
              badgeColor="yellow"
          >
              <div class="mobile-card-field">
                  <i class="fas fa-id-card mobile-card-field-icon"></i>
                  <span class="mobile-card-field-label">NIK</span>
                  <span class="mobile-card-field-value">{{ $data->nik }}</span>
              </div>
              <x-slot:actions>
                  <a href="{{ route('admin.verifikasi.show', $data) }}" class="btn-secondary text-xs">Detail</a>
              </x-slot:actions>
          </x-mobile-card>
        @empty
          <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-check-circle mr-2"></i>Tidak ada data verifikasi pending.</div>
        @endforelse
      </div>
    </div>
    <div class="border-t border-gray-100 px-4 py-3">{{ $datas->links() }}</div>
  </div>
  </div>
</div>
@endsection
