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

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl bg-white p-6 card-shadow">
            <p class="text-sm text-gray-500">Total Pengguna</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalUsers ?? 0) }}</p>
        </div>
        <div class="rounded-xl bg-white p-6 card-shadow">
            <p class="text-sm text-gray-500">Pengguna Aktif</p>
            <p class="mt-2 text-3xl font-bold text-green-600">{{ number_format($totalActiveUsers ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-500">Proxy: user dengan update status pekerjaan.</p>
        </div>
        <div class="rounded-xl bg-white p-6 card-shadow">
            <p class="text-sm text-gray-500">Perlu Perhatian</p>
            <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($usersNeedAttention ?? 0) }}</p>
            <a href="{{ route('admin.pengguna.perhatian') }}" class="mt-2 inline-block text-sm font-medium text-disnaker-600 hover:text-disnaker-700">Lihat daftar</a>
        </div>
        <div class="rounded-xl bg-white p-6 card-shadow">
            <p class="text-sm text-gray-500">Verifikasi</p>
            <div class="mt-2 space-y-1 text-sm">
                <p><span class="font-semibold text-yellow-700">Pending:</span> {{ $pending ?? 0 }}</p>
                <p><span class="font-semibold text-green-700">Verified:</span> {{ $verified ?? 0 }}</p>
                <p><span class="font-semibold text-red-700">Rejected:</span> {{ $rejected ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
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
        </div>

        <div class="rounded-xl bg-white p-6 card-shadow lg:col-span-2">
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
    </div>

     <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
         <div class="rounded-xl bg-white p-6 card-shadow">
             <h2 class="text-lg font-semibold text-gray-900">Wilayah Prioritas Intervensi</h2>
             <p class="mt-1 text-xs text-gray-500">Skor prioritas berdasarkan jumlah belum bekerja + pending verifikasi.</p>
              <div class="mt-4 table-scroll-wrapper">
                  {{-- Desktop table --}}
                  <table class="hidden min-w-full divide-y divide-gray-200 text-sm sm:table">
                     <thead class="bg-gray-50">
                         <tr>
                             <th class="px-3 py-2 text-left font-semibold text-gray-600">Kecamatan</th>
                             <th class="px-3 py-2 text-left font-semibold text-gray-600">Belum Bekerja</th>
                             <th class="px-3 py-2 text-left font-semibold text-gray-600">Sudah Bekerja</th>
                             <th class="px-3 py-2 text-left font-semibold text-gray-600">Pending Verifikasi</th>
                             <th class="px-3 py-2 text-left font-semibold text-gray-600">Total User</th>
                         </tr>
                     </thead>
                     <tbody class="divide-y divide-gray-100">
                         @forelse(($topAttentionDistricts ?? []) as $district)
                             <tr>
                                 <td class="px-3 py-2 font-medium text-gray-900">{{ $district['kecamatan'] }}</td>
                                 <td class="px-3 py-2 text-yellow-700">{{ $district['belum_bekerja'] }}</td>
                                 <td class="px-3 py-2 text-green-700">{{ $district['sudah_bekerja'] }}</td>
                                 <td class="px-3 py-2 text-red-700">{{ $district['pending_verifikasi'] }}</td>
                                 <td class="px-3 py-2 text-gray-700">{{ $district['total'] }}</td>
                             </tr>
                         @empty
                             <tr><td colspan="5" class="px-3 py-12 text-center text-gray-500"><i class="fas fa-info-circle mr-2"></i>Belum ada ringkasan wilayah.</td></tr>
                         @endforelse
                     </tbody>
                 </table>
                 {{-- Mobile cards --}}
                 <div class="sm:hidden space-y-3 p-4">
                     @forelse(($topAttentionDistricts ?? []) as $district)
                         <x-mobile-card
                             :title="$district['kecamatan']"
                             :subtitle="$district['total'] . ' total user'"
                             status="red"
                             :badge="$district['pending_verifikasi'] . ' pending'"
                             badgeColor="red"
                             expandable
                         >
                             <div class="mobile-card-field">
                                 <i class="fas fa-exclamation-triangle mobile-card-field-icon text-yellow-600"></i>
                                 <span class="mobile-card-field-label">Belum Bekerja</span>
                                 <span class="mobile-card-field-value font-semibold text-yellow-700">{{ $district['belum_bekerja'] }}</span>
                             </div>
                             <div class="mobile-card-field">
                                 <i class="fas fa-check-circle mobile-card-field-icon text-green-600"></i>
                                 <span class="mobile-card-field-label">Sudah Bekerja</span>
                                 <span class="mobile-card-field-value font-semibold text-green-700">{{ $district['sudah_bekerja'] }}</span>
                             </div>
                             <x-slot:expand>
                                 <div class="mobile-card-field">
                                     <i class="fas fa-clock mobile-card-field-icon text-red-600"></i>
                                     <span class="mobile-card-field-label">Pending Verifikasi</span>
                                     <span class="mobile-card-field-value text-red-700">{{ $district['pending_verifikasi'] }}</span>
                                 </div>
                                 <div class="mobile-card-field">
                                     <i class="fas fa-users mobile-card-field-icon"></i>
                                     <span class="mobile-card-field-label">Total User</span>
                                     <span class="mobile-card-field-value">{{ $district['total'] }}</span>
                                 </div>
                             </x-slot:expand>
                         </x-mobile-card>
                     @empty
                         <div class="py-8 text-center text-sm text-gray-500"><i class="fas fa-info-circle mr-2"></i>Belum ada ringkasan wilayah.</div>
                     @endforelse
                 </div>
             </div>
         </div>

         <div class="rounded-xl bg-white p-6 card-shadow">
             <h2 class="text-lg font-semibold text-gray-900">Top Rasio Sudah Bekerja</h2>
             <p class="mt-1 text-xs text-gray-500">Peringkat kecamatan dengan rasio user bekerja tertinggi.</p>
             <div class="mt-4 space-y-3">
                 @forelse(($topWorkingDistricts ?? []) as $district)
                     <div class="rounded-lg border border-gray-200 p-3">
                         <div class="flex items-center justify-between">
                             <p class="font-medium text-gray-900">{{ $district['kecamatan'] }}</p>
                             <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">{{ $district['rasio_bekerja'] }}%</span>
                         </div>
                         <p class="mt-1 text-xs text-gray-600">{{ $district['sudah_bekerja'] }} dari {{ $district['total'] }} pengguna berstatus sudah bekerja.</p>
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

      <!-- Charts Row -->
      <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
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
