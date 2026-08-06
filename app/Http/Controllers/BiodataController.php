<?php

namespace App\Http\Controllers;

use App\Http\Requests\BiodataRequest;
use App\Models\Biodata;
use App\Models\NotificationE;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Facades\ResponseCache;

class BiodataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function clearProfileCache(): void
    {
        $userId = auth()->id();
        Cache::forget("profile.{$userId}.biodata");
        Cache::forget("profile.{$userId}.status");
        Cache::forget("profile.{$userId}.kartu");
        Cache::forget("profile.{$userId}.uploads");
        ResponseCache::forget(route('profile.edit'));
    }

    // list (admin/atasan see all, user see own)
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'atasan'], true)) {
            $datas = Biodata::with('user')->latest()->paginate(15);

            return view('biodata.index', compact('datas'));
        }

        return redirect()->to(route('profile.edit').'#biodata');
    }

    public function create()
    {
        if (Auth::user()->role === 'user') {
            return redirect()->to(route('profile.edit').'#biodata');
        }

        return view('biodata.create');
    }

    public function store(BiodataRequest $request)
    {
        $user = Auth::user();

        if ($user->biodata) {
            return redirect()->to(route('profile.edit').'#biodata');
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status_verifikasi'] = 'pending';

        try {
            $softDeletedOwnBiodata = Biodata::withTrashed()
                ->where('user_id', $user->id)
                ->whereNotNull('deleted_at')
                ->first();

            if ($softDeletedOwnBiodata) {
                $softDeletedOwnBiodata->restore();
                $softDeletedOwnBiodata->update($data);
            } else {
                Biodata::create($data);
            }
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                return back()
                    ->withInput()
                    ->withErrors(['nik' => 'NIK sudah terdaftar di sistem. Gunakan NIK yang valid milik Anda atau hubungi admin.']);
            }
            throw $e;
        }

        $userId = $user->id;
        Cache::forget("dashboard.user.{$userId}.biodata");
        $this->clearProfileCache();

        return redirect()->to(route('profile.edit').'#biodata')
            ->with('success', 'Biodata berhasil disimpan.');
    }

    public function edit()
    {
        $user = Auth::user();
        $biodata = $user->biodata;

        if (! $biodata) {
            return redirect()->to(route('profile.edit').'#biodata')
                ->with('info', 'Silakan isi biodata terlebih dahulu.');
        }

        if ($user->role === 'user') {
            return redirect()->to(route('profile.edit').'#biodata');
        }

        return view('biodata.edit', compact('biodata'));
    }

    public function update(BiodataRequest $request)
    {
        $user = Auth::user();
        $biodata = Biodata::withTrashed()->where('user_id', $user->id)->first();

        if (! $biodata) {
            return redirect()->to(route('profile.edit').'#biodata');
        }

        if ($biodata->trashed()) {
            $biodata->restore();
        }

        $data = $request->validated();
        if ($biodata->status_verifikasi === 'rejected') {
            $data['status_verifikasi'] = 'pending';
        }
        try {
            $biodata->update($data);
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                return back()
                    ->withInput()
                    ->withErrors(['nik' => 'NIK sudah terdaftar di sistem. Gunakan NIK yang valid milik Anda atau hubungi admin.']);
            }
            throw $e;
        }

        $userId = $user->id;
        Cache::forget("dashboard.user.{$userId}.biodata");
        $this->clearProfileCache();

        return redirect()->to(route('profile.edit').'#biodata')
            ->with('success', 'Biodata berhasil diperbarui.');
    }

    public function submitForVerification()
    {
        $user = Auth::user();
        if ($user->role !== 'user') {
            abort(403);
        }

        $biodata = $user->biodata;
        if (! $biodata) {
            return redirect()->to(route('profile.edit').'#biodata')
                ->with('error', 'Isi biodata terlebih dahulu.');
        }

        if ($biodata->status_verifikasi === 'verified') {
            return redirect()->to(route('profile.edit').'#biodata')
                ->with('info', 'Biodata sudah terverifikasi.');
        }

        if (! $biodata->isComplete()) {
            return redirect()->to(route('profile.edit').'#biodata')
                ->with('error', 'Lengkapi data nik, tempat lahir, tanggal lahir, alamat, dan pendidikan terlebih dahulu.');
        }

        if (! $user->hasRequiredDocuments()) {
            return redirect()->to(route('profile.edit').'#berkas')
                ->with('error', 'Upload KTP, Ijazah, dan Foto sebelum mengajukan verifikasi.');
        }

        $biodata->update([
            'status_verifikasi' => 'pending',
            'tanggal_verifikasi' => null,
            'verifikator_id' => null,
        ]);

        // Invalidate dashboard cache so pending count updates immediately
        Cache::forget('dashboard.admin.stats');
        Cache::forget('dashboard.admin.regionalSummary');

        // Notify all admins
        User::where('role', 'admin')->each(fn ($admin) => NotificationE::create([
            'user_id' => $admin->id,
            'judul' => 'Pengajuan Verifikasi Baru',
            'pesan' => $user->name.' telah mengajukan verifikasi biodata. Silakan review.',
            'status_baca' => false,
            'tipe' => 'status_update',
        ]));

        $userId = $user->id;
        Cache::forget("dashboard.user.{$userId}.biodata");
        $this->clearProfileCache();

        return redirect()->to(route('profile.edit').'#biodata')
            ->with('success', 'Pengajuan verifikasi berhasil dikirim. Menunggu validasi admin.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $b = Biodata::findOrFail($id);
        $b->delete();

        return redirect()->route('biodata.index')->with('success', 'Biodata dihapus.');
    }

    public function show($id = null)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'user') {
            return redirect()->to(route('profile.edit').'#biodata');
        }

        if ($id) {
            $biodata = Biodata::with('user', 'user.uploads')->findOrFail($id);
        } else {
            $biodata = $currentUser?->biodata;
        }

        return view('biodata.show', compact('biodata'));
    }
}
