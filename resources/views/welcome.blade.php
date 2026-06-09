@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'e-Kaku V3 - Sistem Digital Kartu Kuning (AK1) Dinas Tenaga Kerja Kabupaten Pandeglang. Kelola biodata, upload berkas, verifikasi admin, dan cetak kartu kuning secara online. Daftar sekarang!')

@section('navigation')
    @include('layouts.navigation-guest')
@endsection

@section('content')
<section class="gradient-bg relative overflow-hidden text-white">
    <div class="absolute inset-0 opacity-[0.07]">
        <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="hero-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-grid)" />
        </svg>
    </div>
    <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
    <div class="absolute -bottom-20 -right-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            <div class="text-center lg:text-left">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm text-blue-100">
                    <i class="fas fa-id-card"></i>
                    <span>Dinas Tenaga Kerja Kabupaten Pandeglang</span>
                </div>
                <h1 class="text-4xl font-bold leading-tight md:text-5xl lg:text-6xl">
                    e-Kaku V3
                    <span class="mt-2 block text-xl font-normal text-blue-100 md:text-2xl">
                        Kartu Kuning Digital Disnaker Pandeglang
                    </span>
                </h1>
                <p class="mx-auto mt-6 max-w-xl text-lg text-blue-100 lg:mx-0">
                    Digitalisasi kartu kuning (AK-1) untuk pencari kerja. Daftar, lengkapi profil, dan dapatkan kartu digital dengan QR code.
                </p>
                <div class="mt-8 flex flex-col items-center gap-4 sm:flex-row lg:justify-start">
                    <a href="{{ route('register') }}"
                       class="group inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white px-8 py-3 font-semibold text-disnaker-600 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 sm:w-auto">
                        Daftar Sekarang
                        <i class="fas fa-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                    </a>
                    <a href="{{ route('login') }}"
                       class="group inline-flex w-full items-center justify-center gap-2 rounded-lg border-2 border-white/60 px-8 py-3 font-semibold text-white transition-all duration-300 hover:border-white hover:bg-white/10 sm:w-auto">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                </div>
            </div>
            <div class="flex justify-center lg:justify-end">
                <picture>
                    <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                    <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="h-48 w-48 animate-float drop-shadow-2xl md:h-56 md:w-56 lg:h-64 lg:w-64">
                </picture>
            </div>
        </div>
    </div>
</section>

<section class="relative -mt-10 z-10">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 rounded-2xl bg-white p-8 shadow-xl sm:grid-cols-3">
            <div x-data="{ count: 0, target: {{ $stats['pencari_kerja'] }} }"
                 x-init="let i = 0; const t = setInterval(() => { count = ++i; if (i >= target) clearInterval(t); }, 10)"
                 class="text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-disnaker-100">
                    <i class="fas fa-users text-xl text-disnaker-600"></i>
                </div>
                <div class="counter-value" x-text="count.toLocaleString('id-ID')">0</div>
                <p class="mt-1 text-sm text-gray-500">Pencari Kerja Terdaftar</p>
            </div>
            <div x-data="{ count: 0, target: {{ $stats['kartu_terbit'] }} }"
                 x-init="let i = 0; const t = setInterval(() => { count = ++i; if (i >= target) clearInterval(t); }, 10)"
                 class="border-t border-gray-100 pt-6 text-center sm:border-l sm:border-t-0 sm:pt-0">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <i class="fas fa-id-card text-xl text-green-600"></i>
                </div>
                <div class="counter-value" x-text="count.toLocaleString('id-ID')">0</div>
                <p class="mt-1 text-sm text-gray-500">Kartu Kuning Terbit</p>
            </div>
            <div x-data="{ count: 0, target: {{ $stats['biodata_terverifikasi'] }} }"
                 x-init="let i = 0; const t = setInterval(() => { count = ++i; if (i >= target) clearInterval(t); }, 10)"
                 class="border-t border-gray-100 pt-6 text-center sm:border-l sm:border-t-0 sm:pt-0">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <i class="fas fa-check-circle text-xl text-blue-600"></i>
                </div>
                <div class="counter-value" x-text="count.toLocaleString('id-ID')">0</div>
                <p class="mt-1 text-sm text-gray-500">Biodata Terverifikasi</p>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Cara Kerja</h2>
            <p class="mx-auto mt-3 max-w-2xl text-lg text-gray-600">
                Hanya 3 langkah mudah untuk mendapatkan Kartu Kuning digital Anda
            </p>
        </div>
        <div class="relative grid grid-cols-1 gap-8 md:grid-cols-3">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-disnaker-50">
                    <i class="fas fa-user-plus text-2xl text-disnaker-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar Akun</h3>
                <p class="mt-2 text-sm text-gray-600">Buat akun pencari kerja dengan email dan data diri Anda.</p>
            </div>
            <div class="step-card" style="transition-delay: 0.1s">
                <div class="step-number">2</div>
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-disnaker-50">
                    <i class="fas fa-file-upload text-2xl text-disnaker-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Lengkapi Data</h3>
                <p class="mt-2 text-sm text-gray-600">Isi biodata dan unggah dokumen yang diperlukan untuk verifikasi.</p>
            </div>
            <div class="step-card" style="transition-delay: 0.2s">
                <div class="step-number">3</div>
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-disnaker-50">
                    <i class="fas fa-qrcode text-2xl text-disnaker-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Dapatkan Kartu</h3>
                <p class="mt-2 text-sm text-gray-600">Kartu digital dengan QR code siap diunduh dan diverifikasi.</p>
            </div>
        </div>
    </div>
