@section('title', 'Lupa Password')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password</h2>
        <p class="mt-1 text-sm text-gray-600">Masukkan email Anda. Kami akan kirim tautan reset password.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <div class="input-icon-wrapper mt-1">
                <i class="fas fa-envelope input-icon"></i>
                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="email@contoh.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>
                Kirim Link Reset
            </x-primary-button>
        </div>

        <div class="text-center">
            <a class="text-sm text-disnaker-600 hover:text-disnaker-700" href="{{ route('login') }}">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke halaman masuk
            </a>
        </div>
    </form>
</x-guest-layout>
