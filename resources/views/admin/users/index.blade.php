@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<div class="page-wrap" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON --}}
    <div x-show="!loaded" class="space-y-6">
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <x-skeleton type="title" class="w-1/3" />
            <x-skeleton type="text-sm" class="mt-2 w-1/2" />
        </div>
        <div class="grid grid-cols-1 gap-3 rounded-xl bg-gray-200 p-4 card-shadow animate-pulse md:grid-cols-4">
            <x-skeleton type="btn" class="md:col-span-2" />
            <x-skeleton type="btn" />
            <x-skeleton type="btn" />
        </div>
        <div class="rounded-xl bg-gray-200 card-shadow animate-pulse">
            <div class="p-4 space-y-3">
                @foreach([1,2,3,4,5] as $i)
                <div class="flex items-center gap-4 rounded-lg bg-gray-300 p-4 animate-pulse">
                    <x-skeleton type="text" class="w-1/4" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="text-sm" class="w-1/4" />
                    <x-skeleton type="badge" />
                    <x-skeleton type="text-sm" class="w-1/6" />
                    <x-skeleton type="btn" />
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-6">
    <div class="mb-6 rounded-xl bg-white p-6 card-shadow">
        <h1 class="text-2xl font-bold text-gray-900">Data Pengguna</h1>
        <p class="mt-1 text-sm text-gray-600">Kelola user pencari kerja untuk kebutuhan operasional admin.</p>
    </div>

    <form method="GET" class="mb-4 grid grid-cols-1 gap-3 rounded-xl bg-white p-4 card-shadow md:grid-cols-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input md:col-span-2" placeholder="Cari nama, email, atau NIK...">
        <select name="status" class="form-input">
            <option value="">Semua Status</option>
            <option value="belum_bekerja" @selected(request('status') === 'belum_bekerja')>Belum Bekerja</option>
            <option value="sudah_bekerja" @selected(request('status') === 'sudah_bekerja')>Sudah Bekerja</option>
            <option value="pelatihan" @selected(request('status') === 'pelatihan')>Sedang Pelatihan</option>
        </select>
        <button type="submit" class="btn-primary justify-center">Filter</button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        <div class="table-scroll-wrapper">
            {{-- Desktop table --}}
            <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">NIK</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Verifikasi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status Pekerjaan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $item)
                        @php $sv = $item->biodata->status_verifikasi ?? 'pending'; @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $item->biodata->nik ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $item->email }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $sv === 'verified' ? 'bg-green-100 text-green-700' : ($sv === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ strtoupper($sv) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $item->statusPekerjaan?->status_pekerjaan ? str_replace('_', ' ', ucfirst($item->statusPekerjaan->status_pekerjaan)) : '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.pengguna.show', $item) }}" class="btn-secondary">Detail</a>
                                    <a href="{{ route('admin.pengguna.edit', $item) }}" class="btn-secondary">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500"><i class="fas fa-users mr-2"></i>Tidak ada data pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Mobile cards --}}
            <div class="sm:hidden space-y-3 p-4">
                @forelse($users as $item)
                    @php $sv = $item->biodata->status_verifikasi ?? 'pending'; @endphp
                    <x-mobile-card
                        :title="$item->name"
                        :subtitle="$item->email"
                        :status="$sv === 'verified' ? 'green' : ($sv === 'rejected' ? 'red' : 'yellow')"
                        :badge="str_replace('_', ' ', ucfirst($item->statusPekerjaan?->status_pekerjaan ?? '-'))"
                        expandable
                    >
                        <div class="mobile-card-field">
                            <i class="fas fa-id-card mobile-card-field-icon"></i>
                            <span class="mobile-card-field-label">NIK</span>
                            <span class="mobile-card-field-value">{{ $item->biodata->nik ?? '-' }}</span>
                        </div>
                        <div class="mobile-card-field">
                            <i class="fas fa-briefcase mobile-card-field-icon"></i>
                            <span class="mobile-card-field-label">Verifikasi</span>
                            <span class="mobile-card-field-value font-semibold {{ $sv === 'verified' ? 'text-green-700' : ($sv === 'rejected' ? 'text-red-700' : 'text-yellow-700') }}">{{ strtoupper($sv) }}</span>
                        </div>
                        <x-slot:actions>
                            <a href="{{ route('admin.pengguna.show', $item) }}" class="btn-secondary text-xs">Detail</a>
                            <a href="{{ route('admin.pengguna.edit', $item) }}" class="btn-secondary text-xs">Edit</a>
                        </x-slot:actions>
                    </x-mobile-card>
                @empty
                    <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-users mr-2"></i>Tidak ada data pengguna.</div>
                @endforelse
            </div>
        </div>
        <div class="border-t border-gray-100 px-4 py-3">{{ $users->links() }}</div>
    </div>
    </div>
</div>
@endsection
