@extends('layouts.app')

@section('title', 'Dashboard Atasan')

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
    <div x-show="!loaded" class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">
        @foreach([1,2,3,4,5] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text-sm" class="w-1/2" />
            <x-skeleton type="title" class="mt-2 w-1/3" />
        </div>
        @endforeach
    </div>

    {{-- SKELETON: CHARTS --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        @foreach([1,2] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/3" />
            <x-skeleton type="card" class="mt-4 h-48" />
        </div>
        @endforeach
    </div>

    {{-- SKELETON: GRID --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="rounded-xl bg-gray-200 p-6 card-shadow lg:col-span-1 animate-pulse">
            <x-skeleton type="text" class="w-1/2" />
            <div class="mt-4 space-y-3">
                @foreach([1,2,3] as $i)
                <div class="flex items-center justify-between rounded-lg bg-gray-300 px-3 py-2 animate-pulse">
                    <x-skeleton type="text-sm" class="w-1/2" />
                    <x-skeleton type="text-sm" class="w-8" />
                </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-xl bg-gray-200 p-6 card-shadow lg:col-span-2 animate-pulse">
            <x-skeleton type="text" class="w-1/3" />
            <div class="mt-4 space-y-3">
                @foreach([1,2,3,4] as $i)
                <div class="flex gap-4">
                    <x-skeleton type="text" class="w-1/4" />
                    <x-skeleton type="text" class="w-1/4" />
                    <x-skeleton type="badge" class="w-1/4" />
                    <x-skeleton type="text" class="w-1/4" />
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SKELETON: TABLES --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        @foreach([1,2] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/2" />
            <div class="mt-4 space-y-3">
                @foreach([1,2,3] as $j)
                <div class="flex items-center justify-between rounded-lg bg-gray-300 px-3 py-2 animate-pulse">
                    <x-skeleton type="text" class="w-1/3" />
                    <x-skeleton type="text-sm" class="w-8" />
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-8">

        {{-- HEADER --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Atasan</h1>
                    <p class="mt-1 text-sm text-gray-600">Monitoring performa operasional admin dan progress verifikasi layanan AK-1.</p>
                </div>
                <div class="hidden h-14 w-14 items-center justify-center rounded-full bg-purple-100 sm:flex">
                    <i class="fas fa-user-tie text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                        <i class="fas fa-user-shield text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAdmin ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <i class="fas fa-users text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pencari Kerja</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUser ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ number_format($pending ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Verified</p>
                        <p class="text-2xl font-bold text-green-700">{{ number_format($verified ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-2xl font-bold text-red-700">{{ number_format($rejected ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHARTS ROW --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Verifikasi</h2>
                <div class="relative" style="height: 280px;">
                    <canvas id="verificationChart"
                        data-ekaku-chart='@json(['labels' => $verificationData['labels'], 'values' => $verificationData['data'], 'colors' => $verificationData['colors']])'
                        data-chart-type="doughnut">
                    </canvas>
                </div>
            </div>
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Pekerjaan</h2>
                <div class="relative" style="height: 280px;">
                    <canvas id="jobStatusChart"
                        data-ekaku-chart='@json(['labels' => $jobStatusLabels, 'values' => $jobStatusData, 'colors' => $jobStatusColors])'
                        data-chart-type="bar">
                    </canvas>
                </div>
            </div>
        </div>

        {{-- WEEKLY TREND --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Registrasi Mingguan (7 Hari Terakhir)</h2>
            <div class="relative" style="height: 220px;">
                <canvas id="weeklyChart"
                    data-ekaku-chart='@json(['labels' => $weeklyRegistrations->pluck('label'), 'values' => $weeklyRegistrations->pluck('count'), 'colors' => ['#2563eb']])'
                    data-chart-type="bar">
                </canvas>
            </div>
        </div>

        {{-- ADMIN PERFORMANCE + STATUS DISTRIBUTION --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-6 card-shadow lg:col-span-1">
                <h2 class="text-lg font-semibold text-gray-900">Distribusi Status Pekerjaan</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2">
                        <span class="text-gray-700">Belum Bekerja</span>
                        <span class="font-semibold text-yellow-700">{{ $statusDistribution['belum_bekerja'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2">
                        <span class="text-gray-700">Sudah Bekerja</span>
                        <span class="font-semibold text-green-700">{{ $statusDistribution['sudah_bekerja'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2">
                        <span class="text-gray-700">Sedang Pelatihan</span>
                        <span class="font-semibold text-blue-700">{{ $statusDistribution['pelatihan'] ?? 0 }}</span>
                    </div>
                </div>
                <a href="{{ route('atasan.rekap-admin') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-disnaker-600 hover:text-disnaker-700">
                    <i class="fas fa-arrow-right mr-1"></i> Buka Rekap Admin
                </a>
            </div>

            <div class="rounded-xl bg-white p-6 card-shadow lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Performa Admin</h2>
                    <a href="{{ route('atasan.rekap-admin') }}" class="text-sm font-medium text-disnaker-600 hover:text-disnaker-700">Lihat detail</a>
                </div>
                <div class="space-y-4">
                    @forelse($adminPerformance as $admin)
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $admin['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $admin['email'] }}</p>
                            </div>
                            <span class="rounded-full bg-disnaker-100 px-3 py-1 text-xs font-semibold text-disnaker-700">{{ $admin['total'] }} verifikasi</span>
                        </div>
                        <div class="mt-3 flex items-center gap-4 text-xs">
                            <span class="text-green-700"><i class="fas fa-check mr-1"></i>{{ $admin['verified'] }} verified</span>
                            <span class="text-red-700"><i class="fas fa-times mr-1"></i>{{ $admin['rejected'] }} rejected</span>
                            <span class="ml-auto font-semibold {{ $admin['approval_rate'] >= 80 ? 'text-green-700' : ($admin['approval_rate'] >= 50 ? 'text-yellow-700' : 'text-red-700') }}">{{ $admin['approval_rate'] }}%</span>
                        </div>
                        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-gray-200">
                            <div class="h-full rounded-full {{ $admin['approval_rate'] >= 80 ? 'bg-green-500' : ($admin['approval_rate'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $admin['approval_rate'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-sm text-gray-500">
                        <i class="fas fa-user-shield mr-2"></i>Belum ada data performa admin.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RECENT ACTIVITIES + REGIONAL --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-xl bg-white p-6 card-shadow">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                </div>
                <div class="table-scroll-wrapper">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Nama</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">NIK</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Verifikasi</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Pekerjaan</th>
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
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900">Wilayah Prioritas Intervensi</h2>
                <p class="mt-1 text-xs text-gray-500">Skor prioritas berdasarkan jumlah belum bekerja + pending verifikasi.</p>
                <div class="mt-4 table-scroll-wrapper">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Kecamatan</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Belum Bekerja</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Pending Verifikasi</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Sudah Bekerja</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse(($topAttentionDistricts ?? []) as $district)
                            <tr>
                                <td class="px-3 py-2 font-medium text-gray-900">{{ $district['kecamatan'] }}</td>
                                <td class="px-3 py-2 text-yellow-700">{{ $district['belum_bekerja'] }}</td>
                                <td class="px-3 py-2 text-red-700">{{ $district['pending_verifikasi'] }}</td>
                                <td class="px-3 py-2 text-green-700">{{ $district['sudah_bekerja'] }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $district['total'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-3 py-12 text-center text-gray-500"><i class="fas fa-info-circle mr-2"></i>Belum ada ringkasan wilayah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total = Belum Bekerja + Sudah Bekerja + Pending Verifikasi.
                </p>
            </div>
        </div>

        {{-- TOP RASIO --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <h2 class="text-lg font-semibold text-gray-900">Top Rasio Sudah Bekerja</h2>
            <p class="mt-1 text-xs text-gray-500">Peringkat kecamatan dengan rasio user bekerja tertinggi.</p>
            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @forelse(($topWorkingDistricts ?? []) as $district)
                <div class="rounded-lg border border-gray-200 p-3">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">{{ $district['kecamatan'] }}</p>
                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">{{ $district['rasio_bekerja'] }}%</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-600">{{ $district['sudah_bekerja'] }} dari {{ $district['total'] }} pengguna berstatus sudah bekerja.</p>
                </div>
                @empty
                <div class="col-span-full py-8 text-center">
                    <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                        <i class="fas fa-trophy text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada data rasio wilayah.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div> {{-- end x-show loaded --}}
</div>
@endsection
