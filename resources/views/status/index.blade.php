@extends('layouts.app')

@section('title', 'Update Status Pekerjaan')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Update Status Pekerjaan</h1>
    <p class="mt-1 text-sm text-gray-600">Smart Reminder akan mengingatkan pembaruan status setiap 90 hari.</p>
  </div>

  <div class="rounded-xl bg-white p-6 card-shadow">
    <form method="POST" action="{{ route('status.store') }}" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
      @csrf
      <div>
        <label class="form-label" for="status_pekerjaan">Status Pekerjaan</label>
        <select id="status_pekerjaan" name="status_pekerjaan" class="form-input" required>
          <option value="belum_bekerja" @selected(old('status_pekerjaan', optional($status)->status_pekerjaan) === 'belum_bekerja')>Belum Bekerja</option>
          <option value="sudah_bekerja" @selected(old('status_pekerjaan', optional($status)->status_pekerjaan) === 'sudah_bekerja')>Sudah Bekerja</option>
        </select>
      </div>

      <div>
        <label class="form-label" for="nama_perusahaan">Nama Perusahaan (jika sudah bekerja)</label>
        <input id="nama_perusahaan" name="nama_perusahaan" class="form-input" value="{{ old('nama_perusahaan', optional($status)->nama_perusahaan) }}">
      </div>

      <div>
        <label class="form-label" for="tanggal_update">Tanggal Update</label>
        <input id="tanggal_update" name="tanggal_update" type="date" class="form-input" value="{{ old('tanggal_update', optional(optional($status)->tanggal_update)->format('Y-m-d')) }}">
      </div>

      <button type="submit" class="btn-primary" x-bind:disabled="loading">
        <span x-show="!loading">Simpan Status</span>
        <span x-show="loading" class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...</span>
      </button>
    </form>
  </div>
</div>
@endsection
