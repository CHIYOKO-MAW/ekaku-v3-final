@section('title', 'Verifikasi Email')
<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Verifikasi Email</h2>
        <p class="mt-1 text-sm text-gray-600">Silakan verifikasi email Anda untuk mengakses semua fitur</p>
    </div>

    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
        <i class="fas fa-info-circle mr-1"></i>
        Link verifikasi telah dikirim ke email Anda saat pendaftaran.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            <i class="fas fa-check-circle mr-1"></i>
            Link verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                <i class="fas fa-paper-plane mr-1"></i> Kirim Ulang
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
