<?php

namespace App\Http\Controllers;

use App\Http\Traits\SharesNotifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use SharesNotifications;

    public function index()
    {
        $this->shareNotifications();
        $users = User::query()
            ->where('role', 'user')
            ->with(['biodata', 'statusPekerjaan']);

        if (request('search')) {
            $term = trim((string) request('search'));
            $users->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhereHas('biodata', fn ($qb) => $qb->where('nik', 'like', '%'.$term.'%'));
            });
        }

        if (request('status')) {
            $users->whereHas('statusPekerjaan', fn ($q) => $q->where('status_pekerjaan', request('status')));
        }

        if (request('kecamatan')) {
            $kecamatan = trim((string) request('kecamatan'));
            $users->whereHas('biodata', fn ($q) => $q->where('kecamatan', $kecamatan));
        }

        $users = $users->latest()->paginate(20)->withQueryString();

        $districts = $this->getPandeglangDistricts();

        return view('admin.users.index', compact('users', 'districts'));
    }

    public function exportSudahBekerja()
    {
        $users = User::where('role', 'user')
            ->whereHas('statusPekerjaan', fn ($q) => $q->where('status_pekerjaan', 'sudah_bekerja'))
            ->with(['biodata', 'statusPekerjaan'])
            ->latest()
            ->get();

        $csvHeader = ['No', 'Nama', 'NIK', 'Kecamatan', 'Desa/Kelurahan', 'Nama Perusahaan', 'Tanggal Update'];
        $rows = [];
        $no = 1;
        foreach ($users as $user) {
            $rows[] = [
                $no++,
                $user->name,
                $user->biodata->nik ?? '-',
                $user->biodata->kecamatan ?? '-',
                $user->biodata->desa_kelurahan ?? '-',
                $user->statusPekerjaan->nama_perusahaan ?? '-',
                $user->statusPekerjaan->tanggal_update ? $user->statusPekerjaan->tanggal_update->format('d/m/Y') : '-',
            ];
        }

        $filename = 'sudah_bekerja_'.now()->format('Ymd_His').'.csv';
        $handle = fopen('php://output', 'w');
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
        fputcsv($handle, $csvHeader);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return response($handle)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    private function getPandeglangDistricts(): array
    {
        $localPath = database_path('data/pandeglang_wilayah.json');
        if (is_file($localPath)) {
            $parsed = json_decode((string) file_get_contents($localPath), true);
            if (is_array($parsed) && isset($parsed['districts']) && is_array($parsed['districts'])) {
                return collect($parsed['districts'])->pluck('name')->filter()->sort()->values()->all();
            }
        }

        return [];
    }

    public function show(User $user)
    {
        $user->load(['biodata', 'statusPekerjaan', 'uploads', 'notificationsE' => fn ($q) => $q->latest()->take(10)]);

        return view('admin.user-details', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'atasan', 'user'])],
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ((int) $request->user()->id === (int) $user->id && $request->role !== $user->role) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.pengguna.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User dihapus.');
    }

    public function attention()
    {
        $needsAttention = User::where('role', 'user')
            ->where(function ($q) {
                $q->doesntHave('biodata')
                    ->orWhereHas('biodata', function ($qb) {
                        $qb->where('status_verifikasi', '!=', 'verified');
                    })
                    ->orWhereHas('statusPekerjaan', function ($qb) {
                        $qb->whereDate('tanggal_update', '<', now()->subDays(90)->toDateString());
                    });
            })
            ->with(['biodata', 'statusPekerjaan'])
            ->latest()
            ->paginate(20);

        return view('admin.perhatian', compact('needsAttention'));
    }
}
