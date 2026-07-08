@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="text-center">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-disnaker-100">
            <i class="fas fa-id-card text-2xl text-disnaker-600"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Selamat Datang di e-Kaku!</h1>
        <p class="mt-3 text-gray-600">Sistem Digital Kartu Kuning Dinas Tenaga Kerja Kabupaten Pandeglang</p>
    </div>

    <div class="mt-10 space-y-6">
        {{-- Step 1 --}}
        <div class="flex items-start gap-4 rounded-xl bg-white p-5 card-shadow">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-disnaker-500 text-white font-bold">1</div>
            <div>
                <h3 class="font-semibold text-gray-900">Isi Biodata</h3>
                <p class="mt-1 text-sm text-gray-600">Lengkapi data diri Anda: NIK, tempat lahir, tanggal lahir, alamat, pendidikan, dan keahlian.</p>
                <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-clock"></i>
                    <span>± 5 menit</span>
                </div>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex items-start gap-4 rounded-xl bg-white p-5 card-shadow">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-disnaker-500 text-white font-bold">2</div>
            <div>
                <h3 class="font-semibold text-gray-900">Unggah Berkas</h3>
                <p class="mt-1 text-sm text-gray-600">Upload KTP, Ijazah, dan Foto. Kartu Keluarga dan Sertifikat bersifat opsional.</p>
                <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-file-upload"></i>
                    <span>Format: JPG, PNG, atau PDF (maks 5MB)</span>
                </div>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex items-start gap-4 rounded-xl bg-white p-5 card-shadow">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-disnaker-500 text-white font-bold">3</div>
            <div>
                <h3 class="font-semibold text-gray-900">Ajukan Verifikasi</h3>
                <p class="mt-1 text-sm text-gray-600">Kirim biodata Anda untuk diverifikasi admin. Setelah disetujui, Anda bisa mencetak Kartu Kuning digital.</p>
                <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-check-circle"></i>
                    <span>Verifikasi biasanya selesai dalam 1×24 jam</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="{{ route('profile.edit') }}" class="rounded-lg bg-disnaker-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-disnaker-700 focus:ring-2 focus:ring-disnaker-500 focus:ring-offset-2">
            <i class="fas fa-arrow-right mr-2"></i>Mulai Isi Biodata
        </a>
        <form method="POST" action="{{ route('onboarding.skip') }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="w-full rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50">
                Lewati, nanti saja
            </button>
        </form>
    </div>
</div>
@endsection
