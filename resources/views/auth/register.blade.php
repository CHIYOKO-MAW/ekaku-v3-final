@section('title', 'Daftar')
@section('description', 'Daftar akun e-Kaku V3 - Sistem Digital Kartu Kuning (AK1) Dinas Tenaga Kerja Kabupaten Pandeglang. Isi biodata, upload berkas, dan dapatkan kartu kuning digital.')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Akun</h2>
        <p class="mt-1 text-sm text-gray-600">Isi data diri Anda untuk mendaftar</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <div class="input-icon-wrapper mt-1">
                <i class="fas fa-user input-icon"></i>
                <x-text-input id="name" class="w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <div class="input-icon-wrapper mt-1">
                <i class="fas fa-envelope input-icon"></i>
                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@contoh.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <div class="input-icon-wrapper mt-1" x-data="{ show: false }">
                <i class="fas fa-lock input-icon"></i>
                <x-text-input id="password" class="w-full" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Masukkan password" />
                <button type="button" @click="show = !show" class="password-toggle" tabindex="-1">
                    <i x-bind:class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <div class="input-icon-wrapper mt-1" x-data="{ show: false }">
                <i class="fas fa-lock input-icon"></i>
                <x-text-input id="password_confirmation" class="w-full" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi password" />
                <button type="button" @click="show = !show" class="password-toggle" tabindex="-1">
                    <i x-bind:class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Sudah punya akun?</a>
            <x-primary-button x-bind:disabled="loading">
                <span x-show="!loading">Daftar</span>
                <span x-show="loading" class="flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...</span>
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>