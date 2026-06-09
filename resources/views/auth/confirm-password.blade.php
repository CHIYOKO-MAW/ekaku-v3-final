@section('title', 'Konfirmasi Password')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Konfirmasi Password</h2>
        <p class="mt-1 text-sm text-gray-600">Area aman. Konfirmasi password untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

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

        <div class="flex justify-end">
            <x-primary-button>
                Konfirmasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
