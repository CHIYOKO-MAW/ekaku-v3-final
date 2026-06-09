@section('title', 'Reset Password')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
        <p class="mt-1 text-sm text-gray-600">Atur password baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email" />
            <div class="input-icon-wrapper mt-1">
                <i class="fas fa-envelope input-icon"></i>
                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="email@contoh.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password Baru" />
            <div class="input-icon-wrapper mt-1" x-data="{ show: false }">
                <i class="fas fa-lock input-icon"></i>
                <x-text-input id="password" class="w-full" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Masukkan password baru" />
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
                <x-text-input id="password_confirmation" class="w-full" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi password baru" />
                <button type="button" @click="show = !show" class="password-toggle" tabindex="-1">
                    <i x-bind:class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>
                Simpan Password Baru
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
