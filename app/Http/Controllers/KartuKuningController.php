<?php

namespace App\Http\Controllers;

use App\Models\KartuKuning;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KartuKuningController extends Controller
{
    public function show()
    {
        return $this->preview((int) Auth::id());
    }

    public function download()
    {
        return $this->generatePdf((int) Auth::id());
    }

    public function preview(int $id)
    {
        try {
            $currentUser = Auth::user();

            if (! $currentUser) {
                abort(403);
            }

            if (! in_array($currentUser->role, ['admin', 'atasan'], true) && $currentUser->id !== $id) {
                abort(403);
            }

            $user = User::with(['biodata', 'kartuKuning'])->findOrFail($id);

            if (
                $currentUser->id === $user->id &&
                (! $user->biodata || $user->biodata->status_verifikasi !== 'verified')
            ) {
                return redirect()->route($user->biodata ? 'biodata.show' : 'biodata.create', $user->biodata ? [$user->biodata->id] : [])
                    ->with('error', 'Biodata Anda belum diverifikasi admin. Kartu kuning belum bisa dipreview.');
            }

            $this->ensureKartuKuningReady($user);

            $qrBase64 = Cache::remember('kartu.qr_svg.'.$user->id, 86400, function () use ($user) {
                $svg = QrCode::format('svg')
                    ->size(200)
                    ->generate($user->qr_code);

                return base64_encode($svg);
            });

            return view('user.card-show', [
                'user' => $user,
                'qrBase64' => $qrBase64,
                'validUntil' => now()->addYears(2),
            ]);
        } catch (\Throwable $e) {
            Log::error('Card preview failed for user '.$id, [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            abort(500, 'Gagal preview kartu: '.$e->getMessage());
        }
    }

    public function generatePdf(int $id)
    {
        ini_set('memory_limit', '512M');

        try {
            $currentUser = Auth::user();

            if (! $currentUser) {
                abort(403);
            }

            if (! in_array($currentUser->role, ['admin', 'atasan'], true) && $currentUser->id !== $id) {
                abort(403);
            }

            $user = User::with(['biodata', 'kartuKuning'])->findOrFail($id);

            if (
                $currentUser->id === $user->id &&
                (! $user->biodata || $user->biodata->status_verifikasi !== 'verified')
            ) {
                return redirect()->route($user->biodata ? 'biodata.show' : 'biodata.create', $user->biodata ? [$user->biodata->id] : [])
                    ->with('error', 'Biodata Anda belum diverifikasi admin. Kartu kuning belum bisa dicetak.');
            }

            $this->ensureKartuKuningReady($user);

            $user->kartuKuning()->updateOrCreate(
                ['user_id' => $user->id],
                ['tanggal_cetak' => now()]
            );

            $user->refresh(['biodata', 'kartuKuning']);

            if (! $user->kartuKuning->qr_token || ($user->kartuKuning->expired_at && $user->kartuKuning->expired_at->isPast())) {
                $user->generateQrCode();
                $user->refresh(['biodata', 'kartuKuning']);
            }

            $qrBase64 = Cache::remember('kartu.qr_svg.'.$user->id, 86400, function () use ($user) {
                $svg = QrCode::format('svg')
                    ->size(200)
                    ->generate($user->qr_code);

                return base64_encode($svg);
            });

            $logoBase64 = $this->getLogoBase64();

            $freshUser = $user->fresh(['biodata', 'kartuKuning']);

            $photoBase64 = null;
            if ($freshUser->profile_photo_path) {
                $photoPath = storage_path('app/public/'.ltrim($freshUser->profile_photo_path, '/'));
                if (file_exists($photoPath)) {
                    $mime = mime_content_type($photoPath);
                    $photoBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($photoPath));
                }
            }

            $pdf = Pdf::loadView('kartu.cetak', [
                'user' => $freshUser,
                'qrBase64' => $qrBase64,
                'logoBase64' => $logoBase64,
                'photoBase64' => $photoBase64,
                'validUntil' => now()->addYears(2),
            ])->setPaper('a4', 'portrait');

            $pdfOutput = $pdf->output();

            return response($pdfOutput, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="kartu-kuning-'.$user->id.'.pdf"',
            ]);
        } catch (\Throwable $e) {
            Log::error('PDF generation failed for user '.$id, [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'file' => str_replace(base_path(), '', $e->getFile()),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'file' => str_replace(base_path(), '', $e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    private function getLogoBase64(): ?string
    {
        foreach (['png', 'webp'] as $ext) {
            $logoPath = public_path("images/lambang-pandeglang.{$ext}");
            if (file_exists($logoPath)) {
                $mime = mime_content_type($logoPath);
                return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
            }
        }
        Log::warning('Logo file not found in public/images/');
        return null;
    }

    /**
     * Endpoint verifikasi publik — diakses saat QR discan.
     * TIDAK memerlukan login.
     */
    public function verify(Request $request, string $nomor_ak1)
    {
        $token = $request->query('token', '');

        // Cari kartu berdasarkan nomor_ak1
        $kartu = KartuKuning::where('nomor_ak1', $nomor_ak1)->first();

        // Tentukan status validitas
        $isValid = false;
        $errorMessage = null;

        if (! $kartu) {
            $errorMessage = 'Kartu dengan nomor ini tidak ditemukan dalam sistem.';
        } elseif (! $token || ! hash_equals((string) $kartu->qr_token, $token)) {
            $errorMessage = 'Token keamanan tidak valid. Kartu ini kemungkinan dipalsukan.';
        } elseif ($kartu->status === 'expired') {
            $errorMessage = 'Kartu ini sudah kadaluarsa.';
        } elseif ($kartu->expired_at && $kartu->expired_at->isPast()) {
            $errorMessage = 'Token verifikasi QR sudah kadaluarsa.';
            // Update kartu status to expired
            $kartu->update(['status' => 'expired']);
        } else {
            $user = User::with(['biodata', 'kartuKuning'])->find($kartu->user_id);
            if (! $user || ! $user->biodata || $user->biodata->status_verifikasi !== 'verified') {
                $errorMessage = 'Biodata pemilik kartu belum atau tidak lagi diverifikasi.';
            } else {
                $isValid = true;
            }
        }

        // Jika tidak valid, tampilkan halaman error
        if (! $isValid) {
            return view('kartu.verify', [
                'valid' => false,
                'errorMessage' => $errorMessage,
                'nomor_ak1' => $nomor_ak1,
            ]);
        }

        $photoBase64 = null;
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            $photoData = Storage::disk('public')->get($user->profile_photo_path);
            $mime = Storage::disk('public')->mimeType($user->profile_photo_path);
            $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode($photoData);
        }

        $kartu->update([
            'last_scanned_at' => now(),
        ]);
        $kartu->increment('scan_count');

        return view('kartu.verify', [
            'valid' => true,
            'user' => $user,
            'kartu' => $kartu->fresh(),
            'photoBase64' => $photoBase64,
            'validUntil' => $kartu->tanggal_cetak
                ? \Carbon\Carbon::parse($kartu->tanggal_cetak)->addYears(2)
                : now()->addYears(2),
            'scannedAt' => now(),
        ]);
    }

    /**
     * API endpoint untuk data scan (backward compat + extended info).
     */
    public function scanData(int $id): JsonResponse
    {
        $user = User::with(['biodata', 'kartuKuning'])->findOrFail($id);

        return response()->json([
            'valid' => (bool) $user->kartuKuning && optional($user->biodata)->status_verifikasi === 'verified',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'biodata' => [
                'nik' => optional($user->biodata)->nik
                    ? substr(optional($user->biodata)->nik, 0, 6).'**********'
                    : null,
                'tempat_lahir' => optional($user->biodata)->tempat_lahir,
                'jenis_kelamin' => optional($user->biodata)->jenis_kelamin,
                'pendidikan' => optional($user->biodata)->pendidikan,
                'institusi' => optional($user->biodata)->institusi_pendidikan,
                'jurusan' => optional($user->biodata)->jurusan,
                'tahun_lulus' => optional($user->biodata)->tahun_lulus,
                'keahlian' => optional($user->biodata)->keahlian,
                'tujuan_lamaran' => optional($user->biodata)->tujuan_lamaran,
                'status_verifikasi' => optional($user->biodata)->status_verifikasi,
            ],
            'kartu' => [
                'nomor_ak1' => optional($user->kartuKuning)->nomor_ak1,
                'tanggal_cetak' => optional($user->kartuKuning)->tanggal_cetak,
                'status' => optional($user->kartuKuning)->status,
                'scan_count' => optional($user->kartuKuning)->scan_count,
            ],
        ]);
    }

    private function ensureKartuKuningReady(User $user): void
    {
        if (! $user->hasCompletedProfile()) {
            abort(422, 'Lengkapi profil terlebih dahulu.');
        }

        if (! $user->biodata || $user->biodata->status_verifikasi !== 'verified') {
            abort(403, 'Biodata belum diverifikasi admin. Kartu kuning belum bisa dicetak.');
        }

        if (! $user->kartuKuning) {
            KartuKuning::create([
                'user_id' => $user->id,
                'nomor_ak1' => 'AK1-'.now()->format('Ymd').'-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                'tanggal_cetak' => now(),
                'status' => 'printed',
            ]);

            $user->refresh();
        }

        if (! $user->qr_code) {
            $user->generateQrCode();
            $user->refresh();
        }
    }
}
