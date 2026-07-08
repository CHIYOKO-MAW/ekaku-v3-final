@extends('layouts.app')

@section('title', 'Detail Kinerja - ' . $admin->name)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON --}}
    <div x-show="!loaded" class="mb-8 rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
        <div class="space-y-3">
            <x-skeleton type="title" class="w-1/3" />
            <x-skeleton type="text" class="w-1/2" />
        </div>
    </div>

    <div x-show="!loaded" class="grid grid-cols-1 gap-6 md:grid-cols-4">
        @foreach([1,2,3,4] as $i)
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="text-sm" class="w-1/2" />
            <x-skeleton type="title" class="mt-2 w-1/3" />
        </div>
        @endforeach
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-8">

        {{-- HEADER --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('atasan.rekap-admin') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-disnaker-600">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $admin->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $admin->email }}</p>
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
        <div class="rounded-xl bg-white p-6 card-shadow">
            <form method="GET" action="{{ route('atasan.rekap-admin.detail', $admin->id) }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-input rounded-lg border-gray-300 text-sm" />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-input rounded-lg border-gray-300 text-sm" />
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-disnaker-600 px-4 py-2 text-sm font-medium text-white hover:bg-disnaker-700 transition">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($startDate || $endDate)
                    <a href="{{ route('atasan.rekap-admin.detail', $admin->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 transition">
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
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($total) }}</p>
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
                        <p class="text-2xl font-bold text-green-700">{{ number_format($verified) }}</p>
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
                        <p class="text-2xl font-bold text-red-700">{{ number_format($rejected) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 card-shadow">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                        <i class="fas fa-percentage text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approval Rate</p>
                        <p class="text-2xl font-bold {{ $approvalRate >= 80 ? 'text-green-700' : ($approvalRate >= 50 ? 'text-yellow-700' : 'text-red-700') }}">{{ $approvalRate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MONTHLY CHART --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tren Verifikasi (6 Bulan Terakhir)</h2>
            @if($monthlyData->isNotEmpty())
            <div class="relative" style="height: 280px;">
                <canvas id="monthlyChart"
                    data-ekaku-chart='@json(['labels' => $monthlyData->pluck('label'), 'values' => $monthlyData->pluck('verified'), 'colors' => ['#28a745']])'
                    data-chart-type="bar">
                </canvas>
            </div>
            @else
            <div class="flex h-48 items-center justify-center text-sm text-gray-500">
                <i class="fas fa-chart-bar mr-2"></i>Belum ada data tren.
            </div>
            @endif
        </div>

        {{-- DETAIL TABLE --}}
        <div class="rounded-xl bg-white p-6 card-shadow">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Verifikasi</h2>
            <div class="table-scroll-wrapper">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">NIK</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Tanggal Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($biodataList as $biodata)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 font-medium text-gray-900">{{ $biodata->user?->name ?? '-' }}</td>
                            <td class="px-3 py-3 text-gray-700">{{ $biodata->nik ?? '-' }}</td>
                            <td class="px-3 py-3">
                                @php $sv = $biodata->status_verifikasi; @endphp
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $sv === 'verified' ? 'bg-green-100 text-green-700' : ($sv === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ strtoupper($sv) }}</span>
                            </td>
                            <td class="px-3 py-3 text-gray-700">{{ $biodata->tanggal_verifikasi ? \Carbon\Carbon::parse($biodata->tanggal_verifikasi)->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox mr-2"></i>Belum ada data verifikasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $biodataList->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
