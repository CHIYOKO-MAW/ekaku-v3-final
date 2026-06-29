@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-2xl space-y-6 py-8 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <div class="rounded-xl bg-gradient-to-r from-[#005aa7] to-blue-600 p-6 card-shadow">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20">
                <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                <p class="text-sm text-blue-100">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>

    {{-- INFORMASI AKUN --}}
    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Informasi Akun</h2>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @csrf
                @method('PATCH')

                <div class="md:col-span-2">
                    <x-input-label value="Foto Profil" />
                    <div class="mt-2 flex items-center gap-5">
                        <div class="relative flex-shrink-0">
                            @if ($user->profile_photo_path)
                                <img src="{{ route('profile.photo', $user) }}" alt="{{ $user->name }}"
                                     loading="eager" width="80" height="80"
                                     class="h-20 w-20 rounded-full border-2 border-gray-200 object-cover shadow-sm">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-disnaker-100 border-2 border-gray-200">
                                    <span class="text-2xl font-bold text-disnaker-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <label class="btn-secondary cursor-pointer">
                                    <i class="fas fa-camera mr-1"></i>Pilih Foto
                                    <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp"
                                           class="hidden" x-ref="photoInput"
                                           @@change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   const reader = new FileReader();
                                                   reader.onload = (e) => {
                                                       const img = $el.closest('.md\\:col-span-2').querySelector('.preview-photo');
                                                       if (img) img.src = e.target.result;
                                                   };
                                                   reader.readAsDataURL(file);
                                               }
                                           ">
                                </label>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                            <x-input-error :messages="$errors->get('profile_photo')" />
                        </div>
                    </div>
                    @if ($user->profile_photo_path)
                        <div class="mt-2 text-xs text-gray-400">
                            Foto sebelumnya akan diganti secara otomatis.
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                    <x-text-input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Informasi Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- GANTI KATA SANDI --}}
    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                    <i class="fas fa-lock text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Ganti Kata Sandi</h2>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="current_password" :value="__('Kata Sandi Saat Ini')" />
                    <x-text-input id="current_password" name="current_password" type="password" />
                    <x-input-error :messages="$errors->get('current_password')" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" />
                    <x-text-input id="password" name="password" type="password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- HAPUS AKUN --}}
    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Hapus Akun</h2>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600">Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus permanen. Sebelum menghapus akun, silakan unduh data atau informasi apa pun yang ingin Anda simpan.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" x-data="{ show: false }" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="button" @click="show = true" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Hapus Akun
                </button>
                <div x-show="show" x-cloak class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4">
                    <p class="text-sm text-red-700">Masukkan kata sandi untuk mengkonfirmasi penghapusan akun.</p>
                    <div class="mt-3">
                        <x-input-label for="password_delete" :value="__('Kata Sandi')" />
                        <x-text-input id="password_delete" name="password" type="password" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" />
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Ya, Hapus Akun Saya
                        </button>
                        <button type="button" @click="show = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
