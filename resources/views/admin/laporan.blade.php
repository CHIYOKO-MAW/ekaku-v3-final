@extends('layouts.app')

@section('title', 'Laporan Status')

@section('content')
<div class="page-wrap">
    <div class="mb-6 rounded-xl bg-white p-6 card-shadow">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Status Pengguna</h1>
        <p class="mt-1 text-sm text-gray-600">Rekap status pekerjaan dan verifikasi untuk evaluasi operasional.</p>
    </div>

    <form method="GET" class="mb-6 grid grid-cols-1 gap-3 rounded-xl bg-white p-4 card-shadow md:grid-cols-6">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" />
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" />
        <select name="status" class="form-input">
            <option value="">Semua Status</option>
            <option value="belum_bekerja" @selected(request('status') === 'belum_bekerja')>Belum Bekerja</option>
            <option value="sudah_bekerja" @selected(request('status') === 'sudah_bekerja')>Sudah Bekerja</option>
        </select>
        <div x-data="{
            loading: false,
            error: false,
            async loadVillages(code) {
                this.loading = true;
                this.error = false;
                const el = $refs.kelurahan;
                el.innerHTML = '<option value=\"\">Memuat desa...</option>';
                try {
                    const res = await fetch(`/admin/wilayah/${code}/villages`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const payload = await res.json();
                    el.innerHTML = '<option value=\"\">Semua Desa/Kelurahan</option>';
                    (payload.villages || []).forEach((v) => {
                        const opt = document.createElement('option');
                        opt.value = v.name;
                        opt.textContent = v.name;
                        el.appendChild(opt);
                    });
                } catch {
                    el.innerHTML = '<option value=\"\" class=\"text-red-500\">Gagal memuat desa</option>';
                    this.error = true;
                } finally {
                    this.loading = false;
                }
            }
        }" class="contents">
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
        </div>
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
            <table class="min-w-full divide-y divide-gray-200 text-sm">
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
        </div>
        <div class="border-t border-gray-100 px-4 py-3">{{ $rows->links() }}</div>
    </div>
</div>


@endsection
