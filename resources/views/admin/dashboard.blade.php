@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-wrap" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON: HEADER --}}
    <div x-show="!loaded" class="mb-8 rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
        <div class="flex items-center justify-between">
            <div class="space-y-3">
                <x-skeleton type="title" class="w-1/3" />
                <x-skeleton type="text" class="w-1/2" />
            </div>
            <x-skeleton type="circle" class="hidden sm:block h-14 w-14" />
        </div>
    </div>

    {{-- SKELETON: STAT CARDS --}}
    <div x-show="!loaded" class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach([1,2,3,4] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text-sm" class="w-1/2" />
            <x-skeleton type="title" class="mt-2 w-1/3" />
            <x-skeleton type="text-sm" class="mt-2 w-3/4" />
        </div>
        @endforeach
    </div>

    {{-- SKELETON: GRID --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        @foreach([1,2] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/2" />
            <div class="mt-4 space-y-3">
                @foreach([1,2,3] as $j)
                <div class="flex items-center justify-between rounded-lg bg-gray-300 px-3 py-2 animate-pulse">
                    <x-skeleton type="text-sm" class="w-1/2" />
                    <x-skeleton type="text-sm" class="w-8" />
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- SKELETON: CHARTS --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        @foreach([1,2] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/3" />
            <x-skeleton type="card" class="mt-4 h-40" />
        </div>
        @endforeach
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-8">

        {{-- HEADER --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                    <p class="mt-1 text-sm text-gray-600">Monitoring operasional verifikasi dan layanan kartu kuning digital.</p>
                </div>
                <div class="hidden h-14 w-14 items-center justify-center rounded-full bg-disnaker-100 sm:flex">
                    <i class="fas fa-tachometer-alt text-xl text-disnaker-600"></i>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            {{-- Total Pengguna --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <p class="text-sm text-gray-500">Total Pengguna</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalUsers ?? 0) }}</p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full bg-disnaker-500" style="width: 100%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Semua pengguna terdaftar</p>
            </div>

            {{-- Sudah Bekerja --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <p class="text-sm text-gray-500">Sudah Bekerja</p>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ number_format($sudahBekerja ?? 0) }}</p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full bg-green-500" style="width: {{ $percentActive ?? 0 }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">{{ $percentActive ?? 0 }}% dari total pengguna</p>
            </div>

            {{-- Belum Bekerja --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <p class="text-sm text-gray-500">Belum Bekerja</p>
                <p class="mt-2 text-3xl font-bold text-yellow-600">{{ number_format($belumBekerja ?? 0) }}</p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full bg-yellow-500" style="width: {{ 100 - ($percentActive ?? 0) }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">{{ 100 - ($percentActive ?? 0) }}% dari total pengguna</p>
            </div>

            {{-- Pending Verifikasi --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <p class="text-sm text-gray-500">Pending Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($pending ?? 0) }}</p>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full bg-red-500" style="width: {{ $percentPending ?? 0 }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">{{ $percentPending ?? 0 }}% dari total pengguna</p>
            </div>
        </div>

        {{-- TOP WILAYAH + TOP RASIO --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            {{-- Wilayah Prioritas --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900">Wilayah Prioritas</h2>
                <p class="mt-1 text-xs text-gray-500">5 kecamatan dengan jumlah belum bekerja + pending verifikasi terbanyak.</p>
                <div class="mt-4 space-y-3">
                    @forelse(($topAttentionDistricts ?? []) as $district)
                        <div class="rounded-lg border border-gray-200 p-3">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900">{{ $district['kecamatan'] }}</p>
                                <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">{{ $district['belum_bekerja'] }} belum bekerja</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-600">
                                <span class="text-green-600">{{ $district['sudah_bekerja'] }} sudah bekerja</span>
                                <span>&bull;</span>
                                <span class="text-red-600">{{ $district['pending_verifikasi'] }} pending</span>
                                <span>&bull;</span>
                                <span>{{ $district['total'] }} total</span>
                            </div>
                            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                                <div class="h-full rounded-full bg-yellow-400" style="width: {{ $district['total'] > 0 ? round(($district['belum_bekerja'] / $district['total']) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-info-circle mr-2"></i>Belum ada data wilayah.</div>
                    @endforelse
                </div>
            </div>

            {{-- Top Rasio --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900">Top Rasio Sudah Bekerja</h2>
                <p class="mt-1 text-xs text-gray-500">5 kecamatan dengan persentase sudah bekerja tertinggi.</p>
                <div class="mt-4 space-y-3">
                    @forelse(($topWorkingDistricts ?? []) as $district)
                        <div class="rounded-lg border border-gray-200 p-3">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900">{{ $district['kecamatan'] }}</p>
                                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">{{ $district['rasio_bekerja'] }}%</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-600">{{ $district['sudah_bekerja'] }} dari {{ $district['total'] }} pengguna sudah bekerja.</p>
                            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                                <div class="h-full rounded-full bg-green-400" style="width: {{ $district['rasio_bekerja'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                <i class="fas fa-trophy text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500">Belum ada data rasio wilayah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- AKTIVITAS TERBARU --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                <a href="{{ route('admin.laporan') }}" class="text-sm font-medium text-disnaker-600 hover:text-disnaker-700">Lihat laporan</a>
            </div>
            <div class="table-scroll-wrapper">
                {{-- Desktop table --}}
                <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">NIK</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Status Verifikasi</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Status Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentActivities as $item)
                            <tr>
                                <td class="px-3 py-2 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $item->biodata->nik ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @php $sv = $item->biodata->status_verifikasi ?? 'pending'; @endphp
                                    <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $sv === 'verified' ? 'bg-green-100 text-green-700' : ($sv === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ strtoupper($sv) }}</span>
                                </td>
                                <td class="px-3 py-2 text-gray-700">{{ $item->statusPekerjaan?->status_pekerjaan ? str_replace('_', ' ', ucfirst($item->statusPekerjaan->status_pekerjaan)) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-12 text-center text-gray-500"><i class="fas fa-history mr-2"></i>Belum ada aktivitas user terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- Mobile cards --}}
                <div class="sm:hidden space-y-3 p-4">
                    @forelse($recentActivities as $item)
                        @php $sv = $item->biodata->status_verifikasi ?? 'pending'; @endphp
                        <x-mobile-card
                            :title="$item->name"
                            :subtitle="'NIK: ' . ($item->biodata->nik ?? '-')"
                            :status="$sv === 'verified' ? 'green' : ($sv === 'rejected' ? 'red' : 'yellow')"
                            :badge="str_replace('_', ' ', ucfirst($item->statusPekerjaan?->status_pekerjaan ?? '-'))"
                        >
                            <div class="mobile-card-field">
                                <i class="fas fa-id-card mobile-card-field-icon"></i>
                                <span class="mobile-card-field-label">Verifikasi</span>
                                <span class="mobile-card-field-value font-semibold {{ $sv === 'verified' ? 'text-green-700' : ($sv === 'rejected' ? 'text-red-700' : 'text-yellow-700') }}">{{ strtoupper($sv) }}</span>
                            </div>
                        </x-mobile-card>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-history mr-2"></i>Belum ada aktivitas user terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SEMUA WILAYAH TABLE --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <h2 class="text-lg font-semibold text-gray-900">Semua Wilayah Kerja</h2>
            <p class="mt-1 text-xs text-gray-500">Distribusi seluruh kecamatan di Kabupaten Pandeglang. Diurutkan berdasarkan skor prioritas (BB + PV).</p>
            <div class="mt-4 table-scroll-wrapper">
                <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Kecamatan</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Belum Bekerja</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Pending Verifikasi</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Sudah Bekerja</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Total User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($allDistricts ?? []) as $district)
                            <tr>
                                <td class="px-3 py-2 font-medium text-gray-900">{{ $district['kecamatan'] }}</td>
                                <td class="px-3 py-2 text-yellow-700">{{ $district['belum_bekerja'] }}</td>
                                <td class="px-3 py-2 text-red-700">{{ $district['pending_verifikasi'] }}</td>
                                <td class="px-3 py-2 text-green-700">{{ $district['sudah_bekerja'] }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $district['total'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-12 text-center text-gray-500"><i class="fas fa-info-circle mr-2"></i>Belum ada data wilayah.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="mt-3 text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Total = Belum Bekerja + Sudah Bekerja + Pending Verifikasi.
            </p>
        </div>

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Verifikasi</h2>
                <div class="relative h-[250px] lg:h-[300px]">
                    <canvas id="verificationChart"
                        data-ekaku-chart='@json(['labels' => $verificationData['labels'], 'values' => $verificationData['data'], 'colors' => $verificationData['colors']])'
                        data-chart-type="doughnut">
                    </canvas>
                </div>
            </div>
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Pekerjaan</h2>
                <div class="relative h-[250px] lg:h-[300px]">
                    <canvas id="jobStatusChart"
                        data-ekaku-chart='@json(['labels' => $jobStatusLabels, 'values' => $jobStatusData, 'colors' => $jobStatusColors])'
                        data-chart-type="bar">
                    </canvas>
                </div>
            </div>
        </div>

    </div> {{-- end x-show loaded --}}
  </div>
  @endsection
