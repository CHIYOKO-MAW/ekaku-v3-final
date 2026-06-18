<?php

use App\Http\Controllers\BiodataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KartuKuningController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusPekerjaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiController;
use App\Models\Biodata;
use App\Models\KartuKuning;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

Route::middleware(['auth', 'role:admin', DoNotCacheResponse::class])->get('/force-seed', function () {
    try {
        echo 'Starting seeding...<br>';
        Artisan::call('db:seed', ['--force' => true]);
        echo 'Seeding completed!<br>';
        echo '<pre>'.Artisan::output().'</pre>';
    } catch (\Exception $e) {
        echo 'Error: '.$e->getMessage();
    }
});

Route::get('/', function () {
    $stats = [
        'pencari_kerja' => User::count(),
        'kartu_terbit' => KartuKuning::count(),
        'biodata_terverifikasi' => Biodata::where('status_verifikasi', 'verified')->count(),
    ];

    return view('welcome', compact('stats'));
})->middleware(CacheResponse::using(3600))->name('welcome');
Route::get('/landing', fn () => redirect()->route('welcome'))->name('landing');

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(DoNotCacheResponse::class)->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/profile/photo/{user}', [ProfileController::class, 'photo'])->name('profile.photo');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/biodata', [BiodataController::class, 'index'])->name('biodata.index');
        Route::get('/biodata/create', fn () => redirect()->to(route('profile.edit').'#biodata'))->name('biodata.create');
        Route::get('/biodata/edit', fn () => redirect()->to(route('profile.edit').'#biodata'))->name('biodata.edit');
        Route::get('/biodata/{id}', fn () => redirect()->to(route('profile.edit').'#biodata'))->name('biodata.show');

        Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
        Route::get('/upload/create', [UploadController::class, 'create'])->name('upload.create');
        Route::get('/upload/{upload}', [UploadController::class, 'preview'])->name('upload.preview');

        Route::get('/kartu-kuning', [KartuKuningController::class, 'show'])->name('kartu.show');
        Route::get('/kartu-kuning/download', [KartuKuningController::class, 'download'])->name('kartu.download.self');
        Route::get('/cetak-ak1/{id}', [KartuKuningController::class, 'preview'])->name('kartu.preview');

        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    });

    Route::post('/biodata', [BiodataController::class, 'store'])->name('biodata.store');
    Route::put('/biodata', [BiodataController::class, 'update'])->name('biodata.update');
    Route::delete('/biodata/{id}', [BiodataController::class, 'destroy'])->name('biodata.destroy');
    Route::post('/biodata/submit', [BiodataController::class, 'submitForVerification'])->name('biodata.submit');

    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::delete('/upload/{upload}', [UploadController::class, 'destroy'])->name('upload.destroy');

    Route::get('/cetak-ak1/{id}/download', [KartuKuningController::class, 'generatePdf'])->name('kartu.download');

    Route::post('/notifikasi/send', [NotificationController::class, 'sendManual'])->name('notifikasi.send');
    Route::patch('/notifikasi/{notification}/read', [NotificationController::class, 'markRead'])->name('notifikasi.read');

    Route::middleware('role:user')->group(function () {
        Route::get('/status-pekerjaan', [StatusPekerjaanController::class, 'index'])->name('status.index');
        Route::post('/status-pekerjaan', [StatusPekerjaanController::class, 'store'])->name('status.store');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->middleware(CacheResponse::using(300))
            ->name('dashboard');
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/perhatian', [UserController::class, 'attention'])->name('pengguna.perhatian');
        Route::get('/laporan', [DashboardController::class, 'laporanAdmin'])->name('laporan');
        Route::get('/wilayah/{districtCode}/villages', [DashboardController::class, 'villagesByDistrict'])->name('wilayah.villages');
        Route::view('/notifikasi', 'admin.notification')->name('notification');
        Route::get('/user-management', fn () => redirect()->route('admin.pengguna.index'))->name('user-management');

        Route::middleware(DoNotCacheResponse::class)->group(function () {
            Route::get('/verifikasi/{biodata}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
            Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('pengguna.edit');
            Route::get('/pengguna/{user}', [UserController::class, 'show'])->name('pengguna.show');
        });

        Route::match(['put', 'patch', 'post'], '/verifikasi/{biodata}', [VerifikasiController::class, 'update'])->name('verifikasi.update');
        Route::post('/verifikasi/{biodata}/status', [VerifikasiController::class, 'updateStatus'])->name('verifikasi.updateStatus');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('pengguna.destroy');
        Route::get('/laporan/export/csv', [DashboardController::class, 'exportLaporanCsv'])->name('laporan.export.csv');
        Route::get('/laporan/export/pdf', [DashboardController::class, 'exportLaporanPdf'])->name('laporan.export.pdf');
    });

    Route::middleware('role:atasan')->prefix('atasan')->name('atasan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'atasan'])->name('dashboard');
        Route::get('/rekap-admin', [DashboardController::class, 'rekapAdmin'])->name('rekap-admin');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/biodata', fn () => redirect()->route('profile.edit').'#biodata')->name('biodata');
        Route::get('/profile', fn () => redirect()->route('profile.edit'))->name('profile');
        Route::get('/kartu', fn () => redirect()->route('kartu.show'))->name('kartu');
        Route::get('/notifikasi', fn () => redirect()->route('notifikasi.index'))->name('notifikasi');
    });
});

Route::middleware(['auth', DoNotCacheResponse::class])->get('/kartu-scan/{id}', [KartuKuningController::class, 'scanData'])->name('kartu.scan.data');

// Halaman verifikasi publik — dibuka saat QR discan (tidak perlu login)
Route::middleware('throttle:30,1')->get('/verify/{nomor_ak1}', [KartuKuningController::class, 'verify'])->name('kartu.verify')
    ->middleware(CacheResponse::using(60));

Route::get('/dashboard/user', [DashboardController::class, 'user'])
    ->middleware(['auth', 'role:user'])
    ->name('dashboard.user');
