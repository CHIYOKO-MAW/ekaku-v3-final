@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
  <x-breadcrumb :items="[
      ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
      ['label' => 'Pengguna', 'url' => route('admin.pengguna.index')],
      ['label' => 'Edit Pengguna']
  ]" />

  <div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Edit Pengguna</h1>
    <a href="{{ route('admin.pengguna.index') }}" class="btn-secondary">Kembali</a>
  </div>

  <div class="rounded-xl bg-white p-6 card-shadow">
    <form method="POST" action="{{ route('admin.pengguna.update', $user) }}" class="space-y-4" x-data="{ loading: false }" @submit="loading = true">
      @csrf
      @method('PUT')

      <div>
        <label class="form-label" for="name">Nama</label>
        <input id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="form-label" for="email">Email</label>
        <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required>
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="form-label" for="role">Role</label>
        <select id="role" name="role" class="form-input" required>
          @foreach(['admin' => 'Admin', 'atasan' => 'Atasan', 'user' => 'User'] as $value => $label)
            <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="form-label" for="password">Password Baru (opsional)</label>
        <input id="password" name="password" type="password" class="form-input">
      </div>

      <div>
        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-input">
      </div>

      <button type="submit" class="btn-primary" x-bind:disabled="loading">
        <span x-show="!loading">Simpan Perubahan</span>
        <span x-show="loading" class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...</span>
      </button>
    </form>
  </div>
</div>
@endsection
