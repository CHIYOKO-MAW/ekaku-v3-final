@section('title', 'Masuk')
@section('description', 'Masuk ke e-Kaku V3 - Sistem Digital Kartu Kuning (AK1) Dinas Tenaga Kerja Kabupaten Pandeglang. Kelola biodata, status pekerjaan, dan kartu digital Anda.')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Masuk</h2>
        <p class="mt-1 text-sm text-gray-600">Masukkan email dan password Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <div class="input-icon-wrapper mt-1">
                <i class="fas fa-envelope input-icon"></i>
                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@contoh.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <div class="input-icon-wrapper mt-1" x-data="{ show: false }">
                <i class="fas fa-lock input-icon"></i>
                <x-text-input id="password" class="w-full" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
                <button type="button" @click="show = !show" class="password-toggle" tabindex="-1">
                    <i x-bind:class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-disnaker-500 shadow-sm focus:ring-disnaker-500" name="remember">
                <span class="ms-2">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">Belum punya akun?</a>
            <x-primary-button>
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
