@extends('layouts.app')

@section('title', 'Rekap Kinerja Admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON: HEADER --}}
    <div x-show="!loaded" class="mb-8 rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
        <div class="flex items-center justify-between">
            <div class="space-y-3">
                <x-skeleton type="title" class="w-1/3" />
                <x-skeleton type="text" class="w-1/2" />
            </div>
            <x-skeleton type="btn" class="w-32" />
        </div>
    </div>

    {{-- SKELETON: SUMMARY CARDS --}}
    <div x-show="!loaded" class="grid grid-cols-1 gap-6 md:grid-cols-4">
        @foreach([1,2,3,4] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text-sm" class="w-1/2" />
            <x-skeleton type="title" class="mt-2 w-1/3" />
        </div>
        @endforeach
    </div>

    {{-- SKELETON: CHART + TABLE --}}
    <div x-show="!loaded" class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/3" />
            <x-skeleton type="card" class="mt-4 h-48" />
        </div>
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text" class="w-1/2" />
            <div class="mt-4 space-y-3">
                @foreach([1,2,3,4,5] as $i)
                <div class="flex items-center justify-between rounded-lg bg-gray-300 px-3 py-2 animate-pulse">
                    <x-skeleton type="text" class="w-1/3" />
                    <x-skeleton type="text-sm" class="w-8" />
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-8">

        {{-- HEADER --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Rekap Kinerja Admin</h1>
                    <p class="mt-1 text-sm text-gray-600">Ringkasan aktivitas verifikasi masing-masing admin.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('atasan.rekap-admin.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                    <a href="{{ route('atasan.rekap-admin.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- DATE FILTER --}}
        <div class="rounded-xl bg-white p-6 card-shadow" x-data="{ start: '{{ $startDate ?? '' }}', end: '{{ $endDate ?? '' }}' }">
            <h2 class="mb-3 text-sm font-semibold text-gray-700">Filter Tanggal</h2>
            <form method="GET" action="{{ route('atasan.rekap-admin') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" x-model="start" class="form-input rounded-lg border-gray-300 text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" x-model="end" class="form-input rounded-lg border-gray-300 text-sm" />
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-disnaker-600 px-4 py-2 text-sm font-medium text-white hover:bg-disnaker-700 transition">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($startDate || $endDate)
                    <a href="{{ route('atasan.rekap-admin') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 transition">
                        <i class="fas fa-times"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                        <i class="fas fa-clipboard-list text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Verifikasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalVerifikasi) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approval Rate</p>
                        <p class="text-2xl font-bold {{ $approvalRate >= 80 ? 'text-green-700' : ($approvalRate >= 50 ? 'text-yellow-700' : 'text-red-700') }}">{{ $approvalRate }}%</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rata-rata/Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $avgPerAdmin }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <i class="fas fa-user-shield text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $admins->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART + TABLE --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            {{-- CHART --}}
            <div class="rounded-xl bg-white p-6 card-shadow">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Perbandingan Kinerja Admin</h2>
                @if(count($chartData['labels']) > 0)
                <div class="relative" style="height: 300px;">
                    <canvas id="adminPerformanceChart"
                        data-ekaku-chart='@json($chartData)'
                        data-chart-type="bar">
                    </canvas>
                </div>
                @else
                <div class="flex h-48 items-center justify-center text-sm text-gray-500">
                    <i class="fas fa-chart-bar mr-2"></i>Belum ada data untuk ditampilkan.
                </div>
                @endif
            </div>

            {{-- TABLE --}}
            <div class="rounded-xl bg-white p-6 card-shadow" x-data="{ sort: 'total', dir: 'desc' }">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Kinerja Admin</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 cursor-pointer select-none" @click="sort = 'name'; dir = dir === 'asc' ? 'desc' : 'asc'">
                                    Nama <i class="fas ml-1" :class="sort === 'name' ? (dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                </th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 cursor-pointer select-none" @click="sort = 'total'; dir = dir === 'asc' ? 'desc' : 'asc'">
                                    Total <i class="fas ml-1" :class="sort === 'total' ? (dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                </th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Verified</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Rejected</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600 cursor-pointer select-none" @click="sort = 'rate'; dir = dir === 'asc' ? 'desc' : 'asc'">
                                    Rate <i class="fas ml-1" :class="sort === 'rate' ? (dir === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                                </th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($adminData as $admin)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3">
                                    <p class="font-medium text-gray-900">{{ $admin['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $admin['email'] }}</p>
                                </td>
                                <td class="px-3 py-3 font-semibold text-gray-900">{{ $admin['total'] }}</td>
                                <td class="px-3 py-3 text-green-700">{{ $admin['verified'] }}</td>
                                <td class="px-3 py-3 text-red-700">{{ $admin['rejected'] }}</td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold {{ $admin['approval_rate'] >= 80 ? 'bg-green-100 text-green-700' : ($admin['approval_rate'] >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $admin['approval_rate'] }}%
                                    </span>
                                </td>
                                <td class="px-3 py-3">
                                    <a href="{{ route('atasan.rekap-admin.detail', $admin['id']) }}" class="inline-flex items-center gap-1 text-sm font-medium text-disnaker-600 hover:text-disnaker-700">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-12 text-center text-gray-500">
                                    <i class="fas fa-user-shield mr-2"></i>Belum ada data admin.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div> {{-- end x-show loaded --}}
</div>
@endsection