</section>

<section id="features" class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Fitur Unggulan</h2>
            <p class="mx-auto mt-3 max-w-3xl text-lg text-gray-600">
                Sistem kartu kuning digital dengan teknologi terkini untuk kemudahan pencari kerja dan pengawas Disnaker.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-disnaker-100">
                    <i class="fas fa-user-edit text-xl text-disnaker-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Profil Lengkap</h3>
                <p class="mt-3 text-gray-600">Lengkapi data diri, skill, dan preferensi untuk mendapatkan rekomendasi yang tepat.</p>
                <div class="mt-4 text-sm font-medium text-disnaker-600">
                    <i class="fas fa-check mr-1"></i> Data terintegrasi
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift " style="transition-delay: 0.1s">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100">
                    <i class="fas fa-sync-alt text-xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Update Status</h3>
                <p class="mt-3 text-gray-600">Update status pekerjaan secara berkala: Belum Bekerja, Sudah Bekerja, atau Pelatihan.</p>
                <div class="mt-4 text-sm font-medium text-green-600">
                    <i class="fas fa-bell mr-1"></i> Reminder otomatis 3 bulanan
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift " style="transition-delay: 0.2s">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <i class="fas fa-qrcode text-xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Kartu Digital</h3>
                <p class="mt-3 text-gray-600">Kartu digital dengan QR code yang bisa diunduh dalam PDF dan diverifikasi online.</p>
                <div class="mt-4 text-sm font-medium text-blue-600">
                    <i class="fas fa-mobile-alt mr-1"></i> Akses kapan saja
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift " style="transition-delay: 0.3s">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <i class="fas fa-chart-line text-xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Dashboard Admin</h3>
                <p class="mt-3 text-gray-600">Pantau statistik pencari kerja, tren, dan kelola data secara efisien.</p>
                <div class="mt-4 text-sm font-medium text-purple-600">
                    <i class="fas fa-chart-pie mr-1"></i> Visualisasi data lengkap
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift " style="transition-delay: 0.4s">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                    <i class="fas fa-bell text-xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Notifikasi</h3>
                <p class="mt-3 text-gray-600">Terima notifikasi update status, reminder, dan info dari Disnaker Pandeglang.</p>
                <div class="mt-4 text-sm font-medium text-yellow-600">
                    <i class="fas fa-envelope mr-1"></i> Email & in-app notification
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-6 card-shadow hover-lift " style="transition-delay: 0.5s">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100">
                    <i class="fas fa-file-export text-xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Export Laporan</h3>
                <p class="mt-3 text-gray-600">Export data pencari kerja dalam PDF atau CSV untuk keperluan pelaporan.</p>
                <div class="mt-4 text-sm font-medium text-red-600">
                    <i class="fas fa-download mr-1"></i> Multiple format support
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="bg-gray-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Tentang e-Kaku V3</h2>
            <p class="mx-auto mt-3 max-w-3xl text-lg text-gray-600">
                Digitalisasi layanan kartu kuning oleh Dinas Tenaga Kerja Kabupaten Pandeglang
            </p>
        </div>

        <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-2">
            <div class="space-y-4 text-gray-600">
                <p>
                    e-Kaku V3 adalah sistem digitalisasi kartu kuning (AK-1) yang dikembangkan oleh
                    Dinas Tenaga Kerja Kabupaten Pandeglang untuk memudahkan pencari kerja dalam
                    mengelola data dan status pekerjaan mereka.
                </p>
                <p>
                    Sistem ini dilengkapi dengan teknologi QR code, dashboard admin, dan notifikasi
                    yang memudahkan monitoring dan pelaporan secara real-time.
                </p>
                <p>
                    Dengan e-Kaku V3, pencari kerja dapat mengupdate status pekerjaan,
                    mendapatkan reminder otomatis, dan mengakses kartu digital kapan saja melalui
                    perangkat mobile atau desktop.
                </p>

                <h3 class="pt-4 text-xl font-semibold text-gray-900">Manfaat Utama</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Efisiensi administrasi pencari kerja</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Monitoring real-time status pekerjaan</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Reduksi biaya cetak kartu fisik</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Peningkatan akurasi data statistik</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl bg-white p-6 card-shadow">
                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-disnaker-100">
                            <i class="fas fa-building text-xl text-disnaker-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Dinas Tenaga Kerja</h3>
                            <p class="text-sm text-gray-600">Kabupaten Pandeglang</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-disnaker-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Alamat</p>
                                <p class="text-sm text-gray-600">Jl. Raya Pandeglang - Serang KM. 5, Pandeglang, Banten</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-phone mt-1 text-disnaker-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Telepon</p>
                                <p class="text-sm text-gray-600">(0253) 201001 / 201002</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-envelope mt-1 text-disnaker-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Email</p>
                                <p class="text-sm text-gray-600">disnaker@pandeglang.go.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 card-shadow">
                    <h3 class="mb-4 font-semibold text-gray-900">Jam Operasional</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Senin - Kamis</span>
                            <span class="font-medium text-gray-900">08:00 - 16:00 WIB</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumat</span>
                            <span class="font-medium text-gray-900">08:00 - 16:30 WIB</span>
                        </div>
                    </div>
                    <div class="mt-4 rounded-lg bg-yellow-50 p-3 text-xs text-yellow-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Untuk pendaftaran dan bantuan teknis, hubungi bagian informasi Disnaker.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection