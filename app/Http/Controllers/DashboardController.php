<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'atasan' => redirect()->route('atasan.dashboard'),
            default => redirect()->route('dashboard.user'),
        };
    }

    public function user()
    {
        $user = Auth::user();
        $userId = $user->id;

        $biodata = Cache::remember("dashboard.user.{$userId}.biodata", 60, fn () => $user->biodata);
        $status = Cache::remember("dashboard.user.{$userId}.status", 60, fn () => $user->statusPekerjaan);
        $kartuKuning = Cache::remember("dashboard.user.{$userId}.kartu", 60, fn () => $user->kartuKuning);

        $notifications = Cache::remember("dashboard.user.{$userId}.notifications", 60, function () use ($user) {
            return $user->notificationsE()->latest()->take(5)->get();
        });
        $unreadCount = Cache::remember("dashboard.user.{$userId}.unread", 60, function () use ($user) {
            return $user->notificationsE()->where('status_baca', false)->count();
        });

        View::share('unreadNotes', $unreadCount);
        View::share('latestNotes', $notifications);

        return view('user.dashboard', compact('user', 'biodata', 'status', 'notifications', 'unreadCount'));
    }

    public function admin()
    {
        $districts = $this->getPandeglangWilayahData()['districts'];

        $user = Auth::user();
        $notifications = Cache::remember("dashboard.admin.{$user->id}.notifications", 60, function () use ($user) {
            return $user->notificationsE()->latest()->take(5)->get();
        });
        $unreadCount = Cache::remember("dashboard.admin.{$user->id}.unread", 60, function () use ($user) {
            return $user->notificationsE()->where('status_baca', false)->count();
        });
        \Illuminate\Support\Facades\View::share('unreadNotes', $unreadCount);
        \Illuminate\Support\Facades\View::share('latestNotes', $notifications);

        $stats = Cache::remember('dashboard.admin.stats', 300, function () {
            $verificationCounts = Biodata::selectRaw('status_verifikasi, count(*) as total')
                ->groupBy('status_verifikasi')
                ->pluck('total', 'status_verifikasi');

            return [
                'totalUsers' => User::where('role', 'user')->count(),
                'totalActiveUsers' => StatusPekerjaan::distinct('user_id')->count('user_id'),
                'usersNeedAttention' => User::where('role', 'user')
                    ->where(function ($q) {
                        $q->doesntHave('biodata')
                            ->orWhereHas('biodata', fn ($qb) => $qb->where('status_verifikasi', '!=', 'verified'));
                    })->count(),
                'pending' => $verificationCounts->get('pending', 0),
                'verified' => $verificationCounts->get('verified', 0),
                'rejected' => $verificationCounts->get('rejected', 0),
            ];
        });

        $statusDistribution = Cache::remember('dashboard.admin.statusDistribution', 300, function () {
            return StatusPekerjaan::selectRaw('status_pekerjaan, count(*) as total')
                ->groupBy('status_pekerjaan')
                ->pluck('total', 'status_pekerjaan');
        });

        $verificationData = [
            'labels' => ['Pending', 'Verified', 'Rejected'],
            'data' => [$stats['pending'], $stats['verified'], $stats['rejected']],
            'colors' => ['#ffc107', '#28a745', '#dc3545'],
        ];

        $jobStatusLabels = [];
        $jobStatusData = [];
        $jobStatusColors = ['#ffc107', '#28a745', '#17a2b8', '#fd7e14', '#6f42c1'];

        $statusMapping = [
            'belum_bekerja' => 'Belum Bekerja',
            'sudah_bekerja' => 'Sudah Bekerja',
            'pelatihan' => 'Sedang Pelatihan',
            'pensiun' => 'Pensiun',
            'cuti' => 'Cuti',
        ];

        foreach ($statusMapping as $key => $label) {
            if ($statusDistribution->has($key)) {
                $jobStatusLabels[] = $label;
                $jobStatusData[] = $statusDistribution->get($key);
            }
        }

        $recentActivities = Cache::remember('dashboard.admin.recentActivities', 300, function () {
            return User::where('role', 'user')
                ->with(['biodata', 'statusPekerjaan'])
                ->whereHas('biodata')
                ->latest()
                ->take(8)
                ->get();
        });

        $regionalSummary = Cache::remember('dashboard.admin.regionalSummary', 3600, function () use ($districts) {
            return $this->buildRegionalSummary($districts);
        });
        $topAttentionDistricts = $regionalSummary['topAttentionDistricts'];
        $topWorkingDistricts = $regionalSummary['topWorkingDistricts'];

        return view('admin.dashboard', array_merge(
            $stats,
            compact(
                'statusDistribution',
                'verificationData',
                'jobStatusLabels',
                'jobStatusData',
                'jobStatusColors',
                'recentActivities',
                'topAttentionDistricts',
                'topWorkingDistricts',
            )
        ));
    }

    public function laporanAdmin()
    {
        $wilayahData = $this->getPandeglangWilayahData();
        $districts = $wilayahData['districts'];
        $selectedDistrictCode = (string) request('kecamatan_code', '');
        $selectedVillageName = (string) request('kelurahan_name', '');
        $villagesForSelectedDistrict = $this->villagesForDistrict($districts, $selectedDistrictCode);

        $totalUser = User::where('role', 'user')->count();
        $query = $this->buildLaporanQuery(request(), $districts);
        $rows = $query->latest()->paginate(15)->withQueryString();

        $areaMap = $this->buildAreaMap(
            $rows->items(),
            $districts,
            $selectedDistrictCode,
            $selectedVillageName
        );

        $statusStats = (clone $query)
            ->reorder()
            ->join('status_pekerjaan', 'users.id', '=', 'status_pekerjaan.user_id')
            ->selectRaw('status_pekerjaan.status_pekerjaan, count(*) as total')
            ->groupBy('status_pekerjaan.status_pekerjaan')
            ->pluck('total', 'status_pekerjaan.status_pekerjaan');
        $pendingVerifikasi = Biodata::where('status_verifikasi', 'pending')->count();

        return view('admin.laporan', compact(
            'totalUser',
            'statusStats',
            'rows',
            'pendingVerifikasi',
            'districts',
            'villagesForSelectedDistrict',
            'selectedDistrictCode',
            'selectedVillageName',
            'areaMap'
        ));
    }

    public function exportLaporanCsv(Request $request): StreamedResponse
    {
        $districts = $this->getPandeglangWilayahData()['districts'];
        $selectedDistrictCode = (string) $request->query('kecamatan_code', '');
        $selectedVillageName = (string) $request->query('kelurahan_name', '');
        $rows = $this->buildLaporanQuery($request, $districts)->latest()->get();
        $areaMap = $this->buildAreaMap($rows, $districts, $selectedDistrictCode, $selectedVillageName);

        $filename = 'laporan-status-pandeglang-'.now()->format('Ymd-His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($rows, $areaMap): void {
            $handle = fopen('php://output', 'wb');
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Nama', 'Email', 'NIK', 'Kecamatan', 'Kelurahan/Desa', 'Status Verifikasi', 'Status Pekerjaan', 'Tanggal Update']);

            foreach ($rows as $row) {
                $area = $areaMap[$row->id] ?? ['kecamatan' => '-', 'kelurahan' => '-'];
                fputcsv($handle, [
                    $row->name,
                    $row->email,
                    optional($row->biodata)->nik ?? '-',
                    $area['kecamatan'],
                    $area['kelurahan'],
                    optional($row->biodata)->status_verifikasi ?? 'pending',
                    optional($row->statusPekerjaan)->status_pekerjaan ?? '-',
                    $row->statusPekerjaan?->tanggal_update?->format('Y-m-d') ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportLaporanPdf(Request $request)
    {
        $districts = $this->getPandeglangWilayahData()['districts'];
        $selectedDistrictCode = (string) $request->query('kecamatan_code', '');
        $selectedVillageName = (string) $request->query('kelurahan_name', '');
        $rows = $this->buildLaporanQuery($request, $districts)->latest()->get();
        $areaMap = $this->buildAreaMap($rows, $districts, $selectedDistrictCode, $selectedVillageName);

        $pdf = Pdf::loadView('admin.laporan-export-pdf', [
            'rows' => $rows,
            'areaMap' => $areaMap,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-status-pandeglang-'.now()->format('Ymd-His').'.pdf');
    }

    public function villagesByDistrict(string $districtCode): JsonResponse
    {
        $districts = $this->getPandeglangWilayahData()['districts'];
        $villages = $this->villagesForDistrict($districts, $districtCode);

        return response()->json([
            'district_code' => $districtCode,
            'villages' => collect($villages)->map(fn ($v) => [
                'code' => $v['code'] ?? null,
                'name' => $v['name'] ?? null,
            ])->values(),
        ]);
    }

    public function atasan()
    {
        $user = Auth::user();
        $notifications = Cache::remember("dashboard.atasan.{$user->id}.notifications", 60, function () use ($user) {
            return $user->notificationsE()->latest()->take(5)->get();
        });
        $unreadCount = Cache::remember("dashboard.atasan.{$user->id}.unread", 60, function () use ($user) {
            return $user->notificationsE()->where('status_baca', false)->count();
        });
        \Illuminate\Support\Facades\View::share('unreadNotes', $unreadCount);
        \Illuminate\Support\Facades\View::share('latestNotes', $notifications);

        $stats = Cache::remember('dashboard.atasan.stats', 300, function () {
            return [
                'totalAdmin' => User::where('role', 'admin')->count(),
                'totalUser' => User::where('role', 'user')->count(),
                'pending' => Biodata::where('status_verifikasi', 'pending')->count(),
                'verified' => Biodata::where('status_verifikasi', 'verified')->count(),
                'rejected' => Biodata::where('status_verifikasi', 'rejected')->count(),
                'statusDistribution' => StatusPekerjaan::selectRaw('status_pekerjaan, count(*) as total')
                    ->groupBy('status_pekerjaan')
                    ->pluck('total', 'status_pekerjaan'),
            ];
        });

        return view('atasan.dashboard', $stats);
    }

    public function rekapAdmin()
    {
        $admins = User::where('role', 'admin')->latest()->get();

        $verifikasiPerAdmin = Biodata::selectRaw('verifikator_id, count(*) as total')
            ->whereNotNull('verifikator_id')
            ->groupBy('verifikator_id')
            ->pluck('total', 'verifikator_id');

        $verifiedPerAdmin = Biodata::selectRaw('verifikator_id, count(*) as total')
            ->where('status_verifikasi', 'verified')
            ->whereNotNull('verifikator_id')
            ->groupBy('verifikator_id')
            ->pluck('total', 'verifikator_id');

        $rejectedPerAdmin = Biodata::selectRaw('verifikator_id, count(*) as total')
            ->where('status_verifikasi', 'rejected')
            ->whereNotNull('verifikator_id')
            ->groupBy('verifikator_id')
            ->pluck('total', 'verifikator_id');

        return view('atasan.rekap-admin', compact('admins', 'verifikasiPerAdmin', 'verifiedPerAdmin', 'rejectedPerAdmin'));
    }

    private function buildLaporanQuery(Request $request, array $districts)
    {
        $query = User::query()
            ->where('role', 'user')
            ->with(['biodata', 'statusPekerjaan']);

        $status = (string) $request->query('status', '');
        if ($status !== '') {
            $query->whereHas('statusPekerjaan', fn ($q) => $q->where('status_pekerjaan', $status));
        }

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        if ($startDate || $endDate) {
            $query->whereHas('statusPekerjaan', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('tanggal_update', '>=', $startDate);
                }
                if ($endDate) {
                    $q->whereDate('tanggal_update', '<=', $endDate);
                }
            });
        }

        $districtCode = (string) $request->query('kecamatan_code', '');
        $district = collect($districts)->firstWhere('code', $districtCode);
        if ($district) {
            $districtName = Str::lower((string) $district['name']);
            $query->whereHas('biodata', function ($q) use ($districtName) {
                $q->whereRaw('LOWER(COALESCE(kecamatan, "")) LIKE ?', ['%'.$districtName.'%'])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ['%'.$districtName.'%']);
            });
        }

        $villageName = trim(mb_substr((string) $request->query('kelurahan_name', ''), 0, 100));
        if ($villageName !== '') {
            $villageNameLower = Str::lower($villageName);
            $query->whereHas('biodata', function ($q) use ($villageNameLower) {
                $q->whereRaw('LOWER(COALESCE(desa_kelurahan, "")) LIKE ?', ['%'.$villageNameLower.'%'])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ['%'.$villageNameLower.'%']);
            });
        }

        return $query;
    }

    private function getPandeglangWilayahData(): array
    {
        return Cache::remember('dashboard.wilayahData', 86400, function () {
            $localPath = database_path('data/pandeglang_wilayah.json');
            if (is_file($localPath)) {
                $parsed = json_decode((string) file_get_contents($localPath), true);
                if (is_array($parsed) && isset($parsed['districts']) && is_array($parsed['districts'])) {
                    return $parsed;
                }
            }

            return ['districts' => []];
        });
    }

    private function villagesForDistrict(array $districts, string $districtCode): array
    {
        if ($districtCode === '') {
            return [];
        }

        $district = collect($districts)->firstWhere('code', $districtCode);
        if (! $district || ! isset($district['villages']) || ! is_array($district['villages'])) {
            return [];
        }

        return $district['villages'];
    }

    private function buildAreaMap(iterable $rows, array $districts, string $selectedDistrictCode, string $selectedVillageName): array
    {
        $areaMap = [];
        $districtNames = collect($districts)->pluck('name', 'code')->toArray();
        $selectedDistrictName = $districtNames[$selectedDistrictCode] ?? null;
        $selectedDistrictVillages = $this->villagesForDistrict($districts, $selectedDistrictCode);

        foreach ($rows as $row) {
            $address = (string) optional($row->biodata)->alamat;
            $districtName = $selectedDistrictName
                ?: (optional($row->biodata)->kecamatan ?: $this->findNameInAddress($address, array_values($districtNames)));

            $villageName = $selectedVillageName ?: (optional($row->biodata)->desa_kelurahan ?? '');
            if ($villageName === '' && $selectedDistrictCode !== '') {
                $villageName = $this->findNameInAddress($address, collect($selectedDistrictVillages)->pluck('name')->all());
            }

            $areaMap[$row->id] = [
                'kecamatan' => $districtName ?: '-',
                'kelurahan' => $villageName ?: '-',
            ];
        }

        return $areaMap;
    }

    private function findNameInAddress(string $address, array $names): ?string
    {
        $addressNormalized = Str::lower($address);
        $sortedNames = collect($names)->filter()->sortByDesc(fn ($name) => strlen((string) $name));

        foreach ($sortedNames as $name) {
            $needle = Str::lower((string) $name);
            if ($needle !== '' && str_contains($addressNormalized, $needle)) {
                return (string) $name;
            }
        }

        return null;
    }

    private function buildRegionalSummary(array $districts): array
    {
        if (empty($districts)) {
            return [
                'topAttentionDistricts' => collect(),
                'topWorkingDistricts' => collect(),
            ];
        }

        // Pre-sort district names ONCE (was re-sorted per user causing O(N*M) allocation)
        $sortedDistricts = collect($districts)
            ->pluck('name')
            ->filter()
            ->sortByDesc(fn ($name) => strlen((string) $name))
            ->values()
            ->all();

        // Use select()+join() instead of full Eloquent model hydration (saves memory + CPU)
        $rows = User::where('role', 'user')
            ->join('biodata', 'users.id', '=', 'biodata.user_id')
            ->leftJoin('status_pekerjaan', 'users.id', '=', 'status_pekerjaan.user_id')
            ->select('biodata.alamat', 'biodata.status_verifikasi', 'status_pekerjaan.status_pekerjaan')
            ->get();

        $bucket = [];

        foreach ($rows as $row) {
            $address = (string) $row->alamat;
            $district = $this->findNameInAddress($address, $sortedDistricts) ?: 'Tidak Terpetakan';
            if (! isset($bucket[$district])) {
                $bucket[$district] = [
                    'kecamatan' => $district,
                    'total' => 0,
                    'belum_bekerja' => 0,
                    'sudah_bekerja' => 0,
                    'pending_verifikasi' => 0,
                ];
            }

            $bucket[$district]['total']++;
            if ($row->status_pekerjaan === 'belum_bekerja') {
                $bucket[$district]['belum_bekerja']++;
            } elseif ($row->status_pekerjaan === 'sudah_bekerja') {
                $bucket[$district]['sudah_bekerja']++;
            }

            if ($row->status_verifikasi !== 'verified') {
                $bucket[$district]['pending_verifikasi']++;
            }
        }

        $topAttentionDistricts = collect($bucket)
            ->sortByDesc(fn ($item) => ($item['belum_bekerja'] * 2) + $item['pending_verifikasi'])
            ->take(5)
            ->values();

        $topWorkingDistricts = collect($bucket)
            ->filter(fn ($item) => $item['total'] > 0)
            ->sortByDesc(fn ($item) => $item['sudah_bekerja'] / max(1, $item['total']))
            ->take(5)
            ->map(function ($item) {
                $item['rasio_bekerja'] = round(($item['sudah_bekerja'] / max(1, $item['total'])) * 100, 1);

                return $item;
            })
            ->values();

        return compact('topAttentionDistricts', 'topWorkingDistricts');
    }
}
