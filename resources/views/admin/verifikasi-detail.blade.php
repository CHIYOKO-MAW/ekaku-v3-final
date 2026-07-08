@extends('layouts.app')

@section('title', 'Detail Verifikasi')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8" x-data="{
    showApprove: false,
    showReject: false,
    rejectCatatan: '',
    loadingApprove: false,
    loadingReject: false
}">
  <x-breadcrumb :items="[
      ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
      ['label' => 'Verifikasi', 'url' => route('admin.verifikasi.index')],
      ['label' => 'Detail Biodata']
  ]" />

  <div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Detail Verifikasi</h1>
    <a href="{{ route('admin.verifikasi.index') }}" class="btn-secondary">Kembali</a>
  </div>

  <div class="rounded-xl bg-white p-6 card-shadow">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
      <div><span class="font-semibold">Nama:</span> {{ $biodata->user?->name ?? '-' }}</div>
      <div><span class="font-semibold">NIK:</span> {{ $biodata->nik }}</div>
      <div><span class="font-semibold">Tempat Lahir:</span> {{ $biodata->tempat_lahir }}</div>
      <div><span class="font-semibold">Tanggal Lahir:</span> {{ optional($biodata->tanggal_lahir)->format('d M Y') ?? $biodata->tanggal_lahir }}</div>
      <div><span class="font-semibold">Pendidikan:</span> {{ $biodata->pendidikan }}</div>
      <div class="sm:col-span-2"><span class="font-semibold">Alamat:</span> {{ $biodata->alamat }}</div>
      <div class="sm:col-span-2"><span class="font-semibold">Keahlian:</span> {{ $biodata->keahlian ?: '-' }}</div>
    </div>

    <h2 class="mt-8 text-lg font-semibold text-gray-900">Dokumen Upload</h2>
    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
      @forelse($biodata->user?->uploads ?? [] as $upload)
        @php $ext = pathinfo($upload->file_path, PATHINFO_EXTENSION); @endphp
        <div class="rounded-lg border border-gray-200 p-4">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
              @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ route('upload.preview', $upload->id) }}" alt="preview" class="h-full w-full object-cover" loading="eager">
              @else
                <i class="fas fa-file-pdf text-red-500"></i>
              @endif
            </div>
            <div>
              <p class="font-medium text-gray-800">{{ strtoupper($upload->jenis_dokumen) }} <span class="text-xs font-normal text-gray-500">({{ strtoupper($ext) }})</span></p>
              <a href="{{ route('upload.preview', $upload) }}" class="text-sm font-semibold text-disnaker-600 hover:underline" target="_blank">Lihat Dokumen</a>
            </div>
          </div>
        </div>
      @empty
        <p class="text-sm text-gray-500">Belum ada dokumen diunggah.</p>
      @endforelse
    </div>

    <div class="mt-8 rounded-lg border border-gray-200 p-4">
      <h3 class="text-sm font-semibold text-gray-900">Aksi Verifikasi</h3>
      <div class="mt-3 flex flex-wrap items-center gap-3">
        <button type="button" x-on:click="showApprove = true" class="btn-primary">Setujui</button>
        <div class="flex flex-wrap items-center gap-2">
          <input type="text" x-model="rejectCatatan" class="form-input" placeholder="Catatan penolakan (opsional)">
          <button type="button" x-on:click="showReject = true" class="btn-danger">Tolak</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Approve Confirmation Dialog --}}
  <div x-show="showApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" x-show="showApprove" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-on:click="showApprove = false"></div>
    <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl" x-show="showApprove" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-on:click.stop>
      <div class="flex items-start gap-4">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100">
          <i class="fas fa-info-circle text-disnaker-500"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-gray-900">Setujui Biodata?</h3>
          <p class="mt-2 text-sm text-gray-600">Biodata ini akan diverifikasi dan pengguna dapat mencetak kartu kuning.</p>
        </div>
      </div>
      <div class="mt-6 flex justify-end gap-3">
        <button type="button" x-on:click="showApprove = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
        <form action="{{ route('admin.verifikasi.updateStatus', $biodata) }}" method="POST" @submit="loadingApprove = true">
          @csrf
          <input type="hidden" name="status" value="verified">
          <button type="submit" class="rounded-lg bg-disnaker-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-disnaker-700" x-bind:disabled="loadingApprove">
            <span x-show="!loadingApprove">Ya, Setujui</span>
            <span x-show="loadingApprove" class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Reject Confirmation Dialog --}}
  <div x-show="showReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" x-show="showReject" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-on:click="showReject = false"></div>
    <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl" x-show="showReject" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-on:click.stop>
      <div class="flex items-start gap-4">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100">
          <i class="fas fa-exclamation-triangle text-red-500"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-gray-900">Tolak Biodata?</h3>
          <p class="mt-2 text-sm text-gray-600">Biodata ini akan ditolak. Pengguna akan diberi tahu.</p>
        </div>
      </div>
      <div class="mt-6 flex justify-end gap-3">
        <button type="button" x-on:click="showReject = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
        <form action="{{ route('admin.verifikasi.updateStatus', $biodata) }}" method="POST" @submit="loadingReject = true">
          @csrf
          <input type="hidden" name="status" value="rejected">
          <input type="hidden" name="catatan" :value="rejectCatatan">
          <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700" x-bind:disabled="loadingReject">
            <span x-show="!loadingReject">Ya, Tolak</span>
            <span x-show="loadingReject" class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menolak...</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
