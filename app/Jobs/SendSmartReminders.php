<?php

namespace App\Jobs;

use App\Models\KartuKuning;
use App\Models\NotificationE;
use App\Models\User;
use App\Notifications\KartuKuningExpiryReminder;
use App\Notifications\StatusPekerjaanReminder;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendSmartReminders implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $now = Carbon::now();

        $this->sendStatusPekerjaanReminders($now);
        $this->sendKartuExpiryReminders($now);
    }

    private function sendStatusPekerjaanReminders(Carbon $now): void
    {
        $sixMonthsAgo = $now->copy()->subMonths(6);
        $users = User::whereHas('statusPekerjaan', function ($query) use ($sixMonthsAgo) {
            $query->where('updated_at', '<=', $sixMonthsAgo);
        })->get();

        foreach ($users as $user) {
            if ($this->alreadyReminded($user->id, 'status_update', $now, 7)) {
                continue;
            }

            Notification::send($user, new StatusPekerjaanReminder);

            NotificationE::create([
                'user_id' => $user->id,
                'judul' => 'Pengingat: Perbarui Status Pekerjaan',
                'pesan' => 'Status pekerjaan Anda terakhir diperbarui lebih dari 6 bulan yang lalu. Silakan perbarui data Anda.',
                'status_baca' => false,
                'tipe' => 'status_update',
            ]);
        }
    }

    private function sendKartuExpiryReminders(Carbon $now): void
    {
        // Kartu yang akan expired dalam 30 hari
        $expiringSoon = KartuKuning::with('user')
            ->where('expired_at', '>=', $now)
            ->where('expired_at', '<=', $now->copy()->addDays(30))
            ->where('status', 'printed')
            ->get();

        foreach ($expiringSoon as $kartu) {
            if (! $kartu->user || $this->alreadyReminded($kartu->user->id, 'kartu_expired', $now, 7)) {
                continue;
            }

            Notification::send($kartu->user, new KartuKuningExpiryReminder);

            NotificationE::create([
                'user_id' => $kartu->user->id,
                'judul' => 'Pengingat: Masa Berlaku Kartu AK/1',
                'pesan' => 'Kartu Kuning Anda akan segera habis masa berlaku dalam 30 hari. Silakan lakukan pembaruan.',
                'status_baca' => false,
                'tipe' => 'kartu_expired',
            ]);
        }

        // Kartu yang sudah expired
        $alreadyExpired = KartuKuning::with('user')
            ->where('expired_at', '<', $now)
            ->where('status', 'printed')
            ->get();

        foreach ($alreadyExpired as $kartu) {
            if (! $kartu->user || $this->alreadyReminded($kartu->user->id, 'kartu_expired', $now, 30)) {
                continue;
            }

            $kartu->update(['status' => 'expired']);

            NotificationE::create([
                'user_id' => $kartu->user->id,
                'judul' => 'Pemberitahuan: Kartu AK/1 Kadaluarsa',
                'pesan' => 'Kartu Kuning Anda telah melewati masa berlaku 2 tahun. Segera lakukan pembaruan.',
                'status_baca' => false,
                'tipe' => 'kartu_expired',
            ]);
        }
    }

    private function alreadyReminded(int $userId, string $tipe, Carbon $now, int $days): bool
    {
        return NotificationE::where('user_id', $userId)
            ->where('tipe', $tipe)
            ->whereDate('created_at', '>=', $now->copy()->subDays($days)->toDateString())
            ->exists();
    }
}
