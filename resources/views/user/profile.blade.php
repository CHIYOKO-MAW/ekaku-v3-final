@extends('layouts.app')

@section('title', 'Profil Saya')
@section('description', 'Kelola profil dan biodata e-Kaku V3 - Isi data pribadi, unggah berkas (KTP, Ijazah, Foto), dan ajukan verifikasi admin untuk mendapatkan Kartu Kuning digital.')

@section('content')
@php
    $progressFields = [
        'nik' => $biodata?->nik,
        'tempat_lahir' => $biodata?->tempat_lahir,
        'tanggal_lahir' => $biodata?->tanggal_lahir,
        'alamat' => $biodata?->alamat,
        'pendidikan' => $biodata?->pendidikan,
        'keahlian' => $biodata?->keahlian,
    ];
    $completedFields = collect($progressFields)->filter(fn ($value) => filled($value))->count();
    $totalFields = count($progressFields);
    $completionPercentage = $totalFields > 0 ? (int) round(($completedFields / $totalFields) * 100) : 0;
    $validUntil = $user->kartuKuning?->tanggal_cetak
        ? \Carbon\Carbon::parse($user->kartuKuning->tanggal_cetak)->addYears(5)
        : null;
@endphp
<div class="page-wrap" x-data="{ activeTab: 'akun', openPribadi: true, openPendidikan: true, loaded: false }" x-init="setTimeout(() => { activeTab = window.location.hash === '#biodata' ? 'biodata' : window.location.hash === '#berkas' ? 'berkas' : 'akun'; loaded = true; }, 150)">

    {{-- SKELETON: HEADER --}}
    <div x-show="!loaded" class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <x-skeleton type="avatar" class="h-16 w-16" />
                <div class="space-y-2">
                    <x-skeleton type="title" class="w-32" />
                    <x-skeleton type="text-sm" class="w-40" />
                </div>
            </div>
            <x-skeleton type="btn" class="hidden sm:block" />
        </div>
    </div>

    {{-- SKELETON: TAB BAR --}}
    <div x-show="!loaded" class="mt-6 rounded-lg bg-gray-200 px-4 py-3 animate-pulse">
        <div class="flex gap-6">
            @foreach([1,2,3] as $i)
            <x-skeleton type="text" class="w-16" />
            @endforeach
        </div>
    </div>

    {{-- SKELETON: CONTENT --}}
    <div x-show="!loaded" class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
                <x-skeleton type="title" class="w-1/3" />
                <div class="mt-4 space-y-4">
                    @foreach([1,2,3] as $i)
                    <div class="flex items-center gap-4">
                        <x-skeleton type="text-sm" class="w-1/4" />
                        <x-skeleton type="text" class="w-3/4" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="space-y-4">
            <div class="rounded-xl bg-gray-200 p-5 card-shadow animate-pulse">
                <x-skeleton type="text" class="w-1/2" />
                <x-skeleton type="title" class="mt-2 w-1/4" />
                <x-skeleton type="btn" class="mt-3" />
            </div>
        </div>
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-6">
    {{-- HEADER GRADIENT --}}
    <div class="rounded-xl bg-gradient-to-r from-[#005aa7] to-blue-600 p-6 card-shadow">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/20">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-sm text-blue-100">{{ $user->email }}</p>
                </div>
            </div>
            <a href="{{ route('dashboard.user') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30">
                <i class="fas fa-arrow-left"></i>Dashboard
            </a>
        </div>
    </div>

    {{-- TAB BAR --}}
    <div class="mt-6 rounded-lg bg-disnaker-100/50 px-4">
        <div class="flex gap-6">
            <button @click="activeTab = 'akun'; window.location.hash = ''" :class="activeTab === 'akun' ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex items-center border-b-2 px-1 py-3 text-sm font-medium">
                <i class="fas fa-user mr-2"></i>Akun
            </button>
            <button @click="activeTab = 'biodata'; window.location.hash = 'biodata'" :class="activeTab === 'biodata' ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex items-center border-b-2 px-1 py-3 text-sm font-medium">
                <i class="fas fa-address-card mr-2"></i>Biodata
            </button>
            <button @click="activeTab = 'berkas'; window.location.hash = '#berkas'" :class="activeTab === 'berkas' ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="inline-flex items-center border-b-2 px-1 py-3 text-sm font-medium">
                <i class="fas fa-file-upload mr-2"></i>Berkas
            </button>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">

            {{-- TAB AKUN --}}
            <div x-show="activeTab === 'akun'" class="overflow-hidden rounded-xl bg-white card-shadow">
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

            {{-- TAB BIODATA --}}
            <div x-show="activeTab !== 'akun'" id="biodata" class="overflow-hidden rounded-xl bg-white card-shadow">
                <div x-show="activeTab === 'biodata'" class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-6 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                <i class="fas fa-address-card text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white">Form Biodata Pencari Kerja</h2>
                                <p class="text-sm text-blue-100">Acuan pengisian disesuaikan dengan format E-Kaku v2 yang dipakai Disnaker.</p>
                            </div>
                        </div>
                        @if ($biodata?->status_verifikasi === 'verified')
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Terverifikasi</span>
                        @elseif ($biodata?->status_verifikasi === 'rejected')
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Ditolak</span>
                        @elseif ($biodata)
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Menunggu Validasi Admin</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">Belum Diisi</span>
                        @endif
                    </div>
                </div>
                <div x-show="activeTab === 'biodata'" class="p-6">

                <form method="POST" action="{{ $biodata ? route('biodata.update') : route('biodata.store') }}" class="space-y-6">
                    @csrf
                    @if ($biodata)
                        @method('PUT')
                    @endif

                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-disnaker-500">
                        <button type="button" @click="openPribadi = !openPribadi" class="flex w-full items-center justify-between px-4 py-3 text-left">
                            <h3 class="text-base font-semibold text-disnaker-700">Informasi Pribadi</h3>
                            <i class="fas" :class="openPribadi ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="openPribadi" class="border-t border-gray-200 p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-input-label for="nik" :value="__('NIK')" />
                                    <x-text-input id="nik" name="nik" type="text" required value="{{ old('nik', $biodata->nik ?? '') }}" />
                                    <x-input-error :messages="$errors->get('nik')" />
                                </div>
                                <div>
                                    <x-input-label for="nama_lengkap_display" :value="__('Nama Lengkap')" />
                                    <x-text-input id="nama_lengkap_display" type="text" value="{{ $user->name }}" disabled />
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" required value="{{ old('tempat_lahir', $biodata->tempat_lahir ?? '') }}" />
                                    <x-input-error :messages="$errors->get('tempat_lahir')" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" required value="{{ old('tanggal_lahir', $biodata->tanggal_lahir ?? '') }}" />
                                    <x-input-error :messages="$errors->get('tanggal_lahir')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="alamat" :value="__('Alamat')" />
                                    <textarea id="alamat" name="alamat" class="form-input" required>{{ old('alamat', $biodata->alamat ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('alamat')" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-input">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="laki_laki" @selected(old('jenis_kelamin', $biodata->jenis_kelamin ?? '') === 'laki_laki')>Laki-laki</option>
                                        <option value="perempuan" @selected(old('jenis_kelamin', $biodata->jenis_kelamin ?? '') === 'perempuan')>Perempuan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kelamin')" />
                                </div>
                                <div>
                                    <x-input-label for="agama" :value="__('Agama')" />
                                    <x-text-input id="agama" name="agama" type="text" value="{{ old('agama', $biodata->agama ?? '') }}" placeholder="Contoh: Islam" />
                                    <x-input-error :messages="$errors->get('agama')" />
                                </div>
                                <div>
                                    <x-input-label for="rt_rw" :value="__('RT/RW')" />
                                    <x-text-input id="rt_rw" name="rt_rw" type="text" value="{{ old('rt_rw', $biodata->rt_rw ?? '') }}" placeholder="Contoh: 002/005" />
                                    <x-input-error :messages="$errors->get('rt_rw')" />
                                </div>
                                <div>
                                    <x-input-label for="kode_pos" :value="__('Kode Pos')" />
                                    <x-text-input id="kode_pos" name="kode_pos" type="text" value="{{ old('kode_pos', $biodata->kode_pos ?? '') }}" placeholder="Contoh: 42211" />
                                    <x-input-error :messages="$errors->get('kode_pos')" />
                                </div>
                                <div>
                                    <x-input-label for="kecamatan" :value="__('Kecamatan')" />
                                    <x-text-input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan', $biodata->kecamatan ?? '') }}" placeholder="Contoh: Pandeglang" />
                                    <x-input-error :messages="$errors->get('kecamatan')" />
                                </div>
                                <div>
                                    <x-input-label for="desa_kelurahan" :value="__('Desa/Kelurahan')" />
                                    <x-text-input id="desa_kelurahan" name="desa_kelurahan" type="text" value="{{ old('desa_kelurahan', $biodata->desa_kelurahan ?? '') }}" placeholder="Contoh: Pandeglang" />
                                    <x-input-error :messages="$errors->get('desa_kelurahan')" />
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select id="status_perkawinan" name="status_perkawinan" class="form-input">
                                        <option value="">Pilih Status Perkawinan</option>
                                        <option value="belum_menikah" @selected(old('status_perkawinan', $biodata->status_perkawinan ?? '') === 'belum_menikah')>Belum Menikah</option>
                                        <option value="menikah" @selected(old('status_perkawinan', $biodata->status_perkawinan ?? '') === 'menikah')>Menikah</option>
                                        <option value="cerai_hidup" @selected(old('status_perkawinan', $biodata->status_perkawinan ?? '') === 'cerai_hidup')>Cerai Hidup</option>
                                        <option value="cerai_mati" @selected(old('status_perkawinan', $biodata->status_perkawinan ?? '') === 'cerai_mati')>Cerai Mati</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status_perkawinan')" />
                                </div>
                                <div>
                                    <x-input-label for="tinggi_badan" :value="__('Tinggi Badan (cm)')" />
                                    <x-text-input id="tinggi_badan" name="tinggi_badan" type="number" min="50" max="250" value="{{ old('tinggi_badan', $biodata->tinggi_badan ?? '') }}" />
                                    <x-input-error :messages="$errors->get('tinggi_badan')" />
                                </div>
                                <div>
                                    <x-input-label for="berat_badan" :value="__('Berat Badan (kg)')" />
                                    <x-text-input id="berat_badan" name="berat_badan" type="number" min="20" max="300" value="{{ old('berat_badan', $biodata->berat_badan ?? '') }}" />
                                    <x-input-error :messages="$errors->get('berat_badan')" />
                                </div>
                                <div class="md:col-span-2">
                                    <input type="hidden" name="disabilitas" value="0">
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input type="checkbox" name="disabilitas" value="1" class="rounded border-gray-300 text-disnaker-600 focus:ring-disnaker-500"
                                            @checked((bool) old('disabilitas', $biodata->disabilitas ?? false))>
                                        Penyandang disabilitas
                                    </label>
                                    <x-input-error :messages="$errors->get('disabilitas')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-disnaker-500">
                        <button type="button" @click="openPendidikan = !openPendidikan" class="flex w-full items-center justify-between px-4 py-3 text-left">
                            <h3 class="text-base font-semibold text-disnaker-700">Pendidikan dan Pengalaman</h3>
                            <i class="fas" :class="openPendidikan ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="openPendidikan" class="border-t border-gray-200 p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <x-input-label for="pendidikan" :value="__('Pendidikan Terakhir')" />
                                    <x-text-input id="pendidikan" name="pendidikan" type="text" required value="{{ old('pendidikan', $biodata->pendidikan ?? '') }}" />
                                    <x-input-error :messages="$errors->get('pendidikan')" />
                                </div>
                                <div>
                                    <x-input-label for="tahun_lulus" :value="__('Tahun Lulus')" />
                                    <x-text-input id="tahun_lulus" name="tahun_lulus" type="text" value="{{ old('tahun_lulus', $biodata->tahun_lulus ?? '') }}" placeholder="Contoh: 2020" />
                                    <x-input-error :messages="$errors->get('tahun_lulus')" />
                                </div>
                                <div>
                                    <x-input-label for="institusi_pendidikan" :value="__('Nama Institusi Pendidikan')" />
                                    <x-text-input id="institusi_pendidikan" name="institusi_pendidikan" type="text" value="{{ old('institusi_pendidikan', $biodata->institusi_pendidikan ?? '') }}" />
                                    <x-input-error :messages="$errors->get('institusi_pendidikan')" />
                                </div>
                                <div>
                                    <x-input-label for="jurusan" :value="__('Jurusan')" />
                                    <x-text-input id="jurusan" name="jurusan" type="text" value="{{ old('jurusan', $biodata->jurusan ?? '') }}" />
                                    <x-input-error :messages="$errors->get('jurusan')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="keahlian" :value="__('Keterampilan / Keahlian')" />
                                    <textarea id="keahlian" name="keahlian" class="form-input" placeholder="Contoh: Microsoft Office, Administrasi, Bahasa Inggris">{{ old('keahlian', $biodata->keahlian ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('keahlian')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="pengalaman" :value="__('Pengalaman Kerja')" />
                                    <textarea id="pengalaman" name="pengalaman" class="form-input" placeholder="Ringkasan pengalaman kerja Anda">{{ old('pengalaman', $biodata->pengalaman ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('pengalaman')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="tujuan_lamaran" :value="__('Tempat/Tujuan Melamar')" />
                                    <x-text-input id="tujuan_lamaran" name="tujuan_lamaran" type="text" value="{{ old('tujuan_lamaran', $biodata->tujuan_lamaran ?? '') }}" placeholder="Contoh: PT Maju Bersama" />
                                    <x-input-error :messages="$errors->get('tujuan_lamaran')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>{{ $biodata ? 'Perbarui Biodata' : 'Simpan Biodata' }}
                        </button>
                    </div>
                </form>

                @php
                    $biodataComplete = $biodata && $biodata->isComplete();
                    $docsComplete = $user->hasRequiredDocuments();
                    $canSubmit = $biodata && $biodata->status_verifikasi !== 'verified' && $biodataComplete && $docsComplete;
                @endphp

                @if ($biodata && $biodata->status_verifikasi !== 'verified')
                    @if ($canSubmit)
                        <form method="POST" action="{{ route('biodata.submit') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn-secondary">
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Validasi Admin
                            </button>
                        </form>
                    @else
                        <div class="mt-3 space-y-2">
                            <button type="button" class="btn-secondary cursor-not-allowed opacity-50" disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Validasi Admin
                            </button>
                            @if (! $biodataComplete)
                                <p class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>Lengkapi data nik, tempat lahir, tanggal lahir, alamat, dan pendidikan terlebih dahulu.</p>
                            @endif
                            @if (! $docsComplete)
                                <p class="text-xs text-red-500">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Upload KTP, Ijazah, dan Foto
                                    <button type="button" @@click="activeTab = 'berkas'; window.location.hash = '#berkas'" class="font-semibold text-red-700 underline hover:text-red-900">di sini</button>
                                    sebelum mengajukan verifikasi.
                                </p>
                            @endif
                        </div>
                                                                    @endif
                @endif
            </div>

            {{-- TAB BERKAS --}}
            @php
                $jenisList = ['ktp' => 'KTP', 'ijazah' => 'Ijazah', 'foto' => 'Foto', 'kk' => 'Kartu Keluarga', 'sertifikat' => 'Sertifikat'];
                $requiredDocs = ['ktp', 'ijazah', 'foto'];
                $groupedUploads = $uploads->groupBy('jenis_dokumen');
                $icons = ['ktp' => 'fa-id-card', 'ijazah' => 'fa-graduation-cap', 'foto' => 'fa-camera', 'kk' => 'fa-users', 'sertifikat' => 'fa-certificate'];
                $allRequiredUploaded = collect($requiredDocs)->every(fn ($key) => $groupedUploads->get($key)?->isNotEmpty());
            @endphp
            <template x-if="activeTab === 'berkas'">
            <div class="space-y-6">
                @if (! $allRequiredUploaded)
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 card-shadow">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100">
                                <i class="fas fa-exclamation-circle text-sm text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-red-800">Dokumen Wajib Belum Lengkap</p>
                                <p class="mt-1 text-sm text-red-700">
                                    Upload <span class="font-semibold">KTP</span>,
                                    <span class="font-semibold">Ijazah</span>, dan
                                    <span class="font-semibold">Foto</span>
                                    untuk bisa mengajukan validasi admin.
                                    @if ($biodata && $biodata->status_verifikasi !== 'verified')
                                        <a href="{{ $biodata ? route('biodata.edit') : route('biodata.create') }}" class="ml-1 font-semibold text-red-800 underline hover:text-red-900">
                                            Kembali ke Biodata <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-green-200 bg-green-50 p-4 card-shadow">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100">
                                <i class="fas fa-check-circle text-sm text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-green-800">Semua Dokumen Wajib Terupload</p>
                                <p class="mt-1 text-sm text-green-700">Anda sudah siap mengajukan validasi admin.</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($jenisList as $key => $label)
                        @php
                            $docs = $groupedUploads->get($key, collect());
                            $isRequired = in_array($key, $requiredDocs);
                        @endphp
                        <div class="rounded-xl bg-white p-6 card-shadow border-t-2 {{ $isRequired ? 'border-red-400' : 'border-disnaker-300' }}">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-disnaker-100">
                                    <i class="fas {{ $icons[$key] }} text-lg text-disnaker-600"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">{{ $label }}</h3>
                                        @if ($isRequired)
                                            <span class="rounded bg-red-100 px-1.5 py-0.5 text-[10px] font-semibold text-red-700">Wajib</span>
                                        @else
                                            <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold text-gray-500">Opsional</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        @if ($docs->count())
                                            {{ $docs->count() }} file terupload
                                        @else
                                            Belum upload
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                @forelse ($docs as $doc)
                                    @php $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION); @endphp
                                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-2">
                                         <a href="{{ route('upload.preview', $doc->id) }}" target="_blank" class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                             @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                                 <img src="{{ route('upload.thumbnail', $doc->id) }}" alt="preview" class="h-full w-full object-cover" loading="lazy">
                                             @else
                                                 <i class="fas fa-file-pdf text-red-500"></i>
                                             @endif
                                         </a>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('upload.preview', $doc->id) }}" target="_blank" class="block truncate text-sm font-medium text-gray-900 hover:text-disnaker-600">
                                                {{ basename($doc->file_path) }}
                                            </a>
                                            <p class="text-xs text-gray-500">{{ $doc->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div x-data="{ confirm: false }">
                                            <button @click.prevent="confirm = true" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                            <div x-cloak x-show="confirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.away="confirm = false">
                                                <div class="rounded-xl bg-white p-6 shadow-xl" @click.stop>
                                                    <p class="text-sm text-gray-700">Hapus dokumen <span class="font-semibold">{{ basename($doc->file_path) }}</span>?</p>
                                                    <div class="mt-4 flex gap-2">
                                                        <form action="{{ route('upload.destroy', $doc->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn-danger text-sm">Hapus</button>
                                                        </form>
                                                        <button @click="confirm = false" class="btn-secondary text-sm">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center rounded-lg border-2 border-dashed border-gray-200 py-8">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-300"></i>
                                        <p class="mt-2 text-sm text-gray-400">Belum ada {{ strtolower($label) }}</p>
                                    </div>
                                @endforelse
                            </div>

                            @if ($key === 'sertifikat')
                                <p class="mt-3 text-xs italic text-gray-400">*Jika Sudah Punya</p>
                            @endif

                            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-lg border border-disnaker-200 bg-disnaker-50 p-4" x-data="{ uploading: false, error: '' }" @submit.prevent="
                                uploading = true; error = '';
                                const formData = new FormData($el);
                                fetch('{{ route('upload.store') }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                }).then(r => r.json().then(data => ({ status: r.status, data })))
                                  .then(({ status, data }) => {
                                      uploading = false;
                                      if (status === 422) { error = data.errors?.file?.[0] || data.message || 'Validation error'; return; }
                                      if (status === 200 || status === 302) { window.location.hash = '#berkas'; window.location.reload(); }
                                  }).catch(() => { uploading = false; error = 'Upload gagal. Coba lagi.'; });
                            ">
                                @csrf
                                <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                                <div>
                                    <x-input-label for="file_{{ $key }}" value="File (jpg/png/pdf, max 5MB)" />
                                    <input id="file_{{ $key }}" type="file" name="file" class="form-input" required accept=".jpg,.jpeg,.png,.pdf">
                                    <x-input-error :messages="$errors->get('file')" />
                                    <p x-show="error" x-text="error" class="mt-1 text-xs text-red-500"></p>
                                </div>
                                <button type="submit" class="btn-primary w-full" :disabled="uploading">
                                    <i class="fas mr-2" :class="uploading ? 'fa-spinner fa-spin' : 'fa-upload'"></i>
                                    <span x-text="uploading ? 'Mengupload...' : 'Upload'"></span>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            </template>
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="space-y-6">
            <div class="overflow-hidden rounded-xl bg-white card-shadow">
                <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white"><i class="fas fa-chart-line mr-2"></i>Progress Biodata</h3>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Kelengkapan</span>
                        <span class="font-semibold text-gray-900">{{ $completionPercentage }}%</span>
                    </div>
                    <div class="mt-2 h-2 w-full rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-disnaker-500 transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        @foreach ($progressFields as $label => $value)
                            <div class="flex items-center {{ filled($value) ? 'text-green-600' : 'text-gray-400' }}">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ ucwords(str_replace('_', ' ', $label)) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white card-shadow">
                <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white"><i class="fas fa-id-card mr-2"></i>Mini Kartu Digital</h3>
                </div>
                <div class="p-5">
                    @if ($biodata?->status_verifikasi === 'verified')
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                            <p class="text-sm text-gray-600">Nomor AK-1</p>
                            <p class="font-semibold text-gray-900">{{ $user->kartuKuning->nomor_ak1 ?? '-' }}</p>
                            <p class="mt-2 text-xs text-gray-500">Berlaku hingga {{ $validUntil->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('kartu.show') }}" class="btn-primary mt-4">
                            <i class="fas fa-eye mr-2"></i>Preview Kartu
                        </a>
                    @else
                        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                            <p class="text-sm font-medium text-yellow-800">Kartu masih terkunci</p>
                            <p class="mt-1 text-xs text-yellow-700">Kartu aktif setelah biodata diverifikasi admin.</p>
                        </div>
                        @if ($biodata)
                            @php $canSubmitSidebar = $biodata->isComplete() && $docsComplete; @endphp
                            @if ($canSubmitSidebar)
                                <form method="POST" action="{{ route('biodata.submit') }}" class="mt-4">
                                    @csrf
                                    <button type="submit" class="btn-secondary">
                                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Validasi Admin
                                    </button>
                                </form>
                            @else
                                <div class="mt-4 space-y-1">
                                    <button type="button" class="btn-secondary opacity-50 cursor-not-allowed w-full" disabled>
                                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Validasi Admin
                                    </button>
                                    @if (! $docsComplete)
                                        <p class="text-xs text-red-500">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Upload dokumen dulu
                                            <button type="button" @@click="activeTab = 'berkas'; window.location.hash = '#berkas'" class="font-semibold text-red-700 underline hover:text-red-900">di sini</button>
                                        </p>
                                    @endif
                                    @if (! $biodata->isComplete())
                                        <p class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>Lengkapi data biodata dulu.</p>
                                    @endif
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white card-shadow">
                <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white"><i class="fas fa-check-circle mr-2"></i>Status Verifikasi</h3>
                </div>
                <div class="p-5">
                    <p class="text-sm text-gray-600">Preview dan cetak kartu kuning hanya aktif setelah biodata terverifikasi admin.</p>
                    <div class="mt-4">
                        @if ($biodata?->status_verifikasi === 'verified')
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Terverifikasi Admin</span>
                        @elseif ($biodata?->status_verifikasi === 'rejected')
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Ditolak, mohon perbaiki biodata</span>
                        @elseif ($biodata)
                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Menunggu validasi admin</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">Biodata belum diisi</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white card-shadow">
                <div class="bg-gradient-to-r from-[#005aa7] to-blue-600 px-4 py-3">
                    <h3 class="text-sm font-semibold text-white"><i class="fas fa-bolt mr-2"></i>Aksi Cepat</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-2">
                        <a href="{{ route('profile.edit') }}#biodata" class="block rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-address-card mr-2 text-disnaker-600"></i>Isi / Update Biodata
                        </a>
                        <a href="{{ route('status.index') }}" class="block rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-sync-alt mr-2 text-disnaker-600"></i>Update Status Pekerjaan
                        </a>
                        <a href="{{ route('kartu.show') }}" class="block rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-id-card mr-2 text-disnaker-600"></i>Lihat Kartu Kuning
                        </a>
                        <a href="{{ route('notifikasi.index') }}" class="block rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-bell mr-2 text-disnaker-600"></i>Lihat Notifikasi
                        </a>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
