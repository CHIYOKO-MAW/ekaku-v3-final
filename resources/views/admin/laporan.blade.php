@extends('layouts.app')

@section('title', 'Laporan Status')

@section('content')
<div class="page-wrap" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON --}}
    <div x-show="!loaded" class="space-y-6">
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="title" class="w-1/3" />
            <x-skeleton type="text-sm" class="mt-2 w-1/2" />
        </div>
        <div class="grid grid-cols-1 gap-3 rounded-xl bg-gray-200 p-4 card-shadow animate-pulse md:grid-cols-6">
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            @foreach([1,2,3,4] as $i)
            <div class="rounded-xl bg-gray-200 p-5 card-shadow animate-pulse">
                <x-skeleton type="text-sm" class="w-1/2" />
                <x-skeleton type="title" class="mt-2 w-1/3" />
            </div>
            @endforeach
        </div>
        <div class="flex gap-3">
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
        </div>
        <div class="rounded-xl bg-gray-200 card-shadow animate-pulse">
            <div class="p-4 space-y-3">
                @foreach([1,2,3,4,5] as $i)
                <div class="flex items-center gap-4 rounded-lg bg-gray-300 p-4 animate-pulse">
                    <x-skeleton type="text" class="w-1/6" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="badge" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-6">
    <div class="mb-6 rounded-xl bg-white p-6 card-shadow">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Status Pengguna</h1>
        <p class="mt-1 text-sm text-gray-600">Rekap status pekerjaan dan verifikasi untuk evaluasi operasional.</p>
    </div>

    <form method="GET" class="mb-6 grid grid-cols-1 gap-3 rounded-xl bg-white p-4 card-shadow md:grid-cols-6" x-data="laporanFilter()">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" />
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" />
        <select name="status" class="form-input">
            <option value="">Semua Status</option>
            <option value="belum_bekerja" @selected(request('status') === 'belum_bekerja')>Belum Bekerja</option>
            <option value="sudah_bekerja" @selected(request('status') === 'sudah_bekerja')>Sudah Bekerja</option>
        </select>
            <select name="kecamatan_code" class="form-input"
                x-on:change="loadVillages($event.target.value)">
                <option value="">Semua Kecamatan</option>
                @foreach(($districts ?? []) as $district)
                    <option value="{{ $district['code'] }}" @selected(($selectedDistrictCode ?? '') === $district['code'])>
                        {{ $district['name'] }}
                    </option>
                @endforeach
            </select>
            <select x-ref="kelurahan" name="kelurahan_name" class="form-input">
                <option value="">Semua Desa/Kelurahan</option>
                @foreach(($villagesForSelectedDistrict ?? []) as $village)
                    <option value="{{ $village['name'] }}" @selected(($selectedVillageName ?? '') === $village['name'])>
                    {{ $village['name'] }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary justify-center">Terapkan Filter</button>
    </form>

    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white p-5 card-shadow"><p class="text-sm text-gray-500">Total User</p><p class="text-2xl font-bold text-gray-900">{{ $totalUser ?? 0 }}</p></div>
        <div class="rounded-xl bg-white p-5 card-shadow"><p class="text-sm text-gray-500">Pending Verifikasi</p><p class="text-2xl font-bold text-yellow-700">{{ $pendingVerifikasi ?? 0 }}</p></div>
        <div class="rounded-xl bg-white p-5 card-shadow"><p class="text-sm text-gray-500">Belum Bekerja</p><p class="text-2xl font-bold text-yellow-600">{{ $statusStats['belum_bekerja'] ?? 0 }}</p></div>
        <div class="rounded-xl bg-white p-5 card-shadow"><p class="text-sm text-gray-500">Sudah Bekerja</p><p class="text-2xl font-bold text-green-600">{{ $statusStats['sudah_bekerja'] ?? 0 }}</p></div>
    </div>

    <div class="mb-3 flex items-center gap-3">
        <a href="{{ route('admin.laporan.export.pdf', request()->except('page')) }}" class="btn-secondary">Export PDF</a>
        <a href="{{ route('admin.laporan.export.csv', request()->except('page')) }}" class="btn-secondary">Export CSV</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        <div class="table-scroll-wrapper">
            {{-- Desktop table --}}
            <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">NIK</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kecamatan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Kelurahan/Desa</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Pekerjaan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $row->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row->email }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row->biodata->nik ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $areaMap[$row->id]['kecamatan'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $areaMap[$row->id]['kelurahan'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $row->statusPekerjaan?->status_pekerjaan ? str_replace('_', ' ', ucfirst($row->statusPekerjaan->status_pekerjaan)) : '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ optional($row->statusPekerjaan?->tanggal_update)->format('d M Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500"><i class="fas fa-file-alt mr-2"></i>Tidak ada data laporan untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Mobile cards --}}
            <div class="sm:hidden space-y-3 p-4">
                @forelse($rows as $row)
                    @php $sp = $row->statusPekerjaan?->status_pekerjaan ?? null; @endphp
                    <x-mobile-card
                        :title="$row->name"
                        :subtitle="$row->email"
                        :status="$sp === 'sudah_bekerja' ? 'green' : ($sp === 'belum_bekerja' ? 'yellow' : 'gray')"
                        :badge="$sp ? str_replace('_', ' ', ucfirst($sp)) : '-'"
                        :badgeColor="$sp === 'sudah_bekerja' ? 'green' : ($sp === 'belum_bekerja' ? 'yellow' : 'gray')"
                        expandable
                    >
                        <div class="mobile-card-field">
                            <i class="fas fa-id-card mobile-card-field-icon"></i>
                            <span class="mobile-card-field-label">NIK</span>
                            <span class="mobile-card-field-value">{{ $row->biodata->nik ?? '-' }}</span>
                        </div>
                        <x-slot:expand>
                            <div class="mobile-card-field">
                                <i class="fas fa-map-marker-alt mobile-card-field-icon"></i>
                                <span class="mobile-card-field-label">Kecamatan</span>
                                <span class="mobile-card-field-value">{{ $areaMap[$row->id]['kecamatan'] ?? '-' }}</span>
                            </div>
                            <div class="mobile-card-field">
                                <i class="fas fa-building mobile-card-field-icon"></i>
                                <span class="mobile-card-field-label">Kelurahan</span>
                                <span class="mobile-card-field-value">{{ $areaMap[$row->id]['kelurahan'] ?? '-' }}</span>
                            </div>
                            <div class="mobile-card-field">
                                <i class="fas fa-calendar mobile-card-field-icon"></i>
                                <span class="mobile-card-field-label">Update</span>
                                <span class="mobile-card-field-value">{{ optional($row->statusPekerjaan?->tanggal_update)->format('d M Y') ?? '-' }}</span>
                            </div>
                        </x-slot:expand>
                    </x-mobile-card>
                @empty
                    <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-file-alt mr-2"></i>Tidak ada data laporan untuk filter ini.</div>
                @endforelse
            </div>
        </div>
        <div class="border-t border-gray-100 px-4 py-3">{{ $rows->links() }}</div>
    </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('laporanFilter', () => ({
        loading: false,
        error: false,
        async loadVillages(code) {
            this.loading = true;
            this.error = false;
            const el = this.$refs.kelurahan;
            el.innerHTML = '<option value="">Memuat desa...</option>';
            try {
                const res = await fetch(`/admin/wilayah/${code}/villages`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const payload = await res.json();
                el.innerHTML = '<option value="">Semua Desa/Kelurahan</option>';
                (payload.villages || []).forEach((v) => {
                    const opt = document.createElement('option');
                    opt.value = v.name;
                    opt.textContent = v.name;
                    el.appendChild(opt);
                });
            } catch {
                el.innerHTML = '<option value="" class="text-red-500">Gagal memuat desa</option>';
                this.error = true;
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
@endpush
